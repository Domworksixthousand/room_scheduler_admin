<?php
include 'config.php';

if(isset($_POST['update_shutdown'])){
    $room_id =  $_POST['room_id'];
    $tablet_status = "Inactive";
    $update = $conn2->prepare("UPDATE `rooms` SET `tablet_status` = ? WHERE `room_id` = ?");
    $update->bind_param("ss", $tablet_status, $room_id);
    $update->execute();


    $_SESSION['success'] = "Successfully Shutdown";
    header("location:$back");
    exit();
}

if(isset($_POST['update_shutdown_superadmin'])){
    $room_id =  $_POST['room_id'];
    $tablet_status = "Inactive";
    $update = $conn2->prepare("UPDATE `rooms` SET `tablet_status` = ? WHERE `room_id` = ?");
    $update->bind_param("ss", $tablet_status, $room_id);
    $update->execute();

    $_SESSION['success'] = "Successfully Shutdown";
    header("location:super_admin/rooms.php");
    exit();
}



//assign user
if (isset($_POST['assign_user'])) {

    $employee_id = $_POST['employee_id'] ?? '';
    $user_status = "Active";

    

    # check kun naka save na
    $get_data2 = $conn2->prepare("SELECT * FROM `accounts` WHERE `employee_id` = ? AND `user_status` = ?");
    $get_data2->bind_param("ss", $employee_id, $user_status);
    $get_data2->execute();
    $result_data2 = $get_data2->get_result();

    if ($result_data2->num_rows > 0) {

        $_SESSION['error'] = "Employee Already assigned";
        header("location:super_admin/account_add.php");
        exit();

    }


    
    # check kun naka save na
    $get_data2 = $conn2->prepare("SELECT * FROM `accounts` WHERE `employee_id` = ? AND `user_status` != ?");
    $get_data2->bind_param("ss", $employee_id, $user_status);
    $get_data2->execute();
    $result_data2 = $get_data2->get_result();

    if ($result_data2->num_rows > 0) {

        $update = $conn2->prepare("UPDATE `accounts` SET `user_status` = ?,`time_date_added` = ?,`user_id_added` = ? WHERE `employee_id` = ?");
        $update->bind_param("ssss", $user_status, $currentDateTime, $_SESSION['user_id'], $employee_id);
        $update->execute();

        $_SESSION['success'] = "Successfully Assigned";
        header("location:super_admin/accounts.php");
        exit();

    }   else {
        $role = "2";
        # mag laog na sa database
        $id = "assigned" . rand() . uniqid();
        $insert = $conn2->prepare("INSERT INTO `accounts` (`employee_id`,`role`,`id`,`user_status`,`time_date_added`,`user_id_added`) VALUES (?,?,?,?,?,?)");
        $insert->bind_param("ssssss", $employee_id,$role,$id,$user_status,$currentDateTime,$_SESSION['user_id']);
        $insert->execute();

            $_SESSION['success'] = "Successfully Assigned";
        header("location:super_admin/accounts.php");
        exit();
    }
    
 
}


if(isset($_POST['delete_account'])){
    $employee_id =  htmlspecialchars($_POST['employee_id'] ?? '');
    $Inactive = "Inactive";
    $update =  $conn2->prepare("UPDATE `accounts` SET `user_status` = ?,`time_date_deleted` = ? WHERE `employee_id` = ?");
    $update->bind_param("sss",$Inactive,$currentDateTime,$employee_id);
    $update->execute();

    $_SESSION['success'] = "Successfully Deleted";
    header("location:super_admin/accounts.php");
    exit();
}





if(isset($_POST['floors_save'])){
    $floor_name = mysqli_real_escape_string($conn2,$_POST['floor_name'] ?? '');
    $number_of_rooms = mysqli_real_escape_string($conn2,$_POST['number_of_rooms'] ?? '');
    $floor_id = "floor" . rand() . uniqid();
    $_SESSION['floor_name'] = $floor_name;
    $_SESSION['number_of_rooms'] = $number_of_rooms;
    $floor_status = "Active";
    
    if($role === "1"){
        $folder = "super_admin";
    }elseif($role === "2"){
        $folder = "admin";
    }

   
    # Check floor_id
    $check_floor_id = $conn2->prepare("SELECT * FROM floors WHERE floor_id = ?");
    $check_floor_id->bind_param("s", $floor_id);
    $check_floor_id->execute();
    $result = $check_floor_id->get_result();
    if($result->num_rows > 0){
        $_SESSION['error'] = "Technical Issue Please Try Again";
        header("location:$folder/floors.php");
        exit();
    }

    # Check if floor_name exists
    $get_same_data = $conn2->prepare("SELECT * FROM floors WHERE floor_name = ? AND floor_status = ?");
    $get_same_data->bind_param("ss", $floor_name, $floor_status);
    $get_same_data->execute();
    $data_get = $get_same_data->get_result();
    if($data_get->num_rows > 0){
        $_SESSION['error'] = "$floor_name Already Inserted";
        header("location:$folder/floor_add.php");
        exit();
    } else {
        # Insert
        $insert = $conn2->prepare("INSERT INTO floors (floor_id, floor_name, rooms_number, floor_status,user_id_added,time_date_added) VALUES (?, ?, ?, ?, ?,?)");
        $insert->bind_param("ssssss", $floor_id, $floor_name, $number_of_rooms, $floor_status, $_SESSION['user_id'],$currentDateTime);
        $insert->execute();

        unset($_SESSION['floor_name']);
        unset($_SESSION['number_of_rooms']);

        $_SESSION['success'] = "Successfully Inserted";
        header("location:$folder/floors.php");
        exit();
    }
}



