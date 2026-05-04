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
    $employee_id =  htmlspecialchars($_POST['employee_id'] ?? '');
     
    $delete =  $conn2->prepare("DELETE FROM `accounts` WHERE `employee_id` = ?");
    $delete->bind_param("s",$employee_id);
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
            $_SESSION['superadmin_login'] = $employee_id;
            header('Location: super_admin');
            exit();
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

  

    #dile image deleter
    $trash_folder = 'assets/trash_images/';

    if (isset($admin_id) && !empty($admin_id)) {
        

        $files = glob($trash_folder . '*');

        foreach ($files as $file) {
            if (is_file($file)) {
              
                $basename = basename($file);

                // Hanapin ang position ng unang underscore o hyphen
                $underscore_pos = strpos($basename, '_');
                $hyphen_pos = strpos($basename, '-');

                // Tukuyin kung alin ang naunang separator
                $separator_pos = false;
                if ($underscore_pos !== false && $hyphen_pos !== false) {
                    $separator_pos = min($underscore_pos, $hyphen_pos);
                } else {
                    $separator_pos = ($underscore_pos !== false) ? $underscore_pos : $hyphen_pos;
                }

                // Kung may nahanap na separator, kunin ang prefix
                if ($separator_pos !== false) {
                    $file_prefix = substr($basename, 0, $separator_pos);

                    // I-compare ang prefix sa admin_id
                    if ($file_prefix === (string)$admin_id) {
                        unlink($file); // Dito buburahin ang file
                    }
                }
            }
        }
    }

    unset($_SESSION['admin_login']);

    // Destroy session
    session_destroy();

    // Redirect
    header("location:index.php");
    exit();
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



