<?php
//if cookie user is set, but session is not, then getUser and then get rank
//if cookie user is not set, do nothing
if(isset($_COOKIE['user']) && !isset($_SESSION['user']) ){
	$user->getUser();
}

if(isset($_SESSION['user'])){
	$user->getUserRank($_SESSION['user']['points']);
	eval('$rewardArray = array('.$_SESSION["user"]["reward"].');');
	$reward->getNextReward($_SESSION['user']['points'], $rewardArray);
} 

$mysqli = mysqliConn();

$query = "SELECT uid, username, avatar, points FROM users ORDER BY points DESC";
$rankResult = $mysqli->query($query);