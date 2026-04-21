<?php
    include 'config.php';



if (isset($_POST['assign_user'])) {

    $employee_id = $_POST['employee_id'] ?? '';

    # check kun naka save na
    $get_data2 = $conn2->prepare("SELECT * FROM `accounts` WHERE `employee_id` = ?");
    $get_data2->bind_param("i", $employee_id);
    $get_data2->execute();
    $result_data2 = $get_data2->get_result();

    if ($result_data2->num_rows > 0) {

        $_SESSION['error'] = "Employee Already assigned";
        header("location:super_admin/account_add.php");
        exit();

    } else {
        $role = "2";
        # mag laog na sa database
        $id = "assigned" . rand() . uniqid();
        $insert = $conn2->prepare("INSERT INTO `accounts` (`employee_id`,`role`,`id`) VALUES (?,?,?)");
        $insert->bind_param("iss", $employee_id,$role,$id);
        $insert->execute();

        $_SESSION['success'] = "Successfully Assigned";
        header("location:super_admin/accounts.php");
        exit();
    }
}


if(isset($_POST['delete_account'])){
    $id =  htmlspecialchars($_POST['id'] ?? '');
     
    $delete =  $conn2->prepare("DELETE FROM `accounts` WHERE `id` = ?");
    $delete->bind_param("s",$id);
    $delete->execute();

    $_SESSION['success'] = "Successfully Deleted";
    header("location:super_admin/accounts.php");
    exit();

}


if(isset($_POST['login'])){
    $employee_id = htmlspecialchars($_POST['employee_id'] ?? '');
    $password = htmlspecialchars($_POST['password'] ?? '');


    #check kun tama an employee id and password
    $check = $conn1->prepare("SELECT * FROM employeesystemcredential WHERE Employee_ID = ? AND Password = ?");
    $sha1_password = sha1($password);
    $check->bind_param("is", $employee_id, $sha1_password);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
    
        #kuhaon an role
        $get_role = $conn2->prepare("SELECT * FROM `accounts` WHERE `employee_id` = ?");
        $get_role->bind_param("s",$employee_id);
        $get_role->execute();
        $result_get=$get_role->get_result();
       if($result_get->num_rows > 0){
            $row_role = $result_get->fetch_assoc();
            $role = htmlspecialchars($row_role['role'] ?? '');
        }

       
        $remember_token = bin2hex(random_bytes(32)); // 64-character secure token

        $update_token = $conn2->prepare("UPDATE accounts SET remember_token = ? WHERE employee_id = ?");
        $update_token->bind_param("ss", $remember_token, $employee_id);
        $update_token->execute();

        setcookie("remember_token", $remember_token, time() + (7 * 24 * 60 * 60), "/", "", false, true); // httponly

        #role destination
        if($role === "1"){

        }elseif($role === "2"){
            $_SESSION['admin_login'] = $employee_id;
            header('Location: admin');
            exit();
        }else{
            $_SESSION['error'] = "Invalid Role";
            header('Location: index.php');
            exit();
        }

    } else {
        $_SESSION['error'] = "Invalid Employee ID or Password";
        header('Location: index.php');
        exit();
    }
}


if(isset($_POST['sigout_admin'])){
    
    // Invalidate token in DB if session is active
    if (isset($_SESSION['admin_login'])) {
        $stmt = $conn2->prepare("UPDATE accounts SET remember_token = NULL WHERE employee_id = ?");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
    }

    // Clear session
    $_SESSION = array();

    // Clear session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Clear remember_token cookie
    setcookie("remember_token", "", time() - 3600, "/", "", false, true); // HttpOnly


    unset($_SESSION['admin_login']);

    // Destroy session
    session_destroy();

    // Redirect
    header("location:index.php");
    exit;
}



