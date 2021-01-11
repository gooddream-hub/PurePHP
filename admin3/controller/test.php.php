<?php 
    
    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    function getTraffic(){
        include('../common/database.php');
        $page = apache_getenv("HTTP_HOST") . apache_getenv("REQUEST_URI");
        $ip = getUserIpAddr();
        $qry = "SELECT * FROM `track_ip` WHERE `ip` = '$ip' AND `month` ='".date('m')."' AND `year` = '".date('Y')."'";
        $result = $mysqli->query($qry);
        $num = mysqli_num_rows($result);
    
        if ($num == 0){
            $qry3 = "INSERT INTO `track_ip`(`ip`,`visits_per_visitor`,`month`,`year`,`date`,`updated_at`) VALUES ('$ip',1,'".date('m')."','".date('Y')."','".date('Y-m-d')."','".date('Y-m-d h:i:s')."')";
            $mysqli->query($qry3);
            //echo "new ip register";	
            $qry1 = "SELECT * FROM `track_unique_visitors` WHERE `month` = '".date('m')."' AND `year` = '".date('Y')."'";
            $result1 = $mysqli->query($qry1);
         
            if(mysqli_num_rows($result1) == 0) {
                $qry2 = "INSERT INTO `track_unique_visitors` (`unique_visiters`,`month`,`year`,`date`) VALUES (1,'".date('m')."','".date('Y')."','".date('Y-m-d')."')";
                $result2=$mysqli->query($qry2);
            } else {
                $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
                $count = $row1['unique_visiters'];
                $count = $count + 1;
                //echo "<br>";
                //echo "number of unique visiters is $count";
                $qry2 = "UPDATE `track_unique_visitors` SET `unique_visiters`='$count' WHERE `month` = '".date('m')."' AND `year` = '".date('Y')."'";
                $result2=$mysqli->query($qry2);
            }
            
        } else {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $visits_per_visitor = $row['visits_per_visitor'];
            $visits_per_visitor = $visits_per_visitor + 1;

            $qry9 = "UPDATE `track_ip` SET `visits_per_visitor` = '$visits_per_visitor',`updated_at` =  '".date('Y-m-d h:i:s')."' WHERE `ip` = '$ip' AND `month` ='".date('m')."' AND `year` = '".date('Y')."' AND DATE(updated_at) != '".date('Y-m-d')."'";
            $mysqli->query($qry9);
        }

        $qry4 = "SELECT `user_ip` FROM track_page_view where page='$page' and user_ip='$ip'  AND `month` = '".date('m')."' AND `year` = '".date('Y')."'";
        $result4 = $mysqli->query($qry4);
        if(mysqli_num_rows($result4) == 0)
        {
            $qry5 = "INSERT INTO `track_page_view` (`page`,`user_ip`,`month`,`year`,`date`) VALUES ('$page','$ip','".date('m')."','".date('Y')."','".date('Y-m-d')."')";
            $result5 = $mysqli->query($qry5);
          
            $qry6 = "SELECT * FROM total_page_view where page='$page' AND `month` = '".date('m')."' AND `year` = '".date('Y')."'";
            $result6 = $mysqli->query($qry6);
           
            if(mysqli_num_rows($result6)>=1) {
                $qry7 = "UPDATE `total_page_view` SET `totalvisit`= totalvisit+1 WHERE `page`='$page' AND `month` = '".date('m')."' AND `year` = '".date('Y')."'";
                $result7 = $mysqli->query($qry7);
            } else {
                $qry8 = "INSERT INTO `total_page_view` (`page`,`totalvisit`,`month`,`year`,`date`) VALUES ('$page',1,'".date('m')."','".date('Y')."','".date('Y-m-d')."')";
                $result8 = $mysqli->query($qry8);
            }
        }
        if (isset($_SERVER['HTTP_REFERER'])){
            $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
            $current_host = $_SERVER['SERVER_NAME'];
            if($referer_host != $current_host) {
                $qry9 = "INSERT INTO `track_referral_sites` (`referral_sites`,`date`) VALUES ('$referer_host','".date('Y-m-d h:i:s')."')";
                $result9 = $mysqli->query($qry9);
            }
        }
    }
    function get_domain($url) {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }
    getTraffic();   
?>