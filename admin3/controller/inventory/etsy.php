<?php

include('../../common/database.php');
include('../../config/config.php');
include('../../libraries/Pinterestnew/autoload.php');
include('../../models/product_model.php');
include('../../models/etsytransactions_model.php');
include('../../models/invoices_model.php');
include('../../models/payment_model.php');
include('../../models/orderdetails_model.php');
ini_set('max_execution_time', 300);

include('../../libraries/etsy/MY_Etsy.php');

/* submit */
if($_POST['type'] == 'product_listing') {

    $my_etsy_listing = new MY_Etsy();

    $error = 0;
    /* etsy listing required fields */
    $description = "Description:\n\n";
    $description .= strip_tags($_POST['descr']);
    $description .= "\n\nFeatures:\n\n";
    $description .= strip_tags(str_replace('<br>', "\n", $_POST['features']));
    
    $listing_data = array(
        'category_id'           => 0,
        'taxonomy_id'           => $_POST['category_id'],
        'title'                 => $_POST['title'],
        'description'           => $description,
        'price'                 => (float)$_POST['saleprice'] ? (float)$_POST['saleprice'] : (float)$_POST['retail'],
        'quantity'              => $_POST['invamount'] ? (int)$_POST['invamount'] : 1,
        "who_made"              => "i_did",
        "item_weight"           => round($_POST['weight'] * 16),
        "item_weight_units"     => 'oz',
        "item_length"           => $_POST['minlength'],
        "item_width"            => $_POST['minwidth'],
        "item_height"           => $_POST['minheight'],
        "item_dimensions_unit"  => 'in',
        "is_supply"             => 'true',
        "state"                 => 'active',
        "when_made"             => 'made_to_order',
        'shipping_template_id'  => $_POST['shipping_templates'] ? $_POST['shipping_templates'] : "9255149368",
    );
    $invid = $_POST['product_id'];
    $listing['listing_data'] = $listing_data;
    $listing['listing_img'] = (($_POST['img'])?$_POST['img']:array());

    $my_etsy_listing->getAccessToken(SITE_URL . 'admin3/view/inventory/etsy.php');

    if($listing_data) {
        $resp['access'] = 'success';
        $resp['msg'] = 'Start add product';
        
        $valid = $my_etsy_listing->validateListing($listing);
        
        if($valid['is_valid']) {
            $res = $my_etsy_listing->postListing($invid, $listing);
            $resp['info'] = $res[$invid];
        } else {
            $resp['info']['data'] = 'error';
            $resp['info']['msg'] = 'Please check fields: '. implode(', ', $valid['errors']);
            $resp['info']['errors'] = $valid['errors'];
        }
    } else {
        $resp['access'] = 'error';
        $resp['msg'] = 'No any products found for add to etsy.';
    }
    echo json_encode($resp);
}

function getproductcatslist()
{
    $type = $this->input->post('title');
    $cat_list = $this->Product_model->getProductsCatsListByType($type);
    $suggest = array();
    if(count($cat_list) > 0)
    {
        $is_zippers = 0;
        foreach($cat_list as $cat) {
            if ($cat['type'] == 'Zippers') {
                $is_zippers = 1;
                $name = ucfirst($cat['teeth_type']).($cat['seperating'] == 0 ? ' Non-' : ' ').'Separating Zippers';
                $suggest[] = array(
                    'name' => $name,
                    'id'   => $name
                );
            }
            else {
                $suggest[] = array(
                    'name' => $cat['type'],
                    'id'   => $cat['type']
                );
            }
        }
        if ($is_zippers) {
            $suggest[] = array(
                'name' => '3-way Zippers',
                'id'   => '3-way Zippers'
            );
            $suggest[] = array(
                'name' => 'Hidden / Concealed Zippers',
                'id'   => 'Hidden / Concealed Zippers'
            );
        }
    }
    
    $data['suggest'] = $suggest;
    echo json_encode($data);
}

