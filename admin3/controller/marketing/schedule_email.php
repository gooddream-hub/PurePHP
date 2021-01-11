<?php
include('../../../logic/global.php');
include('../../common/constants.php');
    function schedule_email(){
        $mysqli = mysqliConn();
        $markup = $_POST['markup'];
        $datetime = $_POST['date'];
        $subject = $_POST['subject'];

        $content = "";

        $markup = str_replace("&lt;", "<", $markup);
        $markup = str_replace("&gt;", ">", $markup);
        $markup = str_replace("[removed]", "", $markup);

        $pattern = "/((?<=<!--Content-->)(.*?)(?=<!--End-->))/is";

        if (preg_match_all($pattern, $markup, $matches_out)) {
            foreach($matches_out[0] as $row){
                $content .= $row;
            }
            $name = date('His');
            file_put_contents(NEWSLETTER_EMAIL_TEMPLATE_PATH.$name.".html", $content);
            store_schedule_email($name, $datetime, $subject);
        }  
    }
    function store_schedule_email($file_path, $delivery_date, $subject){
        $mysqli = mysqliConn();
        $sql = "INSERT INTO email_stats (file_path, delivery_date, subject) VALUES ('$file_path', '$delivery_date', '$subject')";
        $result = $mysqli->query($sql);
    }
    schedule_email();
?>