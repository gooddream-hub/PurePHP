<?php
include('../../logic/global.php');
include('../common/constants.php');
function track_traffic() {
    $mysqli = mysqliConn();
    $array_data = array();
    $months = [1,2,3,4,5,6,7,8,9,10,11,12];
    
/*     $current_year_unique_visitors = array();
    $last_year_unique_visitors = array();
    $current_year_total_visits = array();
    $last_year_total_visits = array();
    $current_year_total_page_view = array();
    $last_year_total_page_view = array(); */

   
    $visitors_total = array();
    if (isset($_POST['start']) AND isset($_POST['end'])) {
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

        $start = $_POST['start'].'-01-01';
        $end = $_POST['end'].'-01-01';
        $getRangeYears = range(gmdate('Y', strtotime($start)), gmdate('Y', strtotime($end)));
        $total_visitors_yearly = array();
        foreach($getRangeYears as $y => $getRangeYear) {
           
            $total_visitors_yearly1 = array();
            $total_visitors_yearly2 = array();
            $total_visitors_yearly3 = array();
            $unique_visitors_yearly = array();
            $total_visits_yearly = array();
            $total_pages_view_yearly = array();
            foreach($months as $month) {
                $current_year_unique_visitors_sql = "SELECT `unique_visiters`,`month` FROM `track_unique_visitors` WHERE `year` = $getRangeYear and `month` = $month";
                if($current_year_unique_visitors_result = $mysqli->query($current_year_unique_visitors_sql)){
                    $current_year_unique_visitors_row = $current_year_unique_visitors_result->fetch_assoc();
                    array_push($unique_visitors_yearly,$current_year_unique_visitors_row['unique_visiters']);
                
                }

                $total_visitors_yearly1['label'] = $getRangeYear.' : Unique Visitors';
                $total_visitors_yearly1['data'] = $unique_visitors_yearly;
                $total_visitors_yearly1['backgroundColor'] = $sales_diff_colors_list[array_rand($sales_diff_colors_list)];

                $current_year_total_visits_sql = "SELECT sum(visits_per_visitor) as visits_per_visitor FROM `track_ip` WHERE `year` = $getRangeYear and `month` = $month";
                if($current_year_total_visits_result = $mysqli->query($current_year_total_visits_sql)){
                    $current_year_total_visits_row = $current_year_total_visits_result->fetch_assoc();
                    array_push($total_visits_yearly,$current_year_total_visits_row['visits_per_visitor']);
                
                }

                $total_visitors_yearly2['label'] = $getRangeYear.' : Total Visits';
                $total_visitors_yearly2['data'] = $total_visits_yearly;
                $total_visitors_yearly2['backgroundColor'] = $sales_diff_colors_list[array_rand($sales_diff_colors_list)];

                $current_year_total_page_view_sql = "SELECT `totalvisit`,`month` FROM `total_page_view` WHERE `year` = $getRangeYear and `month` = $month";
                if($current_year_total_page_view_result = $mysqli->query($current_year_total_page_view_sql)){
                    $current_year_total_page_view_row = $current_year_total_page_view_result->fetch_assoc();
                    array_push($total_pages_view_yearly,$current_year_total_page_view_row['totalvisit']);
                }

                $total_visitors_yearly3['label'] = $getRangeYear.' : Total Page View';
                $total_visitors_yearly3['data'] = $total_pages_view_yearly;
                $total_visitors_yearly3['backgroundColor'] = $sales_diff_colors_list[array_rand($sales_diff_colors_list)];

               
            }
            array_push($total_visitors_yearly,$total_visitors_yearly1);
            array_push($total_visitors_yearly,$total_visitors_yearly2);
            array_push($total_visitors_yearly,$total_visitors_yearly3);
           
        }
    }
   
    $array_data['total_visits'] = $total_visitors_yearly;
    echo json_encode($array_data);
}
track_traffic();
?>