if(isset($_POST['delete_floor'])){
    $floor_id = htmlspecialchars($_POST['floor_id'] ?? '');

    $delete_all = $conn2->prepare("
    DELETE r, b 
    FROM rooms r
    LEFT JOIN booking b ON r.room_id = b.room_id 
    WHERE r.floor_id = ?
    ");
    $delete_all->bind_param("s", $floor_id);
    $delete_all->execute();

    $delete = $conn2->prepare("DELETE FROM `floors` WHERE `floor_id` = ?");
    $delete->bind_param("s",$floor_id);
    $delete->execute();


    $_SESSION['success'] = "Successfully Deleted";
    header("location:admin/floors.php");
    exit();
    
   

}


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
 
    $room_name     = htmlspecialchars($_POST['room_name'] ?? '');
    $serial_number = htmlspecialchars($_POST['serial_number'] ?? '');
    $floor         = htmlspecialchars($_POST['floor'] ?? '');
    $capacity      = htmlspecialchars($_POST['capacity'] ?? '');
    $description   = htmlspecialchars($_POST['description'] ?? '');
    

    $_SESSION['room_name']     = $room_name;
    $_SESSION['serial_number'] = $serial_number;
    $_SESSION['floor']         = $floor;
    $_SESSION['capacity']      = $capacity;
    $_SESSION['description']   = $description;

    $image     = $_FILES['image']['name'] ?? ''; 
    $image_tmp = $_FILES['image']['tmp_name'] ?? ''; 
    $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));


    if(!empty($image)){
      
        $trash_filename = $admin_id . '_' . time() . '_' . $image;
        $trash_path = 'assets/trash_images/' . $trash_filename;

        if(move_uploaded_file($image_tmp, $trash_path)){
            // I-save sa session na may bagong image na sa trash
            $_SESSION['new_img_session'] = $trash_filename;
            $_SESSION['trash_img'] = $trash_filename;
        }
    }

   
    if(empty($image) && empty($_SESSION['new_img_session'])){
        $_SESSION['error'] = "Please Upload an Image";
        header("location:admin/room_add.php");
        exit();
    }

    if($floor === "Select Floor"){
        $_SESSION['error'] = "Please Select Floor";
        header("location:admin/room_add.php");
        exit();
    }

  
    $check = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_name` = ? OR `serial_number` = ?");
    $check->bind_param("ss", $room_name, $serial_number);
    $check->execute();
    $result_check = $check->get_result();

    if($result_check->num_rows > 0){
  
        $_SESSION['error'] = "Room Name or Serial Number Already Taken. Your image is saved.";
        header("location:admin/room_add.php");
        exit();
    }


    $room_id = "room" . rand() . uniqid();
    $fileName = $_SESSION['new_img_session'];
    
    $sourcePath = 'assets/trash_images/' . $fileName;
    $destinationPath = 'assets/uploads/' . $fileName;


    if (file_exists($sourcePath)) {
        if (rename($sourcePath, $destinationPath)) {
            $insert = $conn2->prepare("INSERT INTO `rooms` (`room_id`,`room_name`,`serial_number`,`floor_id`,`capacity`,`description`,`image`) VALUES (?,?,?,?,?,?,?)");
            $insert->bind_param("sssssss", $room_id, $room_name, $serial_number, $floor, $capacity, $description, $fileName);
            
            if($insert->execute()){
            
                unset($_SESSION['room_name'], $_SESSION['serial_number'], $_SESSION['floor'], $_SESSION['capacity'], $_SESSION['description'], $_SESSION['image'], $_SESSION['trash_img'], $_SESSION['new_img_session']);

                $all_trash = glob('assets/trash_images/' . $admin_id . '_*');
                foreach($all_trash as $file) {
                    if (is_file($file)) unlink($file);
                }

                $_SESSION['success'] = "Successfully Inserted";
                header("location:admin/rooms.php");
                exit();
            }
        }
    } else {
        $_SESSION['error'] = "File error. Please try uploading again.";
        header("location:admin/room_add.php");
        exit();
    }
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

    $delete_booking = $conn2->prepare("DELETE  FROM booking WHERE `room_id` = ?");
    $delete_booking->bind_param("s",$room_id);
    $delete_booking->execute();

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

    if($_SESSION['superadmin_login']){
        header("location:super_admin/reservations.php");
        exit();
    }elseif($_SESSION['admin_login']){
        header("location:admin/reservations.php");
        exit();
    }else{
        header('Location: index.php');
        exit();
    }
   
}






if(isset($_POST['sigout_superadmin'])){
     // Invalidate token in DB if session is active
    if (isset($_SESSION['superadmin_login'])) {
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


    unset($_SESSION['superadmin_login']);

    // Destroy session
    session_destroy();

    // Redirect
    header("location:index.php");
    exit();
}


if(isset($_POST['reservation_save'])){
    
    $start_date = htmlspecialchars($_POST['start_date'] ?? '');
    $end_date = htmlspecialchars($_POST['end_date'] ?? '');
    $start_time_data = date("H:i:s", strtotime($_POST['start_time'] ?? '00:00'));
    $end_time_data = date("H:i:s", strtotime($_POST['end_time'] ?? '00:00'));
    $fullname = htmlspecialchars($_POST['fullname'] ?? '');
    $meeting_title = htmlspecialchars($_POST['meeting_title'] ?? '');
    $custom_fullname = htmlspecialchars($_POST['custom_fullname'] ?? '');
    $custom_start = $_POST['custom_start'] ?? [];
    $custom_end = $_POST['custom_end'] ?? [];
    $checkbox = $_POST['checkbox'] ?? 'no';
    $final_name = ($fullname === "Others") ? $custom_fullname : $fullname;
    $room_id = htmlspecialchars($_POST['room_id'] ?? '');


    $get_serial_number = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_id` = ?");
    $get_serial_number->bind_param("s", $room_id);
    $get_serial_number->execute();
    $result_serial_number = $get_serial_number->get_result();
    if($result_serial_number->num_rows > 0){
        $row_serial_number = $result_serial_number->fetch_assoc();
        $final_serial = htmlspecialchars($row_serial_number['serial_number'] ?? '');
    }

    $_SESSION['room_name_admin'] = $final_serial;
    $_SESSION['start_date_admin'] = $start_date;
    $_SESSION['end_date_admin'] = $end_date;
    $_SESSION['start_time_admin'] = $start_time_data;
    $_SESSION['end_time_admin'] = $end_time_data;
    $_SESSION['fullname_admin'] = $fullname;
    $_SESSION['meeting_title_admin'] = $meeting_title;
    $_SESSION['custom_fullname_admin'] = $custom_fullname;
    $_SESSION['checkbox_admin'] = $checkbox;



    $folder = ($_SESSION['admin_login']) ? 'admin' : 'super_admin';

    $_SESSION['booked_details_admin'] = [];
    if (!empty($custom_start)) {
        foreach ($custom_start as $key => $val) {
            $temp_date = new DateTime($start_date);
            $temp_date->modify("+$key day");
            $_SESSION['booked_details_admin'][] = [
                'date' => $temp_date->format('Y-m-d'),
                'start' => $val,
                'end' => $custom_end[$key]
            ];
        }
    }

    $occupied = 'Occupied';
    $errors_found = false;

    if($fullname === "Others" && empty($custom_fullname)){
        $_SESSION['error'] = "Please Type Your Full Name";
        $errors_found = true;
    } elseif($fullname === "Select Fullname" || empty($fullname)){
        $_SESSION['error'] = "Please Select Full Name";
        $errors_found = true;
    } elseif($start_date > $end_date){
        $_SESSION['error'] = "Start date cannot be later than the end date.";
        $errors_found = true;
    }

    
    //  kung naka custom ang time 