if($_POST['type'] == 'product_form') {
    $data = array();
    if(($_POST['product']) && $_POST['product_id']){
        $product = $_POST['product'];
        $product_id = $_POST['product_id'];
    } else {
        $product = '';
        $product_id = '';
    }
    echo json_encode(true);
}

if($_POST['type'] == 'getproductlist'){

    // include('../../models/product_model.php');
    $title = $_POST['title'];
    $product_list = getProductsListByTitle($title);
    $suggest = array();
    if(count($product_list) > 0)
    {
        foreach($product_list as $product) {
            $name = $product['cat']." ".$product['type']." ".$product['color'];
            if ($product['type'] == 'Zippers') {
                $name .= ' '.$product['length'].' '.$product['teeth_size'].' '.$product['teeth_type'].' '.
                    ($product['seperating'] ? 'separating' : 'non-separating').
                    ($product['hidden'] ? ' hidden' : '').
                    ($product['3_way'] ? ' 3-way' : '');
            }
            $suggest[] = array(
                'name' => $name,
                'id'   => $product['invid']
            );
        }
    }
    
    $data['suggest'] = $suggest;
    echo json_encode($data);

}

if($_POST['type'] == 'getproductbyId') {

    $id = $_POST['id'];
    $product = getProductsById($id);

    $my_etsy_product = new MY_Etsy();

    if(count($product) > 0)
    {
        $featureArray = json_decode($product[0]["features"], true);
        $feature = '';
        if(is_array($featureArray)){
            foreach($featureArray as $key => $val){
                $feature .= $key.": ".$val."<br>";
            }
        }
        $product[0]["features"] = $feature;
        $product[0]["img"] = !empty($product[0]["img"]) ? json_decode($product[0]["img"]) : array();
    }
    $my_etsy_product->getAccessToken(SITE_URL . 'admin3/view/inventory/etsy.php');
    $shipping_temlates = $my_etsy_product->getShippingTemplates();
    $categories = $my_etsy_product->getTaxanomy();
    $data['categories']          = $categories;
    $data['shipping_temlates']   = $shipping_temlates;
    $data['product'] = $product;
    echo json_encode($data);
}

function get_listings(){
    //$this->my_etsy->getAccessToken(site_url('inventory/etsy/get_listings'));
    $api_url = 'https://openapi.etsy.com/v2/shops/10548297/listings/active';
    
    $listing_data = array(
        'keywords' => 'goat'
    );

    $success = $this->my_etsy->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response);		
    //print_r($response);

    $ids = [];
    foreach($response->results as $row){
        $ids[] = $row->listing_id;
    }

    print_r($ids);
    return $ids;

}


