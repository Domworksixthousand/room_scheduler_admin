<?php

     include 'reservations.php'; 

    $data1 = "Select Room"; // Default value
$room_id_session = "";

if(isset($_SESSION['room_name_admin'])){
    $room_val = $_SESSION['room_name_admin']; 
    $check_session = $conn2->prepare("SELECT * FROM `rooms` LEFT JOIN floors ON rooms.floor_id = floors.floor_id WHERE rooms.serial_number = ?");
    $check_session->bind_param("s", $room_val);
    $check_session->execute();
    $result_session = $check_session->get_result();
    
    if($row_session = $result_session->fetch_assoc()){
        $room_name_session = htmlspecialchars($row_session['room_name'] ?? '');
        $room_id_session = htmlspecialchars($row_session['room_id'] ?? '');
        $floor_named_session = htmlspecialchars($row_session['floor_name'] ?? '');


        $data1 = $room_name_session . " (" . $floor_named_session . ")";
    }
}
?>

<main >
    <section class="reservation_add_section" >
       <form action="../functions.php" method="POST">
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between">
                        <p class="modal-title  fw-bold" id="staticBackdropLabel">Add Reservation</p>
                        <a href="reservations.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="room" class="form-label">Select Room</label>
                            <select name="room_id" id="room" class="form-control" required>
                                <option value="<?php echo $room_id_session ?? 'Select Room'; ?>"><?php echo $data1 ?? 'Select Room'; ?></option>
                                <?php
                                    $room_get = $conn2->prepare("SELECT * FROM `rooms` LEFT JOIN `floors` ON rooms.floor_id = floors.floor_id ORDER BY rooms.room_name ASC");
                                    $room_get->execute();
                                    $get_room = $room_get->get_result();
                                    if($get_room->num_rows>0){
                                        while($row_room = $get_room->fetch_assoc()){
                                            $room_name = htmlspecialchars($row_room['room_name'] ?? '');
                                            $room_id = htmlspecialchars($row_room['room_id'] ?? '');
                                            $floor_name = htmlspecialchars($row_room['floor_name'] ?? '');
                                            
                                            echo "<option value='$room_id'>$room_name ($floor_name)</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="row  mb-0">
                            <div class="col-lg-6 mb-4">
                                <label for="name" class="form-label">Select Start Date</label>
                                <input type="date" class="form-control" value="<?php echo $_SESSION['start_date_admin'] ?? ''; ?>" id="start_date" name="start_date"  required>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <label for="name" class="form-label">Select End Date</label>
                                <input type="date" class="form-control" value="<?php echo $_SESSION['end_date_admin'] ?? ''; ?>" id="end_date" name="end_date" required>
                            </div>
                        </div>
                        <div class="row  mb-0">
                            <div class="col-lg-6 mb-4">
                                <label for="name" class="form-label">Start Time</label>
                                <select name="start_time" id="start_time" class="form-control">
                                    <option value="<?php echo $_SESSION['start_time_admin'] ?? 'Select Start Time'; ?>" ><?php echo $_SESSION['start_time_admin'] ?? 'Select Start Time'; ?></option>
                                    <option value="08:00 AM">08:00 AM </option>
                                    <option value="09:00 AM">09:00 AM </option>
                                    <option value="10:00 AM">10:00 AM</option>
                                    <option value="11:00 AM">11:00 AM </option>
                                    <option value="12:00 PM">12:00 PM</option>
                                    <option value="1:00 PM">01:00 PM </option>
                                    <option value="2:00 PM">02:00 PM </option>
                                    <option value="3:00 PM">03:00 PM </option>
                                    <option value="4:00 PM">04:00 PM </option>
                                    <option value="5:00 PM">05:00 PM </option>
                                    <option value="6:00 PM">06:00 PM </option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <label for="name" class="form-label">End Time</label>
                                <select name="end_time" id="end_time" class="form-control">
                                    <option value="<?php echo $_SESSION['end_time_admin'] ?? 'Select End Time'; ?>" ><?php echo $_SESSION['end_time_admin'] ?? 'Select End Time'; ?></option>
                                    <option value="08:00 AM">08:00 AM </option>
                                    <option value="09:00 AM">09:00 AM </option>
                                    <option value="10:00 AM">10:00 AM </option>
                                    <option value="11:00 AM">11:00 AM </option>
                                    <option value="12:00 PM">12:00 PM</option>
                                    <option value="1:00 PM">01:00 PM </option>
                                    <option value="2:00 PM">02:00 PM </option>
                                    <option value="3:00 PM">03:00 PM </option>
                                    <option value="4:00 PM">04:00 PM </option>
                                    <option value="5:00 PM">05:00 PM </option>
                                    <option value="6:00 PM">06:00 PM </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-lg-6 mb-4">
                                <label for="fullname" class="form-label">Full Name</label>
                                <select name="fullname" id="fullname"  class="form-control">
                                    <option value="<?php echo $_SESSION['fullname_admin'] ?? 'Select Fullname'; ?>"><?php echo $_SESSION['fullname_admin'] ?? 'Select Fullname'; ?></option>
                                    <option value="Others">Type your Name</option>
                                    <?php
                                    $users = $conn1->prepare("SELECT * FROM `employee` ORDER BY LastName,FirstName,MiddleName ASC");
                                    $users->execute();
                                    $result_users = $users->get_result();
                                    if($result_users->num_rows>0){
                                        while($row_users = mysqli_fetch_assoc($result_users)){
                                            $lastname = htmlspecialchars($row_users['LastName'] ?? '');
                                            $firstname = htmlspecialchars($row_users['FirstName'] ?? '');
                                            $middlename = htmlspecialchars($row_users['MiddleName'] ?? '');
                                            $employee_id = htmlspecialchars($row_users['Employee_ID'] ?? '');
                                            echo "<option value='$lastname  $firstname  $middlename'>$lastname  $firstname  $middlename</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <label for="meeting_title" class="form-label ">Meeting Title</label>
                                <input type="text" value="<?php echo $_SESSION['meeting_title_admin'] ?? ''; ?>" class="form-control uppercase_function" id="meeting_title" name="meeting_title" placeholder="Enter Meeting Title" required>
                            </div>
                        </div>
                        <div class="mb-4 d-none" id="custom_fullname">
                            <label for="" class="form-label">Type Your Full Name</label>
                            <input type="text" value="<?php echo $_SESSION['custom_fullname_admin'] ?? ''; ?>" name="custom_fullname" class="form-control uppercase_function"  placeholder="Enter Your Full Name">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                      <button type="submit" name="reservation_save" class="btn btn_save"> Save <i class="bx bx-checkbox-checked fs-4 "></i></button>
                    </div>
                    </div>
                </div>
            </div>
       </form>
    </section>
</main>