if(isset($_POST['delete_floor'])){
    $floor_id = htmlspecialchars($_POST['floor_id'] ?? '');

        
    if($role === "1"){
        $folder = "super_admin";
    }elseif($role === "2"){
        $folder = "admin";
    }
    $floor_status = "Inactive";
    $update = $conn2->prepare("UPDATE `floors` SET `floor_status` = ?,`time_date_update` = ?,`user_id_update` = ? WHERE `floor_id` = ?");
    $update->bind_param("ssss",$floor_status,$currentDateTime,$_SESSION['user_id'],$floor_id);
    $update->execute();

    /*
    $delete_all = $conn2->prepare(";
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
*/

    $_SESSION['success'] = "Successfully Deleted";
    header("location:$folder/floors.php");
    exit();
    
   

}


if(isset($_POST['floors_update'])){
    $floor_id = mysqli_real_escape_string($conn2,$_POST['floor_id'] ?? '');
    $floor_name = mysqli_real_escape_string($conn2,$_POST['floor_name'] ?? '');
    $number_of_rooms = mysqli_real_escape_string($conn2,$_POST['number_of_rooms'] ?? '');
    if($role === "1"){
        $folder = "super_admin";    
    }elseif($role === "2"){
        $folder = "admin";
    }else{
        header("location:user");
        exit();
    }
  
    $floor_status = "Active";
    $check = $conn2->prepare("SELECT * FROM `floors` WHERE `floor_name` = ? AND `floor_id` != ? AND `floor_status` = ?");
    $check->bind_param("sss",$floor_name,$floor_id,$floor_status);
    $check->execute();
    $result_check = $check->get_result();
    if($result_check->num_rows>0){
        $_SESSION['error'] = "Floor Name Already Taken";
        header("location:$folder/floor_update.php?floor_id=$floor_id");
        exit();
    }else{
        $update = $conn2->prepare("UPDATE `floors` SET `floor_name` = ?, `rooms_number` = ?,`time_date_update`=?,`user_id_update`=? WHERE `floor_id` = ?");
        $update->bind_param("sssss",$floor_name,$number_of_rooms,$currentDateTime,$_SESSION['user_id'],$floor_id);
        $update->execute();
      
        $_SESSION['success'] = "Successfully Updated";
        header("location:$folder/floors.php");
        exit();
    }
}

