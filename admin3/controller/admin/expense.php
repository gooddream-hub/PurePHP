<?php 
$method = $_SERVER['REQUEST_METHOD'];
$check_msg = 0;
if ($method == 'POST') {
    $check_msg = 1;

    include('../../common/database.php');
    
    if(isset($_POST['cost1'])){
        
        $id = $_POST['id'];

        for($i=1; $i<=$id; $i++){
            $cat = $_POST["cat$i"];
            $des = $_POST["descrip$i"];
            $cost = $_POST["cost$i"];
            $date = $_POST["date$i"];
            $sql = "INSERT INTO  expense(category, descrip, cost, date)
                VALUES ('".$cat."','".$des."','".$cost."','".$date."')";
            if($result = $mysqli->query($sql)){
                $msg = "Insert Successful";
            };
        }

    $mysqli -> close();
    } elseif(isset($_FILES["csvFile"])) { // bulk upload code below

        $extensions = array("CSV", "csv");
        $tmp = explode('.', $_FILES["csvFile"]["name"]);
        $file_extension = end($tmp);

        if(in_array($file_extension, $extensions)){
            $handle = fopen($_FILES["csvFile"]["tmp_name"], "r");
            $shipping = 0;
            
            while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
                if(stripos($data[3], "fedex") !== false OR stripos($data[3], "usps") !== false OR stripos($data[3], "easypost") !== false){ //total up the shipping costs
                    $shipping += (abs($data[6]) ); //convert to positive integer
                } else { //insert a row into expenses
                    // $this->db->query(
                    //     $this->db->insert_string('expense', array(
                    //          'category' => 'other'
                    //         ,'descrip' => $data[3]
                    //         ,'cost' => (abs($data[6]) )
                    //         ,'date' => strftime("%F",strtotime($data[1]))
                    //     ))
                    // );	
                    $cat = $data[4];
                    $des = $data[3];
                    $cost = abs($data[6]);
                    $date = strftime("%F",strtotime($data[1]));
                    $sql = "INSERT INTO  expense(category, descrip, cost, date)
                        VALUES ('".$cat."','".$des."','".$cost."','".$date."')";
                    if($result = $mysqli->query($sql)){
                    };					
                }
            }	
            $month = $_POST["month"];
            $cat = "postal";
            $des = "";
            $sql = "INSERT INTO  expense(category, descrip, cost, date)
                        VALUES ('".$cat."','".$des."','".$shipping."','".$month."')";
            if($result = $mysqli->query($sql)){
                $msg = "Bulk Upload Successful";
            };
            
        } else {
            $msg = "File type was incorrect.  Please use a CSV file.";
        }
        $mysqli -> close();
    } else {
        $msg = "Please enter cost";
 }
}

?>