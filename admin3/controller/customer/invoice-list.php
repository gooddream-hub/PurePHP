<?php
function get_invoices(){
	$mysqli = mysqliConn();
	$invoice = array();

	$sql = "SELECT custinfo.custid, shipfirst, shiplast, email, order_date, ship_date, payTYpe, ship_type, tracking FROM custinfo LEFT JOIN payment on custinfo.custid = payment.custid ORDER BY order_date desc LIMIT 5000";
	$result = $mysqli->query($sql);

	while($row = $result->fetch_assoc() ){
		$tracking = explode('"',$row['tracking']);
		$row['tracking'] = $tracking[0];
		$invoice[] = $row;
	} 

	return $invoice;

}

?>


