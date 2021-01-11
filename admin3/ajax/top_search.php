<?php
include('../../logic/global.php');
include('../common/constants.php');
function top_search() {
    $mysqli = mysqliConn();
    $array_data = array();
    if (isset($_POST['start']) AND isset($_POST['end'])) {
        /* $sql  = "SELECT sub.count1 as search_terms_count, sub.date , sub.search_terms FROM (SELECT COUNT(search_terms) AS count1 , date , search_terms FROM searches WHERE searches.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY searches.search_terms , searches.date ORDER BY searches.date , COUNT(search_terms) DESC) as sub GROUP BY sub.date ORDER BY sub.date DESC"; */
        $top_search_sql  = "SELECT COUNT(search_terms) AS search_terms_count , date , search_terms FROM searches WHERE searches.date > '".$_POST['start']."' AND searches.date < '".$_POST['end']."' GROUP BY searches.search_terms ORDER BY COUNT(search_terms) DESC";
    } else {
        $top_search_sql  = "SELECT COUNT(search_terms) AS search_terms_count , date , search_terms FROM searches WHERE searches.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY searches.search_terms ORDER BY COUNT(search_terms) DESC";
    }
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
    
    $array_data['top_search']['search_date'] = $search_date;
    $array_data['top_search']['search_terms_count'] = $search_terms_count;
    $array_data['top_search']['search_terms'] = $search_terms;

    echo json_encode($array_data);
}
top_search();
?>