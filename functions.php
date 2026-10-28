<?php
include 'config.php';

if(isset($_POST['update_shutdown'])){
    $room_id =  $_POST['room_id'];


    $update = "no";
    $update_heartbeat = $conn2->prepare("UPDATE `heartbeat` SET `update` = ? WHERE `room_id` = ?");
    $update_heartbeat->bind_param("ss",$update,$room_id);
    $update_heartbeat->execute();

    $tablet_status = "Inactive";
    $update = $conn2->prepare("UPDATE `rooms` SET `tablet_status` = ?,time_date_update= ?,user_id_update=? WHERE `room_id` = ?");
    $update->bind_param("ssss", $tablet_status,$currentDateTime,$_SESSION['User_Id'],$room_id);
    $update->execute();



    $_SESSION['success'] = "Successfully Shutdown";
    header("location:admin/rooms.php");
    exit();
}

if(isset($_POST['update_shutdown_superadmin'])){
    $room_id =  $_POST['room_id'];

    $update = "no";
    $update_heartbeat = $conn2->prepare("UPDATE `heartbeat` SET `update` = ? WHERE `room_id` = ?");
    $update_heartbeat->bind_param("ss",$update,$room_id);
    $update_heartbeat->execute();


    $tablet_status = "Inactive";
    $update = $conn2->prepare("UPDATE `rooms` SET `tablet_status` = ?,time_date_update= ?,user_id_update=? WHERE `room_id` = ?");
    $update->bind_param("ssss", $tablet_status,$currentDateTime,$_SESSION['User_Id'],$room_id);
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
        $update->bind_param("ssss", $user_status, $currentDateTime, $_SESSION['User_Id'], $employee_id);
        $update->execute();

        $_SESSION['success'] = "Successfully Assigned";
        header("location:super_admin/accounts.php");
        exit();

    }   else {
        $role = "2";
        # mag laog na sa database
        $id = "assigned" . rand() . uniqid();
        $insert = $conn2->prepare("INSERT INTO `accounts` (`employee_id`,`role`,`id`,`user_status`,`time_date_added`,`user_id_added`) VALUES (?,?,?,?,?,?)");
        $insert->bind_param("ssssss", $employee_id,$role,$id,$user_status,$currentDateTime,$_SESSION['User_Id']);
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
        $insert->bind_param("ssssss", $floor_id, $floor_name, $number_of_rooms, $floor_status, $_SESSION['User_Id'],$currentDateTime);
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
    $update->bind_param("ssss",$floor_status,$currentDateTime,$_SESSION['User_Id'],$floor_id);
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
        $update->bind_param("sssss",$floor_name,$number_of_rooms,$currentDateTime,$_SESSION['User_Id'],$floor_id);
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
    $tablet_status = "Active";

    if(empty($serial_number)){
        $serial_number = "custom" . "-" . $room_name .  rand();
        $tablet_status = "Inactive";
    }
      
   
    if($role === "1"){
        $folder = "super_admin";
    }elseif($role === "2"){
        $folder = "admin";
    }else{
        $folder = "user";
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
            

            $insert = $conn2->prepare("INSERT INTO `rooms` (`room_id`,`room_name`,`serial_number`,`floor_id`,`capacity`,`description`,`image`,`tablet_status`,`user_id_added`,`time_date_added`,`status`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $insert->bind_param("sssssssssss", $room_id, $room_name, $serial_number, $floor, $capacity, $description, $fileName,$tablet_status,$_SESSION['User_Id'],$currentDateTime,$status);
            
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
    $tablet_status = "Active";

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
         $update = $conn2->prepare("UPDATE `rooms` SET `room_name` = ?,`serial_number` = ?,`floor_id`=?,`capacity` =?,`description`=?,`user_id_update` = ?,`time_date_update` =?,`tablet_status` = ? WHERE `room_id` =?");
        $update->bind_param("sssssssss",$room_name,$serial_number,$floor,$capacity,$description,$_SESSION['User_Id'],$currentDateTime,$tablet_status,$room_id);
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
            $update = $conn2->prepare("UPDATE `rooms` SET `room_name` = ?,`serial_number` = ?,`floor_id`=?,`capacity` =?,`description`=?, `image` = ?,`user_id_update` = ?,`time_date_update` =?,`tablet_status` = ?  WHERE `room_id` =?");
            $update->bind_param("ssssssssss",$room_name,$serial_number,$floor,$capacity,$description,$trash_filename,$_SESSION['User_Id'],$currentDateTime,$tablet_status,$room_id);
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
    $update->bind_param("ssss",$status,$_SESSION['User_Id'],$currentDateTime,$room_id);
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
    $update->bind_param("sssss",$status_cancelled,$cancelled_at,$_SESSION['User_Id'],$currentDateTime,$booking_id);
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
    
    if($role === "1"){
        $folder = "super_admin";
    }elseif($role === "2"){
        $folder = "admin";
    }else{
        $folder = "user";
    }
    // ---- Dates now come in as an array of individually-picked dates ----
    // ---- (e.g. Aug 1, 10, 20, 25) instead of a start_date/end_date range ----
    $selected_dates_raw = $_POST['selected_date'] ?? [];
    $selected_dates = array_values(array_unique(array_filter(array_map(function($d) use ($conn2) {
        $d = trim($d);
        // basic sanity check: must look like YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) return null;
        return mysqli_real_escape_string($conn2, $d);
    }, $selected_dates_raw))));
    sort($selected_dates);

    $start_time_data = date("H:i:s", strtotime($_POST['start_time'] ?? '00:00'));
    $end_time_data = date("H:i:s", strtotime($_POST['end_time'] ?? '00:00'));
    $raw_meeting = !empty($_POST['meeting_title']) ? $_POST['meeting_title'] : ($_POST['meeting_title1'] ?? '');
    $meeting_title = mysqli_real_escape_string($conn2, $raw_meeting);
    $raw_employee =  ($_POST['employee_name1'] ?? '');
    $employee_name = mysqli_real_escape_string($conn2, $raw_employee);

  
    $custom_date  = $_POST['custom_date'] ?? [];
    $custom_start = $_POST['custom_start'] ?? [];
    $custom_end   = $_POST['custom_end'] ?? [];
    $checkbox = $_POST['checkbox'] ?? 'no';
    $room_id = mysqli_real_escape_string($conn2,$_POST['room_id'] ?? '');

    $get_serial_number = $conn2->prepare("SELECT * FROM `rooms` WHERE `room_id` = ?");
    $get_serial_number->bind_param("s", $room_id);
    $get_serial_number->execute();
    $result_serial_number = $get_serial_number->get_result();
    $final_serial = '';
    if($result_serial_number->num_rows > 0){
        $row_serial_number = $result_serial_number->fetch_assoc();
        $final_serial = htmlspecialchars($row_serial_number['serial_number'] ?? '');
    }

    $_SESSION['room_id'] = $room_id;
    $_SESSION['start_time_admin'] = $start_time_data;
    $_SESSION['end_time_admin'] = $end_time_data;
    $_SESSION['meeting_title_admin'] = $meeting_title;
    $_SESSION['employee_name'] = $employee_name;
    $_SESSION['checkbox_admin'] = $checkbox;

    $occupied = 'Occupied';
    $errors_found = false;

    if(empty($selected_dates)){
        $_SESSION['error'] = "Please select at least one date.";
        $errors_found = true;
    }

    if(!$errors_found && $final_serial === ''){
        $_SESSION['error'] = "Technical Error, Please Try again";
        $errors_found = true;
    }

    // Build the list of [date, start, end] we'll validate/insert/save-to-session,
    // regardless of whether it's custom-per-day or one uniform time for every date.
    $date_time_pairs = [];

    if(!$errors_found && $checkbox === "yes"){
        if(count($custom_date) !== count($custom_start) || count($custom_date) !== count($custom_end)){
            $_SESSION['error'] = "Technical Error, Please Try again";
            $errors_found = true;
        } else {
            foreach ($custom_date as $i => $d) {
                $d = trim($d);
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) continue;
                $date_time_pairs[] = [
                    'date'  => mysqli_real_escape_string($conn2, $d),
                    'start' => date("H:i:s", strtotime($custom_start[$i] ?? '00:00')),
                    'end'   => date("H:i:s", strtotime($custom_end[$i] ?? '00:00')),
                ];
            }
        }
    } elseif(!$errors_found){
        foreach ($selected_dates as $d) {
            $date_time_pairs[] = [
                'date'  => $d,
                'start' => $start_time_data,
                'end'   => $end_time_data,
            ];
        }
    }

    if(!$errors_found && empty($date_time_pairs)){
        $_SESSION['error'] = "Please select at least one date.";
        $errors_found = true;
    }

    // Save for session prefill (re-opening the form keeps the picked dates/times)
    if(!$errors_found){
        $_SESSION['booked_details_admin'] = array_map(function($p){
            return [
                'date'  => $p['date'],
                'start' => date("g:i A", strtotime($p['start'])),
                'end'   => date("g:i A", strtotime($p['end'])),
            ];
        }, $date_time_pairs);
    }

    // ---- Validate + insert inside ONE locked transaction ----
    // Doing the conflict check and the insert together (with FOR UPDATE) means:
    //  1) two people submitting at the same instant can't both "pass" the check
    //     and then both insert an overlapping booking (race condition), and
    //  2) if two of the dates picked in THIS submission overlap each other,
    //     the second one will see the first one's row (same transaction) and
    //     correctly flag it as a conflict too.
    if(!$errors_found){
        $conn2->begin_transaction();

        foreach ($date_time_pairs as $pair) {
            $curr_d = $pair['date'];
            $sql_s  = $pair['start'];
            $sql_e  = $pair['end'];
            $display_date = date("M d", strtotime($curr_d));

            if ($sql_s >= $sql_e) {
                $_SESSION['error'] = "Error on $display_date: Start time cannot be later than end time.";
                $errors_found = true;
                break;
            }

            if($sql_s <= $timetoday2 && $datetoday === $curr_d){
                $_SESSION['error'] = "Error on $display_date: Time Selected Already Passed";
                $errors_found = true;
                break;
            }

            // Lock any matching rows (existing bookings, or ones inserted earlier
            // in this same transaction) so nothing else can insert/overlap underneath us
            $check = $conn2->prepare("SELECT b.booking_id FROM `booking` b INNER JOIN rooms r ON b.room_id = r.room_id WHERE r.serial_number = ? AND b.status = ? AND b.start_date = ? AND (? < b.end_time AND ? > b.start_time) FOR UPDATE");
            $check->bind_param("sssss", $final_serial, $occupied, $curr_d, $sql_s, $sql_e);
            $check->execute();
            $has_conflict = $check->get_result()->num_rows > 0;
            $check->close();

            if($has_conflict){
                $_SESSION['error'] = "Conflict: Room occupied on $curr_d during selected time.";
                $errors_found = true;
                break;
            }

            $b_id = "booking" . uniqid() . rand(10, 99);

            $check_diplicate = $conn2->prepare("SELECT `booking_id` FROM `booking` WHERE `booking_id` = ? FOR UPDATE");
            $check_diplicate->bind_param("s", $b_id);
            $check_diplicate->execute();
            $check_diplicate->store_result();
            $is_dup = $check_diplicate->num_rows > 0;
            $check_diplicate->close();

            if($is_dup){
                $_SESSION['error'] = "Technical Error, Please Try again";
                $errors_found = true;
                break;
            }

            $insert = $conn2->prepare("INSERT INTO `booking` (`booking_id`, `start_date`, `end_date`, `start_time`, `end_time`, `fullname`, `meeting_title`, `room_id`, `status`,`user_id_added`,`time_date_added`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("sssssssssss", $b_id, $curr_d, $curr_d, $sql_s, $sql_e, $employee_name, $meeting_title, $room_id, $occupied, $_SESSION['User_Id'], $currentDateTime);
            $insert->execute();
            $insert->close();
        }

        if($errors_found){
            $conn2->rollback();
        } else {
            $conn2->commit();

            // CLEAR SESSIONS
            unset($_SESSION['room_name_admin'],$_SESSION['checkbox_admin'],$_SESSION['start_time_admin'],$_SESSION['end_time_admin'],$_SESSION['booked_details_admin'],$_SESSION['meeting_title_admin'],$_SESSION['employee_name']);
            $_SESSION['success'] = "All schedules saved successfully!";
            header("location:$folder/reservations.php");
            exit();
        }
    }

    if($errors_found){
        header("location:$folder/reservation_add.php?room_id=$room_id");
        exit();
    }

}

if(isset($_POST['reservation_update'])){
    $date_selected = htmlspecialchars($_POST['date_selected'] ?? '');
    $start_time = htmlspecialchars($_POST['start_time'] ?? '');
    $end_time = htmlspecialchars($_POST['end_time'] ?? '');
    $room_id = htmlspecialchars($_POST['room_id'] ?? ''); 
    $booking_id = htmlspecialchars($_POST['booking_id'] ?? '');
    $meeting_title1 = htmlspecialchars($_POST['meeting_title1'] ?? '');
    $employee_name = htmlspecialchars($_POST['employee_name'] ?? '');
    $location_back = htmlspecialchars($_POST['location_back'] ?? 'reservations.php');

    
    if(empty($date_selected) || empty($start_time) || empty($end_time) || empty($booking_id)){
        $_SESSION['error'] = "Incomplete form data provided.";
        header("location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $date_val = new DateTime($date_selected);
    $curr_d = $date_val->format('Y-m-d');
    
    $sql_s = date("H:i:s", strtotime($start_time));
    $sql_e = date("H:i:s", strtotime($end_time));


    if(isset($role)){
        if($role === "1"){
            $folder = "super_admin";
        } elseif($role === "2"){
            $folder = "admin";
        } else {
            $folder = "user";
        }
    } else {
        $folder = "user"; 
    }

   
    if($sql_s >= $sql_e){
        $_SESSION['error'] = "Invalid Time Selected: Start time must be earlier than end time.";
        header("location: {$folder}/update_reservation.php?booking_id={$booking_id}&location_back={$location_back}");
        exit; 
    }


    $check_availablity = $conn2->prepare("SELECT * FROM `booking` WHERE `room_id` = ? AND `start_date` = ? AND (`start_time` < ? AND `end_time` > ?) AND `booking_id` != ?");
    $check_availablity->bind_param("sssss", $room_id, $curr_d, $sql_e, $sql_s, $booking_id);
    $check_availablity->execute();
    $result_check = $check_availablity->get_result();

    if($result_check->num_rows > 0){
        $_SESSION['error'] = "Time and Date Selected Already Occupied";
        header("location: {$folder}/update_reservation.php?booking_id={$booking_id}&location_back={$location_back}");
        exit; 
    }else{
        $update = $conn2->prepare("UPDATE `booking` SET `start_date` = ?,`end_date` = ?,`start_time`=?,`end_time`=?,`fullname`=?,`meeting_title`=? WHERE `booking_id` = ?");
        $update->bind_param("sssssss",$date_selected,$date_selected,$start_time,$end_time,$employee_name,$meeting_title1,$booking_id);
        $update->execute();

        $_SESSION['success'] = "Successfully Updated";
         header("location: {$folder}/{$location_back}");
        exit; 
    }


   
}
?>