function update_leather_price(){
    $this->my_etsy->getAccessToken(site_url('inventory/etsy/update_leather_price'));

    $listing_ids = $this->get_listings();

    foreach($listing_ids as $id){
        $api_url = 'https://openapi.etsy.com/v2/listings/'.$id.'/inventory';	
        $listing_data = array(
            'listing_id' => $id
        );
        $success = $this->my_etsy->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response);	

        $new_data = json_decode(json_encode($response->results->products), true);
        $sub_data = [];

        foreach($new_data as $key=>$val){
            foreach($new_data[$key]['offerings'] as $k=>$v){
                $price = ceil($new_data[$key]['offerings'][$k]['price']['amount']*.8);
                
                $new_data[$key]['offerings'][$k]['price']['amount'] = $price;
                $new_data[$key]['offerings'][$k]['price']['currency_formatted_short'] = "$".$price/100;
                $new_data[$key]['offerings'][$k]['price']['currency_formatted_long'] = "$".($price/100)." USD";
                $new_data[$key]['offerings'][$k]['price']['currency_formatted_raw'] = $price/100;

            }
        }

        //hardcoded working:
        //$json = '[{"product_id":4411405610,"sku":"20051","property_values":[{"property_id":513,"property_name":"Square Feet","scale_id":null,"scale_name":null,"values":["3"],"value_ids":[51476091056]}],"offerings":[{"offering_id":4278661409,"price":{"amount":9000,"divisor":100,"currency_code":"USD","currency_formatted_short":"$9.00","currency_formatted_long":"$9.00 USD","currency_formatted_raw":"9.00"},"quantity":25,"is_enabled":1,"is_deleted":0}],"is_deleted":0},{"product_id":4411405612,"sku":"20052","property_values":[{"property_id":513,"property_name":"Square Feet","scale_id":null,"scale_name":null,"values":["3.5"],"value_ids":[64207773585]}],"offerings":[{"offering_id":4278661411,"price":{"amount":1200,"divisor":100,"currency_code":"USD","currency_formatted_short":"$12.00","currency_formatted_long":"$12.00 USD","currency_formatted_raw":"12.00"},"quantity":37,"is_enabled":1,"is_deleted":0}],"is_deleted":0},{"product_id":4180627423,"sku":"20053","property_values":[{"property_id":513,"property_name":"Square Feet","scale_id":null,"scale_name":null,"values":["4"],"value_ids":[51476091106]}],"offerings":[{"offering_id":4392562908,"price":{"amount":1400,"divisor":100,"currency_code":"USD","currency_formatted_short":"$14.00","currency_formatted_long":"$14.00 USD","currency_formatted_raw":"14.00"},"quantity":26,"is_enabled":1,"is_deleted":0}],"is_deleted":0},{"product_id":4180627425,"sku":"20054","property_values":[{"property_id":513,"property_name":"Square Feet","scale_id":null,"scale_name":null,"values":["4.5"],"value_ids":[61904111374]}],"offerings":[{"offering_id":4392562910,"price":{"amount":1600,"divisor":100,"currency_code":"USD","currency_formatted_short":"$16.00","currency_formatted_long":"$16.00 USD","currency_formatted_raw":"16.00"},"quantity":16,"is_enabled":1,"is_deleted":0}],"is_deleted":0},{"product_id":4411405614,"sku":"20055","property_values":[{"property_id":513,"property_name":"Square Feet","scale_id":null,"scale_name":null,"values":["5"],"value_ids":[53559611293]}],"offerings":[{"offering_id":4278661413,"price":{"amount":1800,"divisor":100,"currency_code":"USD","currency_formatted_short":"$18.00","currency_formatted_long":"$18.00 USD","currency_formatted_raw":"18.00"},"quantity":13,"is_enabled":1,"is_deleted":0}],"is_deleted":0},{"product_id":4411405616,"sku":"20056","property_values":[{"property_id":513,"property_name":"Square Feet","scale_id":null,"scale_name":null,"values":["5.5"],"value_ids":[61904111248]}],"offerings":[{"offering_id":4278661415,"price":{"amount":2000,"divisor":100,"currency_code":"USD","currency_formatted_short":"$20.00","currency_formatted_long":"$20.00 USD","currency_formatted_raw":"20.00"},"quantity":2,"is_enabled":1,"is_deleted":0}],"is_deleted":0},{"product_id":4180627427,"sku":"20057","property_values":[{"property_id":513,"property_name":"Square Feet","scale_id":null,"scale_name":null,"values":["6"],"value_ids":[53559611339]}],"offerings":[{"offering_id":4392562912,"price":{"amount":2200,"divisor":100,"currency_code":"USD","currency_formatted_short":"$22.00","currency_formatted_long":"$22.00 USD","currency_formatted_raw":"22.00"},"quantity":2,"is_enabled":1,"is_deleted":0}],"is_deleted":0}]';


        $json = json_encode($new_data);
        //print_r($json);

        $arr = array(
                //'products' => json_encode($sub_data),
                //'products' => json_encode($new_data['products']),
                'products' => $json,
                'price_on_property' => 513,
                'quantity_on_property' => 513, 
                'sku_on_property' => 513
            );
        $api_url = 'https://openapi.etsy.com/v2/listings/'.$id.'/inventory';
        $success = $this->my_etsy->client->CallAPI($api_url, 'PUT', $arr, array('FailOnAccessError'=>true), $response);
        print_r($response);

    }//end foreach listing_ids loop
}

