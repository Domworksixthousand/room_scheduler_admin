<?php
#error handler
error_reporting(E_ALL);
ini_set('display_errors', 1);


function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
    

    error_log($errorMessage . "\n", 3, "error_log.txt");


    echo "<div style='color: red; font-weight: bold;'>Something went wrong! Please try again later.</div>";

    return true;
}

#connection sa mysql
session_start();
$conn1 = mysqli_connect("10.0.0.161","it_dev","dev","ems_dev","3306");
if ($conn1->connect_error) {
    error_log("Database connection1 failed: " . $conn1->connect_error);
    die("We are experiencing technical difficulties. Please try again later.");
}


$conn2 = mysqli_connect("localhost","root","","room_sched_db");
if ($conn2->connect_error) {
    error_log("Database connection2 failed: " . $conn2->connect_error);
    die("We are experiencing technical difficulties. Please try again later.");
}


#get data kun available
 if(isset($_SESSION['admin_login'])){
   $admin_id = $_SESSION['admin_login'];

   $get_data = $conn1->prepare("SELECT * FROM `employee` WHERE `Employee_ID` = ?");
   $get_data->bind_param("i",$admin_id);
   $get_data->execute();
   $result_get = $get_data->get_result();
   if($result_get->num_rows>0){
    while($row_admin = mysqli_fetch_assoc($result_get)){
        $lastname = htmlspecialchars($row_admin['LastName'] ?? '');
        $firstname = htmlspecialchars($row_admin['FirstName'] ?? '');
        $middlename = htmlspecialchars($row_admin['MiddleName'] ?? '');
        $fullname = $lastname . ' ' . $firstname . ' ' . $middlename;
    }
   }
 }




 #timezone
date_default_timezone_set("asia/manila");
 #get date today
 $datetoday = date("Y-m-d");
  #get year today
 $year = date("Y");
  #get time today
 $timetoday = date("h:i:s a");
 $timetoday2 = date("H:i:s");

 
 $status_occupied = 'Occupied';
 $status_new = "Done";
 $status_old = "Occupied";
 $update_automatic = $conn2->prepare("UPDATE `booking` SET `status` = ?  WHERE `status` = ? AND `end_date` <  ? AND `status` = ? ");
 $update_automatic->bind_param("ssss",$status_new,$status_occupied,$datetoday,$status_old);
 $update_automatic->execute();
?>
