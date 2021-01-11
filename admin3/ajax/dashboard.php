<?php
include('../../logic/global.php');
include('../common/constants.php');
function top_search() {
    $mysqli = mysqliConn();
    $array_data = array();

    //Top search graph start
    /* $sql  = "SELECT sub.count1 as search_terms_count, sub.date , sub.search_terms FROM (SELECT COUNT(search_terms) AS count1 , date , search_terms FROM searches WHERE searches.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY searches.search_terms , searches.date ORDER BY searches.date , COUNT(search_terms) DESC) as sub GROUP BY sub.date ORDER BY sub.date DESC"; */
    /* $top_search_sql  = "SELECT COUNT(search_terms) AS search_terms_count , date , search_terms FROM searches WHERE searches.date > '".$_POST['start']."' AND searches.date < '".$_POST['end']."' GROUP BY searches.search_terms ORDER BY COUNT(search_terms) DESC"; */

    $top_search_sql  = "SELECT COUNT(search_terms) AS search_terms_count , date , search_terms FROM searches WHERE searches.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY searches.search_terms ORDER BY COUNT(search_terms) DESC";
    
    $search_date = array();
    $search_terms_count = array();
    $search_terms = array();
   
    if($top_search_result = $mysqli->query($top_search_sql)){
        while($top_search_row = $top_search_result->fetch_assoc() ){
            array_push($search_date,$top_search_row['date']);
            array_push($search_terms_count,$top_search_row['search_terms_count']);
            array_push($search_terms,$top_search_row['search_terms']);
        } 
    }
    //Top search graph end
    //Top selling graph start
    $top_selling_sql  = "SELECT O.ID, O.custid, O.invid, O.cat, O.type, custinfo.order_date AS date, inven_mj.title as selling_item , count(inven_mj.invid) as selling_item_count FROM orderdetails as O LEFT JOIN custinfo ON O.custid = custinfo.custid LEFT JOIN inven_mj ON O.invid = inven_mj.invid WHERE custinfo.order_date >= DATE_SUB(CURDATE(), INTERVAL 90 DAY) GROUP BY inven_mj.title ORDER BY count(inven_mj.invid) DESC LIMIT 20";
    $selling_date = array();
    $selling_item = array();
    $selling_item_count = array();
    if($top_selling_result = $mysqli->query($top_selling_sql)){
        while($top_selling_row = $top_selling_result->fetch_assoc() ){
            array_push($selling_date,$top_selling_row['date']);
            array_push($selling_item_count,$top_selling_row['selling_item_count']);
            array_push($selling_item,$top_selling_row['selling_item']);
        } 
    }	
    //Top search graph end
    //Monthly sales graph start
    /* $monthly_sales_sql  = "SELECT YEAR(custinfo.order_date) AS year, MONTH(custinfo.order_date) AS month, sum(grandtotal) as grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE payType = 'Etsy' GROUP BY year, month ORDER BY year DESC,month DESC limit 12";

    $monthly_non_etsy_sales_sql  = "SELECT YEAR(custinfo.order_date) AS year, MONTH(custinfo.order_date) AS month, sum(grandtotal) as non_etsy_grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE payType != 'Etsy' GROUP BY year, month ORDER BY year DESC,month DESC limit 12"; */
    
    $monthly_sales_sql  = "SELECT YEAR(custinfo.order_date) AS year, MONTH(custinfo.order_date) AS month, sum(grandtotal) as grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE payType = 'Etsy' AND YEAR(custinfo.order_date) = ".date('Y')." GROUP BY year, month ORDER BY year DESC,month DESC limit 12";

    $monthly_non_etsy_sales_sql  = "SELECT YEAR(custinfo.order_date) AS year, MONTH(custinfo.order_date) AS month, sum(grandtotal) as non_etsy_grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE payType != 'Etsy' AND YEAR(custinfo.order_date) = ".date('Y')." GROUP BY year, month ORDER BY year DESC,month DESC limit 12";

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
    //Monthly sales graph end
    //Sales diff graph start
    $months = [1,2,3,4,5,6,7,8,9,10,11,12];
    $current_year_sales_diff_total = array();
    $last_year_sales_diff_total = array();
    foreach($months as $month) {
        $current_year_sales_diff_sql = "SELECT sum(grandtotal) as current_year_grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE YEAR(custinfo.order_date) = ".date('Y')." and MONTH(custinfo.order_date) = $month GROUP BY MONTH(custinfo.order_date)";
        if($current_year_sales_diff_result = $mysqli->query($current_year_sales_diff_sql)){
            while($current_year_sales_diff_row = $current_year_sales_diff_result->fetch_assoc() ){
                array_push($current_year_sales_diff_total,$current_year_sales_diff_row['current_year_grandtotal']);
            }
        }
        $last_year_sales_diff_sql = "SELECT sum(grandtotal) as last_year_grandtotal FROM `payment` LEFT JOIN custinfo ON payment.custid = custinfo.custid WHERE YEAR(custinfo.order_date) = ".date('Y',strtotime('-1 year'))." and MONTH(custinfo.order_date) = $month GROUP BY MONTH(custinfo.order_date)";
      
        if($last_year_sales_diff_result = $mysqli->query($last_year_sales_diff_sql)){
            while($last_year_sales_diff_row = $last_year_sales_diff_result->fetch_assoc() ){
                array_push($last_year_sales_diff_total,$last_year_sales_diff_row['last_year_grandtotal']);
            }
        }
    }
    //Sales diff graph end
    //Unique visitors graph start
    $months = [1,2,3,4,5,6,7,8,9,10,11,12];
    $current_year_unique_visitors = array();
    $last_year_unique_visitors = array();
    $current_year_total_visits = array();
    $last_year_total_visits = array();
    $current_year_total_page_view = array();
    $last_year_total_page_view = array();
    foreach($months as $month) {
        $current_year_unique_visitors_sql = "SELECT `unique_visiters`,`month` FROM `track_unique_visitors` WHERE `year` = ".date('Y')." and `month` = $month";
        if($current_year_unique_visitors_result = $mysqli->query($current_year_unique_visitors_sql)){
            $current_year_unique_visitors_row = $current_year_unique_visitors_result->fetch_assoc();
            array_push($current_year_unique_visitors,$current_year_unique_visitors_row['unique_visiters']);
          
        }
        $current_year_total_visits_sql = "SELECT sum(visits_per_visitor) as visits_per_visitor FROM `track_ip` WHERE `year` = ".date('Y')." and `month` = $month";
        if($current_year_total_visits_result = $mysqli->query($current_year_total_visits_sql)){
            $current_year_total_visits_row = $current_year_total_visits_result->fetch_assoc();
            array_push($current_year_total_visits,$current_year_total_visits_row['visits_per_visitor']);
          
        }
        $last_year_unique_visitors_sql = "SELECT `unique_visiters`,`month` FROM `track_unique_visitors` WHERE `year` = ".date('Y',strtotime('-1 year'))." and `month` = $month";
      
        if($last_year_unique_visitors_result = $mysqli->query($last_year_unique_visitors_sql)){
            $last_year_unique_visitors_row = $last_year_unique_visitors_result->fetch_assoc();
            array_push($last_year_unique_visitors,$last_year_unique_visitors_row['unique_visiters']);
        }
        $last_year_total_visits_sql = "SELECT sum(visits_per_visitor) as visits_per_visitor FROM `track_ip` WHERE `year` = ".date('Y',strtotime('-1 year'))." and `month` = $month";
      
        if($last_year_total_visits_result = $mysqli->query($last_year_total_visits_sql)){
            $last_year_total_visits_row = $last_year_total_visits_result->fetch_assoc();
            array_push($last_year_total_visits,$last_year_total_visits_row['visits_per_visitor']);
        }

        $current_year_total_page_view_sql = "SELECT `totalvisit`,`month` FROM `total_page_view` WHERE `year` = ".date('Y')." and `month` = $month";
        if($current_year_total_page_view_result = $mysqli->query($current_year_total_page_view_sql)){
            $current_year_total_page_view_row = $current_year_total_page_view_result->fetch_assoc();
            array_push($current_year_total_page_view,$current_year_total_page_view_row['totalvisit']);
          
        }
        $last_year_total_page_view_sql = "SELECT `totalvisit`,`month` FROM `total_page_view` WHERE `year` = ".date('Y',strtotime('-1 year'))." and `month` = $month";
      
        if($last_year_total_page_view_result = $mysqli->query($last_year_total_page_view_sql)){
            $last_year_total_page_view_row = $last_year_total_page_view_result->fetch_assoc();
            array_push($last_year_total_page_view,$last_year_total_page_view_row['totalvisit']);
        }
    }
    //Unique visitors graph end

    //Top referral sites start.
    $top_referral_sites_sql  = "SELECT COUNT(referral_sites) AS referral_sites_count , date , referral_sites FROM track_referral_sites WHERE DATE(date) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY referral_sites ORDER BY COUNT(referral_sites) DESC LIMIT 20";
    
    $referral_sites_date = array();
    $referral_sites_count = array();
    $referral_sites = array();
   
    if($top_referral_sites_result = $mysqli->query($top_referral_sites_sql)){
        while($top_referral_sites_row = $top_referral_sites_result->fetch_assoc() ){
            array_push($referral_sites_date,$top_referral_sites_row['date']);
            array_push($referral_sites_count,$top_referral_sites_row['referral_sites_count']);
            array_push($referral_sites,$top_referral_sites_row['referral_sites']);
        } 
    }
    //Top referral sites end

    $array_data['top_referral_sites']['referral_sites_date'] = $referral_sites_date;
    $array_data['top_referral_sites']['referral_sites_count'] = $referral_sites_count;
    $array_data['top_referral_sites']['referral_sites'] = $referral_sites;

    $array_data['unique_visiters']['current_year_unique_visitors'] = $current_year_unique_visitors;
    $array_data['unique_visiters']['last_year_unique_visitors'] = $last_year_unique_visitors;
    $array_data['unique_visiters']['current_year'] = date('Y').' : Unique Visitors';
    $array_data['unique_visiters']['last_year'] = date('Y',strtotime('-1 year')).' : Total Visitors';
    $array_data['total_visits']['current_year_total_visits'] = $current_year_total_visits;
    $array_data['total_visits']['last_year_total_visits'] = $last_year_total_visits;
    $array_data['total_visits']['current_year'] = date('Y').' : Total Visits';
    $array_data['total_visits']['last_year'] = date('Y',strtotime('-1 year')).' : Total Visits';
    $array_data['total_page_view']['current_year_total_page_view'] = $current_year_total_page_view;
    $array_data['total_page_view']['last_year_total_page_view'] = $last_year_total_page_view;
    $array_data['total_page_view']['current_year'] = date('Y').' : Total Page View';
    $array_data['total_page_view']['last_year'] = date('Y',strtotime('-1 year')).' : Total Page View';

    $array_data['sales_diff']['current_year_sales_diff_total'] = $current_year_sales_diff_total;
    $array_data['sales_diff']['last_year_sales_diff_total'] = $last_year_sales_diff_total;
    $array_data['sales_diff']['current_year'] = date('Y');
    $array_data['sales_diff']['last_year'] = date('Y',strtotime('-1 year'));

    $array_data['monthly_sales']['monthly_sales_date'] = array_reverse($monthly_sales_date);
    $array_data['monthly_sales']['monthly_sales_total'] = array_reverse($monthly_sales_total);	
    $array_data['monthly_sales']['monthly_non_etsy_sales_total'] = array_reverse($monthly_non_etsy_sales_total);	

    $array_data['top_search']['search_date'] = $search_date;
    $array_data['top_search']['search_terms_count'] = $search_terms_count;
    $array_data['top_search']['search_terms'] = $search_terms;

    $array_data['top_selling']['selling_date'] = $selling_date;
    $array_data['top_selling']['selling_item'] = $selling_item;
    $array_data['top_selling']['selling_item_count'] = $selling_item_count;
    echo json_encode($array_data);
}
top_search();
?>