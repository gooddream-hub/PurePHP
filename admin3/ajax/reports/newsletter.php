<?php 
function newsletter_filter()
{
    if(isset($_POST['start_date']) && isset($_POST['end_date'])) {
        include('../../../logic/global.php');
        $mysqli = mysqliConn();

        $where = '';
        $cust_data = array();
        
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $sql = "SELECT * FROM email_stats WHERE delivery_date BETWEEN '".$start_date."' AND  '".$end_date."'
        ORDER by nid DESC";
        
        if($result = $mysqli->query($sql)){
            while($row = $result->fetch_assoc() ){
                $cust_data[] = array_map('utf8_encode', $row);
            } 
        }	
        echo json_encode($cust_data);
    }
}
newsletter_filter();
?>