<?php
include('../../config/config.php');

if(isset($_POST['email']) || isset($_POST['phone']) || isset($_POST['firstname']) || isset($_POST['lastname']) || isset($_POST['bill_city']) || isset($_POST['bill_state']) || isset($_POST['ship_city']) || isset($_POST['ship_state'])){
    include('../../../logic/global.php');
    $mysqli = mysqliConn();
    
    $where = '';
    $cust_data = array();
    if(!empty($_POST['email'])){
        $where .= $where=='' ? 'email="'.$_POST['email'].'"' : ' AND email="'.$_POST['email'].'"';
    }
    if(!empty($_POST['phone'])){
        $where .= $where=='' ? 'phone="'.$_POST['phone'].'"' : ' AND phone="'.$_POST['phone'].'"';
    }
    if(!empty($_POST['firstname'])){
        $where .= $where=='' ? 'shipfirst="'.$_POST['firstname'].'"' : ' AND shipfirst="'.$_POST['firstname'].'"';
    }
    if(!empty($_POST['lastname'])){
        $where .= $where=='' ? 'shiplast="'.$_POST['lastname'].'"' : ' AND shiplast="'.$_POST['lastname'].'"';
    }
    if(!empty($_POST['bill_city'])){
        $where .= $where=='' ? 'billcity="'.$_POST['bill_city'].'"' : ' AND billcity="'.$_POST['bill_city'].'"';
    }
    if(!empty($_POST['bill_state'])){
        $where .= $where=='' ? 'billstate="'.$_POST['bill_state'].'"' : ' AND billstate="'.$_POST['bill_state'].'"';
    }
    if(!empty($_POST['ship_city'])){
        $where .= $where=='' ? 'shipcity="'.$_POST['ship_city'].'"' : ' AND shipcity="'.$_POST['ship_city'].'"';
    }
    if(!empty($_POST['ship_state'])){
        $where .= $where=='' ? 'shipstate="'.$_POST['ship_state'].'"' : ' AND shipstate="'.$_POST['ship_state'].'"';
    }

    $sql = "SELECT custid, shipfirst, shiplast, email, phone, shipcity, shipstate FROM custinfo WHERE $where ORDER BY order_date desc";
    
    if($result = $mysqli->query($sql)){
        while($row = $result->fetch_assoc() ){
            $cust_data[] = array_map('utf8_encode', $row);
        } 
    }	
    echo json_encode($cust_data);
}

if(isset($_POST['custid']) && isset($_POST['note'])){
    include('../../../logic/global.php');
    $mysqli = mysqliConn();
    try{
        $note = $_POST['note'];
        $custid = $_POST['custid'];
		$sql = "UPDATE custinfo SET notes = '$note' WHERE custid = $custid";
		$result = $mysqli->query($sql);

		echo "Note Update Successfully!";
	}  catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}

if(isset($_POST['st_name']) && isset($_POST['st_email']) && isset($_POST['standing'])){
    include('../../../logic/global.php');
    $mysqli = mysqliConn();
    try{
        $name = $_POST['st_name'];
        $email = $_POST['st_email'];
        $standing = $_POST['standing'];

        $sql = "SELECT * FROM standing WHERE name = '$name' AND email = '$email'";
        $result = $mysqli->query($sql);
		if(mysqli_num_rows($result) > 0){
            $sql_1 = "UPDATE standing SET standing = '$standing' WHERE name = '$name' AND email = '$email'";
        } else{
            $sql_1 = "INSERT INTO  standing(standing, name, email)
	      VALUES ('".$standing."','".$name."','".$email."')";
        };
        if($result_1 = $mysqli->query($sql_1)){
            echo "Standing Update Successfully!";
        };
	}  catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
}

function get_customer_data($email, $firstname, $lastname){
    $mysqli = mysqliConn();
    // echo "<pre>";
    // print_r($mysqli) ; die;
    $cust_data = array();
    $order_data = array();
    $count = 0;
    $sql = "SELECT custid, shipfirst, shiplast, email, phone, shipcity, shipstate, tracking, order_date FROM custinfo WHERE email='$email' AND shipfirst='$firstname' AND shiplast='$lastname' ORDER BY order_date desc ";
    // $sql = "SELECT custid, phone, order_date FROM custinfo WHERE email=$email AND shipfirst=$firstname AND shiplast=$lastname ORDER BY order_date desc LIMIT 100";
    if ($result = $mysqli->query($sql)) {
        while(($row = $result->fetch_assoc()) !== null){
            $cust_data[] = $row;            
            $rrr = get_items($row['custid']);
            $order_data[] = $rrr;
            $count += $rrr['count'];
        } 
    }
	
    return array('order_data'=>$order_data, 'count' => $count, 'cust_data' => $cust_data);
}

function get_items($custid){
	$mysqli = mysqliConn();
    $items = array();
    $details = array();
    $count = 0;

    $sql = "SELECT * FROM custinfo LEFT JOIN payment on custinfo.custid = payment.custid WHERE custinfo.custid = $custid";
	$result = $mysqli->query($sql);

	while($row = $result->fetch_assoc() ){
		$details[] = $row;
	} 

	$sql = "SELECT invid, cat, type, color, price, quant, total, wholesale FROM orderdetails where custid = $custid";
	$result = $mysqli->query($sql);

	while($row = $result->fetch_assoc() ){
        $items[] = $row;
        $count++;
	}

	return array('items'=>$items, 'count'=>$count, 'details'=>$details);

}
?>