if(isset($_POST['floors_save'])){
    $floor_name = htmlspecialchars($_POST['floor_name'] ?? '');
    $number_of_rooms = htmlspecialchars($_POST['number_of_rooms'] ?? '');
    $floor_id = "floor" . rand() . uniqid();
    $_SESSION['floor_name'] = $floor_name;
    $_SESSION['number_of_rooms'] = $number_of_rooms;

    # Check floor_id
    $check_floor_id = $conn2->prepare("SELECT * FROM floors WHERE floor_id = ?");
    $check_floor_id->bind_param("i", $floor_id);
    $check_floor_id->execute();
    $result = $check_floor_id->get_result();
    if($result->num_rows > 0){
        $_SESSION['error'] = "Technical Issue Please Try Again";
        header("location:admin/floors.php");
        exit();
    }

    # Check if floor_name exists
    $get_same_data = $conn2->prepare("SELECT * FROM floors WHERE floor_name = ?");
    $get_same_data->bind_param("s", $floor_name);
    $get_same_data->execute();
    $data_get = $get_same_data->get_result();
    if($data_get->num_rows > 0){
        $_SESSION['error'] = "$floor_name Already Inserted";
        header("location:admin/floor_add.php");
        exit();
    } else {
        # Insert
        $insert = $conn2->prepare("INSERT INTO floors (floor_id, floor_name, rooms_number) VALUES (?, ?, ?)");
        $insert->bind_param("ssi", $floor_id, $floor_name, $number_of_rooms); // i=integer, s=string
        $insert->execute();

        unset($_SESSION['floor_name']);
        unset($_SESSION['number_of_rooms']);

        $_SESSION['success'] = "Successfully Inserted";
        header("location:admin/floors.php");
        exit();
    }
}


/*
if(isset($_POST['delete_floor'])){
    $floor_id = htmlspecialchars($_POST['floor_id'] ?? '');


    $delete = $conn2->prepare("DELETE FROM `floors` WHERE `floor_id` = ?");
    $delete->bind_param("i",$floor_id);
    $delete->execute();
    
    $delete_room = $conn2->prepare("DELETE FROM `floors` WHERE `floor_id` = ?");
    $delete->bind_param("i",$floor_id);
    $delete->execute();

}*/


if(isset($_POST['floors_update'])){
    $floor_id = htmlspecialchars($_POST['floor_id'] ?? '');
    $floor_name = htmlspecialchars($_POST['floor_name'] ?? '');
    $number_of_rooms = htmlspecialchars($_POST['number_of_rooms'] ?? '');

    $check = $conn2->prepare("SELECT * FROM `floors` WHERE `floor_name` = ? AND `floor_id` != ?");
    $check->bind_param("si",$floor_name,$floor_id);
    $check->execute();
    $result_check = $check->get_result();
    if($result_check->num_rows>0){
        $_SESSION['error'] = "Floor Name Already Taken";
        header("location:admin/floor_update.php?floor_id=$floor_id");
        exit();
    }else{
        $update = $conn2->prepare("UPDATE `floors` SET `floor_name` = ?, `rooms_number` = ? WHERE `floor_id` = ?");
        $update->bind_param("ssi",$floor_name,$number_of_rooms,$floor_id);
        $update->execute();
      
        $_SESSION['success'] = "Successfully Updated";
        header("location:admin/floors.php");
        exit();
    }
}

