<?php
include('../../logic/global.php');
include('../common/constants.php');
function monthly_sales() {
    $mysqli = mysqliConn();
    $array_data = array();
    if (isset($_POST['year'])) {
       
        $monthly_sales_sql  = "SELECT YEAR(custinfo.order_date) AS year, MONTH(custinfo.order_date) AS month, sum(grandtotal) as grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE payType = 'Etsy' AND YEAR(custinfo.order_date) = ".$_POST['year']." GROUP BY year, month ORDER BY year DESC,month DESC limit 12";

        $monthly_non_etsy_sales_sql  = "SELECT YEAR(custinfo.order_date) AS year, MONTH(custinfo.order_date) AS month, sum(grandtotal) as non_etsy_grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE payType != 'Etsy' AND YEAR(custinfo.order_date) = ".$_POST['year']." GROUP BY year, month ORDER BY year DESC,month DESC limit 12";
    }
    $monthly_sales_date = array();
    $monthly_sales_total = array();
    $monthly_non_etsy_sales_total = array();
   
    if($monthly_sales_result = $mysqli->query($monthly_sales_sql)){
        while($monthly_sales_row = $monthly_sales_result->fetch_assoc() ){
            array_push($monthly_sales_date,date('M',strtotime('01-'.$monthly_sales_row['month']."-".$monthly_sales_row['year']))." ".$monthly_sales_row['year']);
            array_push($monthly_sales_total,$monthly_sales_row['grandtotal']);
        } 
    }
    if($monthly_non_etsy_sales_result =  $mysqli->query($monthly_non_etsy_sales_sql)){
        while($monthly_non_etsy_row = $monthly_non_etsy_sales_result->fetch_assoc() ){
            array_push($monthly_non_etsy_sales_total,$monthly_non_etsy_row['non_etsy_grandtotal']);
        } 
    }
    
    $array_data['monthly_sales']['monthly_sales_date'] = array_reverse($monthly_sales_date);
    $array_data['monthly_sales']['monthly_sales_total'] = array_reverse($monthly_sales_total);	
    $array_data['monthly_sales']['monthly_non_etsy_sales_total'] = array_reverse($monthly_non_etsy_sales_total);	

    echo json_encode($array_data);
}
monthly_sales();
?>