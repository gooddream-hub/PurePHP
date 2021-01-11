<?php
$mysqli_local = new mysqli("localhost", "root", "", "mjtrends_mjtrends");
if ($mysqli_local->connect_errno) {
    echo "Failed to connect to local MySQL: " . $mysqli_local->connect_error;
}

$mysqli_remote = new mysqli("mjtrends.com", "mjtrends_hedon", "mikeyry", "mjtrends_mjtrends");
if ($mysqli_remote->connect_errno) {
    echo "Failed to connect to remote MySQL: " . $mysqli_remote->connect_error;
}

// read-write tables
$schema = array();
$schema[] =	array('table' => 'ad', 			'key' => 'adid');
$schema[] = array('table' => 'ad_costs',	'key' => 'id');
$schema[] = array('table' => 'ad_stats',	'key' => 'uid');
$schema[] = array('table' => 'applique',	'key' => 'aid');
$schema[] = array('table' => 'articles',	'key' => 'id');
$schema[] = array('table' => 'cat',			'key' => 'id');
$schema[] = array('table' => 'coupon',		'key' => 'cid');
$schema[] = array('table' => 'coupon_code',	'key' => 'ckey');
$schema[] = array('table' => 'custinfo',	'key' => 'custid');
$schema[] = array('table' => 'email',		'key' => 'date');
$schema[] = array('table' => 'expense',		'key' => 'ID');
$schema[] = array('table' => 'find_it',		'key' => 'ID');
$schema[] = array('table' => 'forum',		'key' => 'ID');
$schema[] = array('table' => 'inven_purch',	'key' => 'pid');
$schema[] = array('table' => 'newsletter_stats','key' => 'key');
$schema[] = array('table' => 'newsletter_template','key' => 'id');
$schema[] = array('table' => 'newsletters',	'key' => 'nid');
$schema[] = array('table' => 'orderdetails','key' => 'ID');
$schema[] = array('table' => 'pattern_style','key' => 'styleID');
$schema[] = array('table' => 'pattern_type','key' => 'patID');
$schema[] = array('table' => 'payment',		'key' => 'ID');
$schema[] = array('table' => 'quotes',		'key' => 'ID');
$schema[] = array('table' => 'review',		'key' => 'id');
$schema[] = array('table' => 'searches',	'key' => 'ID');
$schema[] = array('table' => 'timesheet',	'key' => 'tid');
$schema[] = array('table' => 'tips',		'key' => 'id');

//build query for local to get last insert ids
$queryLocal = '';
foreach($schema as $row){
	$key = $row['key'];
	$table = $row['table'];
	
	$queryLocal .= "SELECT $key FROM $table ORDER BY $key DESC LIMIT 1;";
}

//execute query against localhost and build requestArray
$requestArray = array();
if ($mysqli_local->multi_query($queryLocal)) {
    do {
        /* store first result set */
        if ($result = $mysqli_local->store_result()) {
        	$finfo = $result->fetch_field_direct(0);
            while ($row = $result->fetch_row()) {
				$requestArray[] = array('table' => $finfo->table, 'key' => $finfo->name, 'value' => $row[0]);
            }
            $result->free();
        }
    } while ($mysqli_local->next_result());
}

foreach($requestArray as $row){
	$table = $row['table'];
	$key = $row['key'];
	$value = $row['value'];

	$query = "SELECT * FROM $table WHERE $key > '$value' ";
	
	if ($result = $mysqli_remote->query($query)) {
		if($result->num_rows > 0){
			$query = "INSERT INTO $table VALUES ";
			while ($row = $result->fetch_assoc()) {
	        	$value = " '".implode("','", $row)."' ";
	        	$query .= "($value),";
	    	}

	    	$query = rtrim($query, ',');
		}
    	$result->close();
    }

    //insert updated data into backup db
	$mysqli_local->query($query);
}

$updateTables = array('getid', 'mvt');
foreach($updateTables as $table){
	$query = "SELECT * FROM $table;";

	if ($result = $mysqli_remote->query($query)) {
		if($result->num_rows > 0){
			$query = "INSERT INTO ".$table." VALUES ";
			while ($row = $result->fetch_assoc()) {
	        	$value = " '".implode("','", $row)."' ";
	        	$query .= "($value),";
	    	}

	    	$query = rtrim($query, ',');
		}
    	$result->close();
    }

	//truncate local table 
	$mysqli_local->query('TRUNCATE TABLE '.$table.'');
	
    //insert updated data into backup db
	//$query = $mysqli_local->real_escape_string($query);
	
	$mysqli_local->query($query);
	
}

// update tables - requires full backup
/*
daily:

inven_mj

weekly:

users
standing
wordpress tables
mjt_crm
*/