if(isset($_POST['room_save'])){
 
    $room_name     = mysqli_real_escape_string($conn2,$_POST['room_name'] ?? '');
    $serial_number = mysqli_real_escape_string($conn2,$_POST['serial_number'] ?? '');
    $floor         = mysqli_real_escape_string($conn2,$_POST['floor'] ?? '');
    $capacity      = mysqli_real_escape_string($conn2,$_POST['capacity'] ?? '');
    $description   = mysqli_real_escape_string($conn2,$_POST['description'] ?? '');
    $device_name   = mysqli_real_escape_string($conn2,$_POST['device_name'] ?? '');
    $status = "Active";
      
    if($role === "1"){
        $folder = "super_admin";
    }elseif($role === "2"){
        $folder = "admin";
    }


    $_SESSION['room_name']     = $room_name;
    $_SESSION['serial_number'] = $serial_number;
    $_SESSION['floor']         = $floor;
    $_SESSION['capacity']      = $capacity;
    $_SESSION['description']   = $description;
    $_SESSION['device_name']   = $device_name;

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
        header("location:$folder/room_add.php");
        exit();
    }

    if($floor === "Select Floor"){
        $_SESSION['error'] = "Please Select Floor";
        header("location:$folder/room_add.php");
        exit();
    }

  
    $check = $conn2->prepare("SELECT * FROM `rooms` WHERE (`room_name` = ? OR `serial_number` = ? ) AND `status` = ?");
    $check->bind_param("sss", $room_name,$serial_number,$status);
    $check->execute();
    $result_check = $check->get_result();

    if($result_check->num_rows > 0){
  
        $_SESSION['error'] = "Room Name or Serial Number Already Taken";
        header("location:$folder/room_add.php");
        exit();
    }


    $room_id = "room" . rand() . uniqid();
    $fileName = $_SESSION['new_img_session'];
    
    $sourcePath = 'assets/trash_images/' . $fileName;
    $destinationPath = 'assets/uploads/' . $fileName;


    if (file_exists($sourcePath)) {
        if (rename($sourcePath, $destinationPath)) {
            $tablet_status = "Active";

            $insert = $conn2->prepare("INSERT INTO `rooms` (`room_id`,`room_name`,`serial_number`,`floor_id`,`capacity`,`description`,`image`,`tablet_status`,`user_id_added`,`time_date_added`,`status`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $insert->bind_param("sssssssssss", $room_id, $room_name, $serial_number, $floor, $capacity, $description, $fileName,$tablet_status,$_SESSION['user_id'],$currentDateTime,$status);
            
            if($insert->execute()){
            
                unset($_SESSION['room_name'], $_SESSION['serial_number'], $_SESSION['floor'], $_SESSION['capacity'], $_SESSION['description'], $_SESSION['image'], $_SESSION['trash_img'], $_SESSION['new_img_session'], $_SESSION['device_name']);

                $all_trash = glob('assets/trash_images/' . $admin_id . '_*');
                foreach($all_trash as $file) {
                    if (is_file($file)) unlink($file);
                }

                $_SESSION['success'] = "Successfully Inserted";
                header("location:$folder/rooms.php");
                exit();
            }
        }
    } else {
        $_SESSION['error'] = "File error. Please try uploading again.";
        header("location:$folder/room_add.php");
        exit();
    }
}

if(isset($_POST['room_update'])){
    $room_id = mysqli_real_escape_string($conn2,$_POST['room_id'] ?? '');
    $room_name = mysqli_real_escape_string($conn2,$_POST['room_name'] ?? '');
    $serial_number = mysqli_real_escape_string($conn2,$_POST['serial_number'] ?? '');
    $floor = mysqli_real_escape_string($conn2,$_POST['floor'] ?? '');
    $capacity = mysqli_real_escape_string($conn2,$_POST['capacity'] ?? '');
    $description = mysqli_real_escape_string($conn2,$_POST['description'] ?? '');
    $image     = $_FILES['image']['name'] ?? ''; 
    $image_tmp = $_FILES['image']['tmp_name'] ?? ''; 
    $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $trash_filename = $room_name . '_' . time() . '_' . $image;
    $trash_path = 'assets/uploads/' . $trash_filename;
    $location_back = ($role === "1") ? "super_admin" : "admin";
    $status = 'Active';

    if($extension !== "jpeg" && $extension !== "jpg"  &&  !empty($image)){
        $_SESSION['error'] = "jpeg image type only";
        header("location:$location_back/room_update.php?room_id=$room_id");
        exit();
    }

    $check = $conn2->prepare("SELECT * FROM `rooms` WHERE `serial_number` = ? AND `room_id` != ? AND `status` = ? ");
    $check->bind_param("sss",$serial_number,$room_id,$status);
    $check->execute();
    $result_check = $check->get_result();
    if($result_check->num_rows>0){
        $_SESSION['error'] = "Serial Number Already Taken";
        header("location:$location_back/room_update.php?room_id=$room_id");
        exit();
    }

    $check1 = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_name` = ? AND `room_id` != ? AND `status` = ?  ");
    $check1->bind_param("sss",$room_name,$room_id,$status);
    $check1->execute();
    $result_check1 = $check1->get_result();
    if($result_check1->num_rows>0){
        $_SESSION['error'] = "Room Name Already Taken";
        header("location:$location_back/room_update.php?room_id=$room_id");
        exit();
    }

    if(empty($image)){
         $update = $conn2->prepare("UPDATE `rooms` SET `room_name` = ?,`serial_number` = ?,`floor_id`=?,`capacity` =?,`description`=?,`user_id_update` = ?,`time_date_update` =? WHERE `room_id` =?");
        $update->bind_param("ssssssss",$room_name,$serial_number,$floor,$capacity,$description,$_SESSION['user_id'],$currentDateTime,$room_id);
        $update->execute();

        $_SESSION['success'] = "Successfully Updated";
        header("location:$location_back/rooms.php");
        exit();
    }else{
            $check_if_exist = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_id` = ?");
            $check_if_exist->bind_param("s",$room_id);
            $check_if_exist->execute();
            $result_check_if_exist = $check_if_exist->get_result();
            if($result_check_if_exist->num_rows>0){
            while($data = $result_check_if_exist->fetch_assoc()){
                    $existing_image = $data['image'] ?? '';
            
            
                if($existing_image){
                    $existing_image_path = 'assets/uploads/' . $existing_image;
                    if(file_exists($existing_image_path)){
                        unlink($existing_image_path);
                    }
                }
            }
    
        }
        if(move_uploaded_file($image_tmp,$trash_path)){
            $update = $conn2->prepare("UPDATE `rooms` SET `room_name` = ?,`serial_number` = ?,`floor_id`=?,`capacity` =?,`description`=?, `image` = ?,`user_id_update` = ?,`time_date_update` =? WHERE `room_id` =?");
            $update->bind_param("sssssssss",$room_name,$serial_number,$floor,$capacity,$description,$trash_filename,$_SESSION['user_id'],$currentDateTime,$room_id);
            $update->execute();

            $_SESSION['success'] = "Successfully Updated";
            header("location:$location_back/rooms.php");
            exit();
        }
    }




}