if(isset($_POST['room_save'])){
    $room_name = htmlspecialchars($_POST['room_name'] ?? '');
    $serial_number = htmlspecialchars($_POST['serial_number'] ?? '');
    $floor = htmlspecialchars($_POST['floor'] ?? '');
    $capacity = htmlspecialchars($_POST['capacity'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');

    $room_id = "room" . rand() . uniqid();

    $_SESSION['room_name'] = $room_name;
    $_SESSION['serial_number'] = $serial_number;
    $_SESSION['floor'] = $floor;
    $_SESSION['capacity'] = $capacity;
    $_SESSION['description'] = $description;

    if($floor === "Select Floor"){
        $_SESSION['error'] = "Please Select Floor";
        header("location:admin/room_add.php");
        exit();
    }
    

    $check = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_name` = ?");
    $check->bind_param("s",$room_name);
    $check->execute();
    $result_check = $check->get_result();
    if($result_check->num_rows>0){
        $_SESSION['error'] = "Room Name Already Taken";
        header("location:admin/room_add.php");
        exit();
    }

    $check1 = $conn2->prepare("SELECT * FROM `rooms` WHERE `serial_number` = ?");
    $check1->bind_param("s",$serial_number);
    $check1->execute();
    $result_check1 = $check1->get_result();
    if($result_check1->num_rows>0){
        $_SESSION['error'] = "Serial Number Already Taken";
        header("location:admin/room_add.php");
        exit();
    }

    $check2 = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_id` = ?");
    $check2->bind_param("s",$room_id);
    $check2->execute();
    $result_check2 = $check2->get_result();
    if($result_check2->num_rows>0){
        $_SESSION['error'] = "Technical Error Please Try Again";
        header("location:admin/room_add.php");
        exit();
    }

    $insert = $conn2->prepare("INSERT INTO `rooms` (`room_id`,`room_name`,`serial_number`,`floor_id`,`capacity`,`description`) VALUES (?,?,?,?,?,?)");
    $insert->bind_param("ssssss",$room_id,$room_name,$serial_number,$floor,$capacity,$description);
    $insert->execute();

    unset($_SESSION['room_name']);
    unset($_SESSION['serial_number']);
    unset($_SESSION['floor']);
    unset($_SESSION['capacity']);
    unset($_SESSION['description']);

    $_SESSION['success'] = "Successfully Inserted";
    header("location:admin/rooms.php");
    exit();
}

if(isset($_POST['room_update'])){
    $room_id = htmlspecialchars($_POST['room_id'] ?? '');
    $room_name = htmlspecialchars($_POST['room_name'] ?? '');
    $serial_number = htmlspecialchars($_POST['serial_number'] ?? '');
    $floor = htmlspecialchars($_POST['floor'] ?? '');
    $capacity = htmlspecialchars($_POST['capacity'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    
    $check = $conn2->prepare("SELECT * FROM `rooms` WHERE `serial_number` = ? AND `room_id` != ?  ");
    $check->bind_param("ss",$serial_number,$room_id);
    $check->execute();
    $result_check = $check->get_result();
    if($result_check->num_rows>0){
        $_SESSION['error'] = "Serial Number Already Taken";
        header("location:admin/room_update.php?room_id=$room_id");
        exit();
    }

    $check1 = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_name` = ? AND `room_id` != ?  ");
    $check1->bind_param("ss",$room_name,$room_id);
    $check1->execute();
    $result_check1 = $check1->get_result();
    if($result_check1->num_rows>0){
        $_SESSION['error'] = "Room Name Already Taken";
        header("location:admin/room_update.php?room_id=$room_id");
        exit();
    }

    $update = $conn2->prepare("UPDATE `rooms` SET `room_name` = ?,`serial_number` = ?,`floor_id`=?,`capacity` =?,`description`=? WHERE `room_id` =?");
    $update->bind_param("ssssss",$room_name,$serial_number,$floor,$capacity,$description,$room_id);
    $update->execute();

    $_SESSION['success'] = "Successfully Updated";
    header("location:admin/rooms.php");
    exit();

}

if(isset($_POST['delete_room'])){
    $room_id = htmlspecialchars($_POST['room_id'] ?? '');

    $delete = $conn2->prepare("DELETE FROM `rooms` WHERE `room_id` = ?");
    $delete->bind_param("s",$room_id);
    $delete->execute();

    $_SESSION['success'] = "Successfully Deleted";
    header("location:admin/rooms.php");
    exit();
}



if(isset($_POST['cancel_booking'])){
    $booking_id = htmlspecialchars($_POST['booking_id'] ?? '');
    $cancelled_at = htmlspecialchars($_POST['cancelled_at'] ?? '');
    $status_cancelled = "Cancelled";
    $update = $conn2->prepare("UPDATE `booking` SET `status` = ?, `cancelled_at` = ? WHERE `booking_id` = ?");
    $update->bind_param("sss",$status_cancelled,$cancelled_at,$booking_id);
    $update->execute();

    $_SESSION['success'] = "Successfully Cancelled";
    header("location:admin/reservations.php");
    exit();
}




if(isset($_POST['reservation_save'])){
    $start_date = htmlspecialchars($_POST['start_date'] ?? '');
    $end_date = htmlspecialchars($_POST['end_date'] ?? '');
    $start_time = htmlspecialchars($_POST['start_time'] ?? '');
    $end_time = htmlspecialchars($_POST['end_time'] ?? '');
    $fullname = htmlspecialchars($_POST['fullname'] ?? '');
    $meeting_title = htmlspecialchars($_POST['meeting_title'] ?? '');
    $custom_fullname = htmlspecialchars($_POST['custom_fullname'] ?? '');
    $room_id = htmlspecialchars($_POST['room_id'] ?? '');


    $get_serial_number = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_id` = ?");
    $get_serial_number->bind_param("s", $room_id);
    $get_serial_number->execute();
    $result_serial_number = $get_serial_number->get_result();
    if($result_serial_number->num_rows > 0){
        $row_serial_number = $result_serial_number->fetch_assoc();
        $final_serial = htmlspecialchars($row_serial_number['serial_number'] ?? '');
    }

    $occupied = 'Occupied';

    $_SESSION['start_date_admin'] = $start_date;
    $_SESSION['end_date_admin'] = $end_date;
    $_SESSION['start_time_admin'] = $start_time;
    $_SESSION['end_time_admin'] = $end_time;
    $_SESSION['start_date_admin'] = $start_date;
    $_SESSION['fullname_admin'] = $fullname;
    $_SESSION['meeting_title_admin'] = $meeting_title;
    $_SESSION['custom_fullname_admin'] = $custom_fullname;
    $_SESSION['room_name_admin'] = $final_serial;

    $booking_id =  "booking" . uniqid() . rand();
        
    $check_duplicate = $conn2->prepare("SELECT * FROM booking WHERE `booking_id` = ?");
    $check_duplicate->bind_param("s",$booking_id);
    $check_duplicate->execute();
    $result_check = $check_duplicate->get_result();
    if($result_check->num_rows>0){
        $_SESSION['error'] = "Technical Error Please Try Again";
        header("location:reservation_add.php");
        exit();
    }


    if($start_date > $end_date){
        $_SESSION['error'] = "Start date cannot be later than the end date.";
        header("location:admin/reservation_add.php");
        exit();
    }elseif($start_date === $datetoday && $end_date >= $datetoday && $start_time < $timetoday2 && $end_time < $timetoday2){
        $_SESSION['error'] = "The selected time has already passed.";
        header("location:admin/reservation_add.php");
        exit();
    }elseif($start_date === $end_date && $start_time >= $end_time){
        $_SESSION['error'] = "Start Time cannot be later than the end date or equal.";
        header("location:admin/reservation_add.php");
        exit();
    }elseif($start_time === "Select" || $end_time === "Select" ){
        $_SESSION['error'] = "Please Select Start Time or End Time";
        header("location:admin/reservation_add.php");
        exit();
    }elseif($fullname === "Others" && empty($custom_fullname)){
        $_SESSION['error'] = "Please Type Your Full Name";
        header("location:admin/reservation_add.php");
        exit();
    }elseif($fullname === "Select"){
        $_SESSION['error'] = "Please Select Full Name";
        header("location:admin/reservation_add.php");
        exit();
    }else{
    $new_booking_start = $start_date . ' ' . date("H:i", strtotime($start_time));
    $new_booking_end   = $end_date . ' ' . date("H:i", strtotime($end_time));

$check_query = $conn2->prepare("
    SELECT booking.booking_id 
    FROM `booking` 
    INNER JOIN rooms ON booking.room_id = rooms.room_id 
    WHERE rooms.serial_number = ? 
    AND booking.status = ? 
    AND (
        -- Standard Overlap Formula: (StartA < EndB) AND (EndA > StartB)
        STR_TO_DATE(CONCAT(booking.start_date, ' ', booking.start_time), '%Y-%m-%d %h:%i %p') < STR_TO_DATE(?, '%Y-%m-%d %H:%i')
        AND 
        STR_TO_DATE(CONCAT(booking.end_date, ' ', booking.end_time), '%Y-%m-%d %h:%i %p') > STR_TO_DATE(?, '%Y-%m-%d %H:%i')
    )
");


$check_query->bind_param("ssss", $final_serial, $occupied, $new_booking_end, $new_booking_start);
$check_query->execute();
$result = $check_query->get_result();

if($result->num_rows > 0) {
    // CONFLICT FOUND
    $_SESSION['error'] = "Conflict Detected: Room is already occupied for this schedule.";
    header("location:admin/reservation_add.php");
    exit();
} else {

    $final_name = ($fullname === "Others") ? $custom_fullname : $fullname;
    
    $insert = $conn2->prepare("INSERT INTO `booking` (booking_id, start_date, end_date, start_time, end_time, fullname, meeting_title, room_id, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("sssssssss", $booking_id, $start_date, $end_date, $start_time, $end_time, $final_name, $meeting_title, $room_id, $occupied);
    
    if($insert->execute()) {
        
        unset($_SESSION['start_date_admin']);
        unset($_SESSION['end_date_admin']);
        unset($_SESSION['start_time_admin']);
        unset($_SESSION['end_time_admin']);
        unset($_SESSION['start_date_admin']);
        unset($_SESSION['end_date_admin']); 
        unset($_SESSION['fullname_admin']);
        unset($_SESSION['meeting_title_admin']); 
        unset($_SESSION['custom_fullname_admin']);
        unset($_SESSION['room_name_admin']);
        $_SESSION['success'] = "Booking Successful!";
        header("location:admin/reservations.php");
        exit();
    }
}
    header("location:admin/reservations.php");
    exit();
}
        

}

?>
