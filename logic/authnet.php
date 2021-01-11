<?php

class Authnet
{
    // Set these variables prior to use
    private $login    = "7Yyf8C36CFz9";
    private $transkey = "9323355nzYLAsr9u";
    private $test     = false;

    private $params   = array();
    private $results  = array();

    private $approved = false;
    private $declined = false;
    private $error    = true;

    private $fields;
    private $response;
    private $url;

    private static $instance;

    private function __construct()
    {
        if (empty($this->login) || empty($this->transkey))
        {
            throw new Exception("You have not configured your Authnet login credentials.");
        }

        $subdomain = ($this->test) ? 'test' : 'secure';
        $this->url = "https://" . $subdomain . ".authorize.net/gateway/transact.dll";

        $this->params['x_delim_data']     = "TRUE";
        $this->params['x_delim_char']     = "|";
        $this->params['x_relay_response'] = "FALSE";
        $this->params['x_url']            = "FALSE";
        $this->params['x_version']        = "3.1";
        $this->params['x_method']         = "CC";
        $this->params['x_type']           = "AUTH_CAPTURE";
        $this->params['x_login']          = $this->login;
        $this->params['x_tran_key']       = $this->transkey;
    }

    public static function instance()
    {
        if (!self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __clone()
    {
        throw new Exception("Only one instance of Authnet should be running at one time.");
    }

    public function __toString()
    {
        if (!$this->params)
        {
            return (string) $this;
        }

        $output  = "";
        $output .= '<table summary="Authnet Results" id="authnet">' . "\n";
        $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Outgoing Parameters</b></th>' . "\n" . '</tr>' . "\n";

        foreach ($this->params as $key => $value)
        {
            $output .= "\t" . '<tr>' . "\n\t\t" . '<td><b>' . $key . '</b></td>';
            $output .= '<td>' . $value . '</td>' . "\n" . '</tr>' . "\n";
        }

        if ($this->results)
        {
            $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Incomming Parameters</b></th>' . "\n" . '</tr>' . "\n";
			
            $response = array("Response Code", "Response Subcode", "Response Reason Code",
                              "Response Reason Text", "Approval Code", "AVS Result Code",
                              "Transaction ID", "Invoice Number", "Description", "Amount",
                              "Method", "Transaction Type", "Customer ID", "Cardholder First Name",
                              "Cardholder Last Name", "Company", "Billing Address", "City",
                              "State", "Zip", "Country", "Phone", "Fax", "Email", "Ship to First Name",
                              "Ship to Last Name", "Ship to Company", "Ship to Address",
                              "Ship to City", "Ship to State", "Ship to Zip", "Ship to Country",
                              "Tax Amount", "Duty Amount", "Freight Amount", "Tax Exempt Flag",
                              "PO Number", "MD5 Hash", "Card Code (CVV2/CVC2/CID) Response Code",
                              "Cardholder Authentication Verification Value (CAVV) Response Code");

            foreach ($this->results as $key => $value)
            {
                if ($key > 40) break;
                $output .= "\t" . '<tr>' . "\n\t\t" . '<td><b>' . $response[$key] . '</b></td>';
                $output .= '<td>' . $value . '</td>' . "\n" . '</tr>' . "\n";
            }
        }

        $output .= '</table>' . "\n";
        return $output;
    }

    public function process($retries = 3)
    {
        $this->prepareParameters();
        $ch = curl_init($this->url);
        $count = 0;
        while ($count < $retries)
        {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($this->fields, "& "));
            $this->response = curl_exec($ch);
            $this->parseResults();

            if ($this->getResultResponseFull() == "Approved")
            {
                $this->approved = true;
                $this->declined = false;
                $this->error    = false;
                break;
            }
            else if ($this->getResultResponseFull() == "Declined")
            {
                $this->approved = false;
                $this->declined = true;
                $this->error    = false;
                break;
            }
            $count++;
        }
        curl_close($ch);
    }

    private function prepareParameters()
    {
        foreach ($this->params as $key => $value)
        {
            $this->fields .= "$key=" . urlencode($value) . "&";
        }
    }

    private function parseResults()
    {
        $this->results = explode("|", $this->response);
    }

    public function setTransaction($cardnum, $expiration, $amount, $cvv, $billfirst, $billlast, $billAd1, $billCity, $billState, $billZip, $billCo, $custid, $email, $phone)
    {
        $this->params['x_card_num']  = (string) trim($cardnum);
        $this->params['x_exp_date']  = 		    $expiration;
        $this->params['x_amount']    = (float)  $amount;
    	$this->params['x_card_code'] = 		    $cvv;
		$this->params['x_first_name'] = 		$billfirst;
    	$this->params['x_last_name'] = 		    $billlast;		
    	$this->params['x_address'] = 		    $billAd1;		
    	$this->params['x_city'] = 		    	$billCity;	
    	$this->params['x_state'] = 		    	$billState;
    	$this->params['x_zip'] = 			    $billZip;
    	$this->params['x_country'] = 		    $billCo;
		$this->params['x_invoice_num'] = 		$custid;
        $this->params['x_cust_id'] =            $custid;
        $this->params['x_email'] =              '';
		$this->params['x_phoneNumber'] = 		$phone;

    	if (empty($this->params['x_card_num']) || empty($this->params['x_exp_date']) || empty($this->params['x_amount']))
    	{
    	    throw new Exception("Required information for transaction processing omitted.");
    	}
    }

    public function setParameter($field = "", $value = null)
    {
        $field = (is_string($field)) ? trim($field) : $field;
        $value = (is_string($value)) ? trim($value) : $value;
        if (!is_string($field))
        {
            throw new Exception("setParameter() arg 1 must be a string or integer: " . gettype($field) . " given.");
        }
        if (!is_string($value) && !is_numeric($value) && !is_bool($value))
        {
            throw new Exception("setParameter() arg 2 must be a string, integer, or boolean value: " . gettype($value) . " given.");
        }
        if (empty($field))
        {
            throw new Exception("setParameter() requires a parameter field to be named.");
        }
        if ($value === "")
        {
            throw new Exception("setParameter() requires a parameter value to be assigned: $field");
        }
        $this->params[$field] = $value;
    }

    public function setTransactionType($type = "")
    {
        $type      = strtoupper(trim($type));
        $typeArray = array("AUTH_CAPTURE", "AUTH_ONLY", "PRIOR_AUTH_CAPTURE", "CREDIT", "CAPTURE_ONLY", "VOID");
        if (!in_array($type, $typeArray))
        {
            throw new Exception("setTransactionType() requires a valid value to be assigned.");
        }
        $this->params['x_type'] = $type;
    }

    public function getResultResponse()
    {
        return $this->results[0];
    }

    public function getResultResponseFull()
    {
        $response = array("", "Approved", "Declined", "Error");
        return $response[$this->results[0]];
    }

    public function isApproved()
    {
        return $this->approved;
    }

    public function isDeclined()
    {
        return $this->declined;
    }

    public function isError()
    {
        return $this->error;
    }

    public function getResponseSubcode()
    {
        return $this->results[1];
    }

    public function getResponseCode()
    {
        return $this->results[2];
    }

    public function getResponseText()
    {
        return $this->results[3];
    }

    public function getAuthCode()
    {
        return $this->results[4];
    }

    public function getAVSResponse()
    {
        return $this->results[5];
    }

    public function getTransactionID()
    {
        return $this->results[6];
    }

    public function getInvoiceNumber()
    {
        return $this->results[7];
    }

    public function getDescription()
    {
        return $this->results[8];
    }

    public function getAmount()
    {
        return $this->results[9];
    }

    public function getPaymentMethod()
    {
        return $this->results[10];
    }

    public function getTransactionType()
    {
        return $this->results[11];
    }

    public function getCustomerID()
    {
        return $this->results[12];
    }

    public function getCHFirstName()
    {
        return $this->results[13];
    }

    public function getCHLastName()
    {
        return $this->results[14];
    }

    public function getCompany()
    {
        return $this->results[15];
    }

    public function getBillingAddress()
    {
        return $this->results[16];
    }

    public function getBillingCity()
    {
        return $this->results[17];
    }

    public function getBillingState()
    {
        return $this->results[18];
    }

    public function getBillingZip()
    {
        return $this->results[19];
    }

    public function getBillingCountry()
    {
        return $this->results[20];
    }

    public function getPhone()
    {
        return $this->results[21];
    }

    public function getFax()
    {
        return $this->results[22];
    }

    public function getEmail()
    {
        return $this->results[23];
    }

    public function getShippingFirstName()
    {
        return $this->results[24];
    }

    public function getShippingLastName()
    {
        return $this->results[25];
    }

    public function getShippingCompany()
    {
        return $this->results[26];
    }

    public function getShippingAddress()
    {
        return $this->results[27];
    }

    public function getShippingCity()
    {
        return $this->results[28];
    }

    public function getShippingState()
    {
        return $this->results[29];
    }

    public function getShippingZip()
    {
        return $this->results[30];
    }

    public function getShippingCountry()
    {
        return $this->results[31];
    }

    public function getTaxAmount()
    {
        return $this->results[32];
    }

    public function getDutyAmount()
    {
        return $this->results[33];
    }

    public function getFreightAmount()
    {
        return $this->results[34];
    }

    public function getTaxExemptFlag()
    {
        return $this->results[35];
    }

    public function getPONumber()
    {
        return $this->results[36];
    }

    public function getMD5Hash()
    {
        return $this->results[37];
    }

    public function getCVVResponse()
    {
        return $this->results[38];
    }

    public function getCAVVResponse()
    {
        return $this->results[39];
    }
}