if(isset($_POST['delete_room'])){
        $room_id = htmlspecialchars($_POST['room_id'] ?? '');
        $location_back = ($role === "1") ? "super_admin" : "admin";
        $status = "Inactive";
          /*
        $check_if_exist = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_id` = ?");
        $check_if_exist->bind_param("s",$room_id);
        $check_if_exist->execute();
        $result_check_if_exist = $check_if_exist->get_result();
        if($result_check_if_exist->num_rows>0){
        while($data = $result_check_if_exist->fetch_assoc()){
                $existing_image = $data['image'] ?? '';
        
        
            if($existing_image){
                $existing_image_path = 'assets/uploads/' . $existing_image;
                if(file_exists($existing_image_path)){
                    unlink($existing_image_path);
                }
            }
        }

    }

  
    $delete_booking = $conn2->prepare("DELETE  FROM booking WHERE `room_id` = ?");
    $delete_booking->bind_param("s",$room_id);
    $delete_booking->execute();

    $delete = $conn2->prepare("DELETE FROM `rooms` WHERE `room_id` = ?");
    $delete->bind_param("s",$room_id);
    $delete->execute();
*/

    $update = $conn2->prepare("UPDATE `rooms` SET `status` = ? , `user_id_update` = ?, `time_date_update` = ? WHERE `room_id` = ?");
    $update->bind_param("ssss",$status,$_SESSION['user_id'],$currentDateTime,$room_id);
    $update->execute();
    
    $_SESSION['success'] = "Successfully Deleted";
    header("location:$location_back/rooms.php");
    exit();
}



if(isset($_POST['cancel_booking'])){
    $booking_id = htmlspecialchars($_POST['booking_id'] ?? '');
    $cancelled_at = htmlspecialchars($_POST['cancelled_at'] ?? '');
    $status_cancelled = "Cancelled";
    $update = $conn2->prepare("UPDATE `booking` SET `status` = ?, `cancelled_at` = ?,`user_id_cancelled` =?,`date_time_cancelled`=? WHERE `booking_id` = ?");
    $update->bind_param("sssss",$status_cancelled,$cancelled_at,$_SESSION['user_id'],$currentDateTime,$booking_id);
    $update->execute();

    if($role === "1"){
        $folder = "super_admin";
    }elseif($role === "2"){
        $folder = "admin";
    }elseif($role === "3"){
        $folder = "user";
    }

    $_SESSION['success'] = "Successfully Cancelled";
    header("Location: $folder/reservations.php");
    exit();
}







