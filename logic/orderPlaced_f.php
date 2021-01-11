<?
function order(){
#get input info
$bill_first = $_POST['bill_first'];
$bill_last = $_POST['bill_last'];
$bill_adone = $_POST['bill_adone'];
$bill_adtwo = $_POST['bill_adtwo'];
$bill_city = $_POST['bill_city'];
$bill_state = $_POST['bill_state'];
$bill_zip = $_POST['bill_zip'];
$bill_co = $_POST['bill_co'];
$email =  $_POST['email'];
$signup = $_POST['signup'];
$ship_first = $_POST['ship_first'];
$ship_last = $_POST['ship_last'];
$ship_adone = $_POST['ship_adone'];
$ship_adtwo = $_POST['ship_adtwo'];
$ship_city = $_POST['ship_city'];
$ship_state = $_POST['ship_state'];
$ship_co = $_POST['ship_co'];
$ship_zip = $_POST['ship_zip'];
$order_date = date(m."-".j."-".Y);

$cc_type= $_POST['cc_type'];
$cc_num = $_POST['cc_num'];
$expir_month = $_POST['expir_month'];
$expir_year = $_POST['expir_year'];
$ship = $_POST['ship'];
$ship_type = $_POST['ship_type'];
$grandtotal = $_POST['grandtotal'];
$tax = $_POST['tax'];

#check to see if custid already in dbase to make sure that repeat entries are not entered
$custid = $_SESSION['custid'];
$query = "SELECT custid FROM custinfo WHERE custid = '$custid'";
$result = mysql_query($query); 
$row = mysql_fetch_assoc($result);

#insert custinfo
$query1 = "INSERT INTO custinfo (custid, shipfirst, shiplast, shipadone, shipadtwo, shipcity, shipstate, shipzip, shipco, email, newsletter, billfirst, billlast, billadone, billadtwo, billcity, billstate, billzip, billco, order_date) values ('$custid', '$ship_first', '$ship_last','$ship_adone','$ship_adtwo','$ship_city','$ship_state','$ship_zip','$ship_co','$email', '$sign_up','$bill_first','$bill_last','$bill_adone','$bill_adtwo','$bill_city','$bill_state','$bill_zip','$bill_co','$order_date')";
$query2 = "INSERT INTO payment (custid, cctype, ccnumber, ccmonth, ccyear, shipping, ship_type, grandtotal) values ('$custid', '$cc_type','$cc_num','$expir_month','$expir_year','$ship', '$ship_type', '$grandtotal')";

#check if making duplicate entry (if no custid in dbase, then go ahead and insert info and send email
if ($row['custid']!=$custid){
	$result1 = mysql_query($query1);
	$result2 = mysql_query($query2);
	
	$arr = $_SESSION['cart'];
	foreach ($arr as $rownum=>$value){
		if ($arr[$rownum][6]!==""){
			$message="<td>&nbsp;<s>$".number_format(($arr[$rownum][3]),2,'.','')."</s>&nbsp;<font color=\"red\">".number_format(($arr[$rownum][6]),2,'.','')."</font></td>";
			$saleprice1 = $arr[$rownum][6];
		} elseif ($arr[$rownum][6]==""){
			$message= "<td>&nbsp;$".number_format(($arr[$rownum][3]),2,'.','')."</td>"; #price
			$saleprice1 = $arr[$rownum][3];
		}
		$message_3 = "<tr><td>&nbsp;".$arr[$rownum][0]."</td><td>&nbsp;".$arr[$rownum][1]."</td><td>&nbsp;".$arr[$rownum][2].$message."</td><td>&nbsp;$".number_format(($arr[$rownum][2]*$saleprice1),2,'.','')."</td><td></td></tr>";
		$message_4 = $message_4.$message_3;
	}
	$arr = $_SESSION['cart'];
	foreach ($arr as $rownum=>$value){
		$fabtype= $arr[$rownum][0];
		$color = $arr[$rownum][1];
		$quantity = $arr[$rownum][2];
		$productid = $arr[$rownum][4];
		$inventory = $arr[$rownum][5];
		if ($arr[$rownum][6]!==""){
				$saleprice1 = $arr[$rownum][6];
		}
		if ($arr[$rownum][6]==""){
			$saleprice1 = $arr[$rownum][3];
		}
		$fabtotal = number_format(($arr[$rownum][2]*$saleprice1),2,'.','');
		$wholesale = $arr[$rownum][7]*$quantity;
		$new_q = ($inventory-$quantity);
		$query3 = "INSERT INTO orderdetails  (custid, fabtype, fabcolor, fabquantity, fabtotal, wholesale, store) values ('$custid','$fabtype','$color','$quantity','$fabtotal', '$wholesale', 'MJTrends')"; 
		$query4 = "Update inven_master SET invamount = '$new_q' WHERE invid = '$productid'"; 
		$result3 = mysql_query($query3);
		$result4 = mysql_query($query4);
	}
	email();
}

function email(){
	mail($email.', orders@MJTrends.com', 'MJTrends Order#'.$custid, 
	 '<html><body>
	<img src="http://www.mjtrends.com/images/email_banner_left.jpg" width="273" height="85"><img src="http://www.mjtrends.com/images/email_banner_right.jpg" width="270" height="85">
	<p>Thank you '.$bill_first.' for ordering with MJTrends.com!</p>
	<p>Your order details are as follows:</p>
	<table width="550px" border="0" bordercolor="black" cellspacing="0" cellpadding="0">
	  <tr> 
		<td width="20%" bgcolor="#E4EFFF"><strong>Product </strong></td>
		<td width="17%"bgcolor="#E4EFFF"><strong>Color</strong></td>
		<td width="20%" bgcolor="#E4EFFF"><strong>Quantity</strong></td>
		<td width="17%" bgcolor="#E4EFFF"><strong>Price</strong></td>
		<td width="17%" bgcolor="#E4EFFF"><strong>Total</strong></td>
		<td width="9%" bgcolor="#E4EFFF">&nbsp;</td>
	  </tr>
	 '.$message_4.'	
	<tr> 
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr> 
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td colspan="2"><div align="right"><strong> 
			'.$ship_type.'
			Shipping</strong></div></td>
		<td>$ 
		  '.number_format(($shipping),2,'.','').'
		</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr> 
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><div align="right"><strong>Tax:</strong></div></td>
		<td> $ '.number_format(($tax),2,'.','').'
		</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr> 
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td style="border-top: 1px solid #000033;"><div align="right"><strong>Grand 
			Total:</strong></div></td>
		<td style="border-top: 1px solid #000033;"> $ '.number_format(($grandtotal),2,'.','').'
		</td>
		<td>&nbsp;</td>
	  </tr>
	  <tr> 
		<td colspan="6">&nbsp;</td>
	  </tr>
	</table>
	<p> Orders are typically processed and shipped within 24 hours of receipt, if 
	  you have any questions:<br>
	  about your order you may send them to: <a href="mailto:sales@mjtrends.com">sales@mjtrends.com</a></p>
	<p> To check the details of your order or check the shipping status of your order, 
	  you may click the<br>
	  following link at any time: <a href="http://www.mjtrends.com/tracking.php">www.MJTrends.com/tracking.php</a></p> 
	<p> Once again, thank you for shopping at MJTrends.com. Come back and see us for 
	  great prices on <br>hard to find fabrics and notions!</p>
	<p>
	  Sincerely,<br>
	  MJTrends.com Customer Service</body></html>', 
		"To: ".$bill_first."<".$email.">\n" . 
		"From: MJTrends <orders@MJTrends.com>\n" . 
		"MIME-Version: 1.0\n" . 
		"Content-type: text/html; charset=iso-8859-1"); 
}
?>