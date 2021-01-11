<?php

if($_SESSION['group'] == "admin"){
	include('../common/constants.php');
	include('../view/layout/admin.php');
} elseif($_SESSION['group'] == "warehouse"){
	include('../view/layout/warehouse.php');
} elseif($_SESSION['group'] == "marketing"){
	include('../view/layout/marketing.php');
} else {
	die();
}