if(isset($_POST['reservation_save'])){
    
    $start_date = mysqli_real_escape_string($conn2,$_POST['start_date'] ?? '');
    $end_date = mysqli_real_escape_string($conn2,$_POST['end_date'] ?? '');
    $start_time_data = date("H:i:s", strtotime($_POST['start_time'] ?? '00:00'));
    $end_time_data = date("H:i:s", strtotime($_POST['end_time'] ?? '00:00'));
    $raw_meeting = !empty($_POST['meeting_title']) ? $_POST['meeting_title'] : ($_POST['meeting_title1'] ?? '');
    $meeting_title = mysqli_real_escape_string($conn2, $raw_meeting);
    $raw_employee = !empty($_POST['employee_name']) ? $_POST['employee_name'] : ($_POST['employee_name1'] ?? '');
    $employee_name = mysqli_real_escape_string($conn2, $raw_employee);
    $custom_start = $_POST['custom_start'] ?? [];
    $custom_end = $_POST['custom_end'] ?? [];
    $checkbox = $_POST['checkbox'] ?? 'no';
    $room_id = mysqli_real_escape_string($conn2,$_POST['room_id'] ?? '');

    $get_serial_number = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_id` = ?");
    $get_serial_number->bind_param("s", $room_id);
    $get_serial_number->execute();
    $result_serial_number = $get_serial_number->get_result();
    if($result_serial_number->num_rows > 0){
        $row_serial_number = $result_serial_number->fetch_assoc();
        $final_serial = htmlspecialchars($row_serial_number['serial_number'] ?? '');
    }

    $_SESSION['room_id'] = $room_id;
    $_SESSION['start_date_admin'] = $start_date;
    $_SESSION['end_date_admin'] = $end_date;
    $_SESSION['start_time_admin'] = $start_time_data;
    $_SESSION['end_time_admin'] = $end_time_data;
    $_SESSION['fullname_admin'] = $fullname;
    $_SESSION['meeting_title_admin'] = $meeting_title;
    $_SESSION['employee_name'] = $employee_name;
    $_SESSION['checkbox_admin'] = $checkbox;

    
    if($role === "1"){
        $folder = "super_admin";
    }elseif($role === "2"){
        $folder = "admin";
    }else{
        $folder = "user";
    }

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

    if($start_date > $end_date){
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

            $insert = $conn2->prepare("INSERT INTO `booking` (`booking_id`, `start_date`, `end_date`, `start_time`, `end_time`, `fullname`, `meeting_title`, `room_id`, `status`,`user_id_added`,`time_date_added`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?)");
            $insert->bind_param("sssssssssss", $b_id, $curr_d, $curr_d, $sql_s, $sql_e, $employee_name, $meeting_title, $room_id, $occupied,$_SESSION['user_id'],$currentDateTime);
            $insert->execute();
            
            $date_ins->modify('+1 day');
        }

        // CLEAR SESSIONS
        unset($_SESSION['room_name_admin'],$_SESSION['start_date_admin'],$_SESSION['end_date_admin'],$_SESSION['checkbox_admin'],$_SESSION['start_time_admin'],$_SESSION['end_time_admin'],$_SESSION['booked_details_admin'],$_SESSION['meeting_title_admin'],$_SESSION['employee_name']);
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

    $insert = $conn2->prepare("INSERT INTO `booking` (`booking_id`, `start_date`, `end_date`, `start_time`, `end_time`, `fullname`, `meeting_title`, `room_id`, `status`,`user_id_added`,`time_date_added`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)");
    $insert->bind_param("sssssssssss", $b_id, $start_date, $end_date, $start_time_data, $end_time_data, $employee_name, $meeting_title, $room_id, $occupied,$_SESSION['user_id'],$currentDateTime);
    
    if($insert->execute()){
        $insert->close();
        unset($_SESSION['room_name_admin'],$_SESSION['start_date_admin'],$_SESSION['end_date_admin'],$_SESSION['checkbox_admin'],$_SESSION['start_time_admin'],$_SESSION['end_time_admin'],$_SESSION['booked_details_admin'],$_SESSION['meeting_title_admin'],$_SESSION['employee_name']);
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
            (`booking_id`, `start_date`, `end_date`, `start_time`, `end_time`, `fullname`, `meeting_title`, `room_id`, `status`,`user_id_added`,`time_date_added`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)
        ");

        
        $insert->bind_param("sssssssssss", 
            $b_id, 
            $date_to_save, 
            $date_to_save, 
            $start_time_data, 
            $end_time_data, 
            $employee_name, 
            $meeting_title, 
            $room_id, 
            $occupied,
            $_SESSION['user_id'],
            $currentDateTime
        );

        // I-execute ang insert
        $insert->execute();


        $current_date->modify('+1 day');
    }

  unset($_SESSION['room_name_admin'],$_SESSION['start_date_admin'],$_SESSION['end_date_admin'],$_SESSION['checkbox_admin'],$_SESSION['start_time_admin'],$_SESSION['end_time_admin'],$_SESSION['booked_details_admin'],$_SESSION['meeting_title_admin'],$_SESSION['employee_name']);
    $_SESSION['success'] = "Booking saved for all selected dates!";
    header("location:$folder/reservations.php");
    exit();
}
///////////////////////////////////////////////////////////////////////////////////////////


    if($errors_found){
        header("location:$folder/reservation_add.php?room_id=$room_id");
        exit();
    }

}

?>