function etsy_shipment_name_to_our_naming($etsy_shipping_method) {
    $etsy_shipping_methods = [
        'USPS First Class Package Services' => 'USPS 1st Class',
        'USPS First Class Mail' => 'USPS 1st Class',
        'USPS Parcel Select Ground' => 'USPS ParcelSelect', 
        'USPS Priority Mail' => 'USPS Priority',
        'USPS Priority Mail Express' => 'USPS Express', // CHECK 

        'USPS Priority Mail International' => 'USPS Priority', // also exists 'USPS Priority Intl'
        'USPS Priority Mail Express International' => 'USPS Intl', //check 
        'USPS First Class International Package Service' => 'USPS 1st Class', //check
    ];

    return isset( $etsy_shipping_methods[$etsy_shipping_method] )
        ? $etsy_shipping_methods[$etsy_shipping_method]
        : 'USPS Priority';
}

/* Get Access Token
    * $r - redirect url after access token recived
    *
**/
function callback() {
    if($this->my_etsy->etsyAccessToken(site_url('inventory/etsy'))) {
        redirect($this->input->get('r'));
    } else {
        die('Can\'t receive access token');
    }
}

if($_POST['type'] == 'cron_get_payment') {

    $my_etsy_cron = new MY_Etsy();

    $my_etsy_cron->getAccessToken(site_url('inventory/etsy/cron_get_payment'));
    $user = $my_etsy_cron->getEtsyUser();
    $limit = 100;
    
    $listing_data = array(
        'user_id' => $user->user_id,
        'limit'   => $limit
    );
    $api_url = 'https://openapi.etsy.com/v2/users/'.$user->user_id.'/shops';
    $success = $my_etsy_cron->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response);
    $receipts = array();
    $listing_ids = array();
    $sku = array();

    if (!empty($response->results)) {
        $today   = date('d-m-Y');
        $end_time = strtotime('+1 day', strtotime($today));
        $start_time = strtotime('-7 day', strtotime($today));
        $start_day = date('d-m-Y', $start_time);
        $end_day = date('d-m-Y', $end_time);
        $transactions = getAllTransations($start_time);
        foreach ($response->results as $shop) {
            $listing_data = array(
                'min_created' => $start_day,
                'max_created' => $end_day,
                'was_paid'    => 'true',
                'limit'       => $limit
            );
            $api_url = 'https://openapi.etsy.com/v2/shops/'.$shop->shop_id.'/receipts';
            $success = $my_etsy_cron->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response_rc);

            foreach ($response_rc->results as $rc) {

                $receipts[$rc->receipt_id] = $rc;
                $listing_data = array(
                    'limit'   => $limit
                );
                $api_url = 'https://openapi.etsy.com/v2/receipts/'.$rc->receipt_id.'/transactions';
                $success = $my_etsy_cron->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response_tr);
                
                foreach ($response_tr->results as $tr) {
                    if (!isset($transactions[$tr->transaction_id])) {
                        $listing_ids[] = $tr->listing_id;
                        $sku[] = $tr->product_data->sku; 
                        if (isset($receipts[$tr->receipt_id])) {
                            $receipts[$tr->receipt_id]->transaction[] = $tr;
                        }
                    } else {
                        unset($receipts[$tr->receipt_id]);
                    }
                }
                


            }
        }
    }
    $listing_ids = array_unique($listing_ids);

    $countries = array();
    if($receipts){
        $products = $products_amount = array();
        $results = $this->Product_model->getProductsByEtsyIds($listing_ids);
        
    
        foreach ($results as $row) {
            $products[$row['listing_id']]   = $row;
            $products_amount[$row['invid']] = (int)$row['invamount'];
        }
        unset($results);
        
        $amount = 0;
        $counter = 0;
        foreach($receipts as $receipt){

            //don't add this transaction - it's already been processed
            echo "<br>receipt->order_id: <br>";
            echo  $receipt->order_id;
            echo "<br>That receipt id should be in the database if previous order<br>";
            echo "<br>results of this->etsytransactions_model->getByEtsy(receipt->order_id)<br>";
            print_r( $this->etsytransactions_model->getByEtsy($receipt->order_id) );
            echo "<br> end print of getByEtsy<br>";

            if(!getByEtsy($receipt->order_id)) {

                $user_info = explode(' ', $receipt->name);
                if(!isset($countries[$receipt->country_id])){
                    $api_url = 'https://openapi.etsy.com/v2/countries/'.$receipt->country_id;
                    $success = $my_etsy_cron->client->CallAPI($api_url, 'GET', $listing_data, array('FailOnAccessError'=>true), $response_c);
                    $countries[$receipt->country_id] = $response_c->results[0]->iso_country_code;
                }
                $last  = array_pop($user_info);
                $first = implode(' ', $user_info);

                $data = array(
                    'shiplast'   => $last,
                    'shipfirst'  => $first,
                    'shipadone'  => $receipt->first_line,
                    'shipadtwo'  => $receipt->second_line,
                    'shipcity'   => $receipt->city,
                    'shipstate'  => $receipt->state,
                    'shipzip'    => $receipt->zip,
                    'shipco'     => $countries[$receipt->country_id],

                    'billlast'   => $last,
                    'billfirst'  => $first,
                    'billadone'  => $receipt->first_line,
                    'billadtwo'  => $receipt->second_line,
                    'billcity'   => $receipt->city,
                    'billstate'  => $receipt->state,
                    'billzip'    => $receipt->zip,
                    'billco'     => $countries[$receipt->country_id],

                    'order_date' => date('Y-m-d'),
                    'email'      => $receipt->buyer_email,
                    'newsletter' => 'yes',
                    'custid'     => get_net_custinfo(),
                );

                $payment = array(
                    'custid'     => $data['custid'],
                    'payType'    => 'Etsy',
                    'shipping'   => $receipt->total_shipping_cost,
                    'ship_type'  => etsy_shipment_name_to_our_naming($receipt->shipping_details->shipping_method),
                    'grandtotal' => $receipt->grandtotal,
                );

                $invoice_id = invoiceSave($data);
                paymentSave($payment);
                $amount++;

                foreach($receipt->transaction as $transaction){
                    if(isset($products[$transaction->listing_id])){
                        // echo "listid is: <br>";
                        // echo $transaction->listing_id;
                        // echo "<br>";
                        insert_transaction(
                            $transaction->transaction_id,
                            $transaction->listing_id,
                            $products[$transaction->listing_id]['invid'],
                            $receipt->creation_tsz,
                            $receipt->receipt_id,
                            $data['custid']
                        );
                        $product = $products[$transaction->listing_id];

                        //override values if there is a sku
                        if( $sku[$counter] != ""){
                            $products_amount[$sku[$counter]] = $products_amount[$product['invid']];
                            $product['invid'] = $sku[$counter];
                            $prodArr = getProductById($sku[$counter]);
                            $product['cat'] = $prodArr['cat'];
                            $product['color'] = $prodArr['color'];
                            $product['type'] = $prodArr['type'];
                        }
                        
                        $counter = $counter+1;
                        $order = array(
                                'custid'     => $data['custid'],
                                'invid'      => $product['invid'],
                                'cat'        => $product['cat'],
                                'type'       => $product['type'],
                                'color'      => $product['color'],
                                'price'      => $transaction->price,
                                'quant'      => $transaction->quantity,
                                'total'      => $receipt->total_price,
                        );
                        orderdetailsSave($order);

                        // Mike - something going wrong with the below line:
                        $products_amount[$product['invid']] = $products_amount[$product['invid']] ? ($products_amount[$product['invid']] - $transaction->quantity) : 0;
                        UpdateProduct($product['invid'], array('invamount' => $products_amount[$product['invid']]),0);
                    }
                }

            }

        }
        echo $amount.' transaction(s) received';
    } else {
        echo 'you have no transactions';
    }
}
