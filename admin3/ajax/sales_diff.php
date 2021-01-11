<?php
include('../../logic/global.php');
include('../common/constants.php');
function sales_diff() {
    $mysqli = mysqliConn();
    $array_data = array();
    
    if (isset($_POST['start']) AND isset($_POST['end'])) {
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $sales_diff_colors_list = array(
            '#33FF00',
            '#FF0000',
            '#FF9900',
            '#33CCFF',
            '#990033',
            '#000000',
            '#6600FF',
            '#FF3300'
        );

        $last_year_sales_diff_total = array();
        $start    = $_POST['start'].'-01-01';
        $end      = $_POST['end'].'-01-01';
        $getRangeYears   = range(gmdate('Y', strtotime($start)), gmdate('Y', strtotime($end)));
        $sales_diff_total = array();
        $i = 0;
        foreach($getRangeYears as $y => $getRangeYear) {
           $sales_diff_total_yearly = array();
            foreach($months as $m => $month) {
                $sales_diff_sql = "SELECT sum(grandtotal) as grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE YEAR(custinfo.order_date) = ".$getRangeYear." and MONTH(custinfo.order_date) = $month GROUP BY MONTH(custinfo.order_date)";
                if($sales_diff_result = $mysqli->query($sales_diff_sql)){
                    while($sales_diff_row = $sales_diff_result->fetch_assoc() ){
                        array_push($sales_diff_total_yearly,$sales_diff_row['grandtotal']);
                    }
                }
            }
           
            $sales_diff_total[$y]['label'] = $getRangeYear;
            $sales_diff_total[$y]['data'] = $sales_diff_total_yearly;
            $sales_diff_total[$y]['backgroundColor'] = $sales_diff_colors_list[array_rand($sales_diff_colors_list)];
            $i++;
        }
    }
    $array_data['sales_diff'] = $sales_diff_total;
    echo json_encode($array_data);
}
sales_diff();
?>