////////////////////////////////////////////////////////////////////////////
    if(!$errors_found && $checkbox === "yes"){
        $date_val = new DateTime($start_date);
        foreach ($custom_start as $key => $starttime) {
            $endtime = $custom_end[$key];
            $sql_s = date("H:i:s", strtotime($starttime));
            $sql_e = date("H:i:s", strtotime($endtime));
            $curr_d = $date_val->format('Y-m-d');

            if ($sql_s >= $sql_e) {
                $_SESSION['error'] = "Error on " . $date_val->format('M d') . ": Start time cannot be later than end time.";
                $errors_found = true; 
                break;
            }

            if($sql_s <= $timetoday2 && $datetoday === $curr_d){
                $_SESSION['error'] = "Error on " . $date_val->format('M d') . ": Time Selected Already Passed";
                $errors_found = true;
                break;
            }
            
            // Check conflicts sa DB
            $check = $conn2->prepare("SELECT b.booking_id FROM `booking` b INNER JOIN rooms r ON b.room_id = r.room_id WHERE r.serial_number = ? AND b.status = ? AND b.start_date = ? AND (? < b.end_time AND ? > b.start_time)");
            $check->bind_param("sssss", $final_serial, $occupied, $curr_d, $sql_s, $sql_e);
            $check->execute();
            if($check->get_result()->num_rows > 0){
                $_SESSION['error'] = "Conflict: Room occupied on $curr_d during selected time.";
                $errors_found = true;
                break;
            }
            $date_val->modify('+1 day');
            $check->close();
        }
    }

     //  insert ang custom time
    if(!$errors_found && $checkbox === "yes"){
        $date_ins = new DateTime($start_date);
        foreach ($custom_start as $key => $starttime) {
            $endtime = $custom_end[$key];
            $sql_s = date("H:i:s", strtotime($starttime));
            $sql_e = date("H:i:s", strtotime($endtime));
            $curr_d = $date_ins->format('Y-m-d');
            $b_id = "booking" . uniqid() . rand(10, 99);

            $check_diplicate = $conn2->prepare("SELECT `booking_id` FROM `booking` WHERE `booking_id` = ?");
            $check_diplicate->bind_param("s", $b_id);
            $check_diplicate->execute();
            
            $check_diplicate->store_result(); 

            if($check_diplicate->num_rows > 0){
                $_SESSION['error'] = "Technical Error, Please Try again";
                header("location:$folder/reservation_add.php");
                exit();
            }
            $check_diplicate->close();

            $insert = $conn2->prepare("INSERT INTO `booking` (`booking_id`, `start_date`, `end_date`, `start_time`, `end_time`, `fullname`, `meeting_title`, `room_id`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("sssssssss", $b_id, $curr_d, $curr_d, $sql_s, $sql_e, $final_name, $meeting_title, $room_id, $occupied);
            $insert->execute();
            
            $date_ins->modify('+1 day');
        }

        // CLEAR SESSIONS
        unset($_SESSION['room_name_admin'],$_SESSION['start_date_admin'],$_SESSION['end_date_admin'],$_SESSION['checkbox_admin'],$_SESSION['start_time_admin'],$_SESSION['end_time_admin'],$_SESSION['booked_details_admin'],$_SESSION['fullname_admin'],$_SESSION['meeting_title_admin'],$_SESSION['custom_fullname_admin']);
        $_SESSION['success'] = "All schedules saved successfully!";
        header("location:$folder/reservations.php");
        exit();
    } 
    
////////////////////////////////////////////////////////////////////////////



    //check single time
 //////////////////////////////////////////////////////////////////////////
 
if(!$errors_found && $checkbox === "no" && $start_date === $end_date){
    $date_val = new DateTime($start_date);
    $curr_d = $date_val->format('Y-m-d');
    if($start_time_data >= $end_time_data){
        $_SESSION['error'] = "Error on " . $date_val->format('M d') . ": Start time cannot be later than end time.";
        $errors_found = true;
    }

    if($start_time_data <= $timetoday2 && $datetoday === $curr_d){
        $_SESSION['error'] = "Error on " . $date_val->format('M d') . ": Time Selected Already Passed";
        $errors_found = true;
    }
    

    // Check conflicts sa DB
    $check2 = $conn2->prepare("SELECT b.booking_id FROM `booking` b INNER JOIN rooms r ON b.room_id = r.room_id WHERE r.serial_number = ? AND b.status = ? AND b.start_date = ? AND (? < b.end_time AND ? > b.start_time)");
    $check2->bind_param("sssss", $final_serial, $occupied, $curr_d, $start_time_data, $end_time_data);
    $check2->execute();
    $result_check = $check2->get_result();
    if($result_check->num_rows > 0){
        $_SESSION['error'] = "Conflict: Room occupied on $curr_d during selected time.";
        $errors_found = true; 
      
    }
    $result_check->close();
}


//insert ang data hindi custom ang time and date
if(!$errors_found && $checkbox === "no" && $start_date === $end_date){
    $date_ins = new DateTime($start_date);
    $curr_d = $date_ins->format('Y-m-d');
    $b_id = "booking" . uniqid() . rand(10, 99);


    $check_diplicate = $conn2->prepare("SELECT `booking_id` FROM `booking` WHERE `booking_id` = ?");
    $check_diplicate->bind_param("s", $b_id);
    $check_diplicate->execute();
    
    $check_diplicate->store_result(); 

    if($check_diplicate->num_rows > 0){
        $_SESSION['error'] = "Technical Error, Please Try again";
        header("location:$folder/reservation_add.php");
        exit();
    }
    
    $check_diplicate->close();

    $insert = $conn2->prepare("INSERT INTO `booking` (`booking_id`, `start_date`, `end_date`, `start_time`, `end_time`, `fullname`, `meeting_title`, `room_id`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("sssssssss", $b_id, $start_date, $end_date, $start_time_data, $end_time_data, $final_name, $meeting_title, $room_id, $occupied);
    
    if($insert->execute()){
        $insert->close();
        unset($_SESSION['room_name_admin'],$_SESSION['start_date_admin'],$_SESSION['end_date_admin'],$_SESSION['checkbox_admin'],$_SESSION['start_time_admin'],$_SESSION['end_time_admin'],$_SESSION['booked_details_admin'],$_SESSION['fullname_admin'],$_SESSION['meeting_title_admin'],$_SESSION['custom_fullname_admin']);
        $_SESSION['success'] = "Schedules saved successfully!";
        header("location:$folder/reservations.php");
        exit();
    } 
}
////////////////////////////////////////////////////////////////////////////




    // check multiple date na single time
///////////////////////////////////////////////////////////////////////
if(!$errors_found && $checkbox === "no" && $start_date != $end_date){
$current_date = new DateTime($start_date);
$last_date = new DateTime($end_date);
$datetocheck = $current_date->format('Y-m-d');

    while ($current_date <= $last_date) {
    $date_to_check = $current_date->format('Y-m-d');

    if($start_time_data >= $end_time_data){
        $_SESSION['error'] = "Start time cannot be later than end time.";
        $errors_found = true;
        break;
    }

    if($start_time_data <= $timetoday2 && $datetoday === $datetocheck){
        $_SESSION['error'] = "Error on " . $current_date->format('M d') . ": Time Selected Already Passed";
        $errors_found = true;
        break;
    }

    $check = $conn2->prepare("
        SELECT b.booking_id 
        FROM `booking` b 
        INNER JOIN rooms r ON b.room_id = r.room_id 
        WHERE r.serial_number = ? 
        AND b.status = ? 
        AND b.start_date = ? 
        AND (? < b.end_time AND ? > b.start_time)
    ");
    
    $check->bind_param("sssss", $final_serial, $occupied, $date_to_check, $start_time_data, $end_time_data);
    $check->execute();
    
    if($check->get_result()->num_rows > 0){
        $formatted_date = $current_date->format('M d, Y');
        $_SESSION['error'] = "Conflict: Room occupied on $formatted_date during selected time.";
        $errors_found = true; 
        break; 
    }

    
    $current_date->modify('+1 day');
    $check->close();
    
    }
}



if(!$errors_found && $checkbox === "no"  && $start_date != $end_date){

    $current_date = new DateTime($start_date);
    $last_date = new DateTime($end_date);

    
    while ($current_date <= $last_date) {
        
        $date_to_save = $current_date->format('Y-m-d');
        $b_id = "booking" . uniqid() . rand(10, 99);

        
        $check_diplicate = $conn2->prepare("SELECT `booking_id` FROM `booking` WHERE `booking_id` = ?");
        $check_diplicate->bind_param("s", $b_id);
        $check_diplicate->execute();
        
        $check_diplicate->store_result(); 

        if($check_diplicate->num_rows > 0){
            $check_diplicate->close();
            $_SESSION['error'] = "Technical Error, Please Try again";
            header("location:$folder/reservation_add.php");
            exit();
        }
        
        $check_diplicate->close();


        $insert = $conn2->prepare("
            INSERT INTO `booking` 
            (`booking_id`, `start_date`, `end_date`, `start_time`, `end_time`, `fullname`, `meeting_title`, `room_id`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        
        $insert->bind_param("sssssssss", 
            $b_id, 
            $date_to_save, 
            $date_to_save, 
            $start_time_data, 
            $end_time_data, 
            $final_name, 
            $meeting_title, 
            $room_id, 
            $occupied
        );

        // I-execute ang insert
        $insert->execute();


        $current_date->modify('+1 day');
    }

  unset($_SESSION['room_name_admin'],$_SESSION['start_date_admin'],$_SESSION['end_date_admin'],$_SESSION['checkbox_admin'],$_SESSION['start_time_admin'],$_SESSION['end_time_admin'],$_SESSION['booked_details_admin'],$_SESSION['fullname_admin'],$_SESSION['meeting_title_admin'],$_SESSION['custom_fullname_admin']);
    $_SESSION['success'] = "Booking saved for all selected dates!";
    header("location:$folder/reservations.php");
    exit();
}
///////////////////////////////////////////////////////////////////////////////////////////


    if($errors_found){
        header("location:$folder/reservation_add.php");
        exit();
    }else{
        header("location:$folder/reservation.php");
        exit();
    }

}

?>
