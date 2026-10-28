<?php


    if(isset($_GET['booking_id']) && isset($_GET['location_back'])){
        $booking_id = $_GET['booking_id'];
        $location_back = $_GET['location_back'];
    }
    include "$location_back"; 
    $get_info = $conn2->prepare("SELECT * FROM `booking` WHERE `booking_id` = ?");
    $get_info->bind_param("s",$booking_id);
    $get_info->execute();
    $result_info = $get_info->get_result();
    if($result_info->num_rows>0){
        while($row_result = mysqli_fetch_assoc($result_info)){
            $start_date = htmlspecialchars($row_result['start_date']);
            $end_date = htmlspecialchars($row_result['end_date']);
            $start_time = htmlspecialchars($row_result['start_time']);
            $end_time = htmlspecialchars($row_result['end_time']);
            $fullname = htmlspecialchars($row_result['fullname']);
            $meeting_title = htmlspecialchars($row_result['meeting_title']);
            $room_id = htmlspecialchars($row_result['room_id']);
        }
    }
           

            
    ?>

    


<main >
    <section class="reservation_add_section" >
       <form action="../functions.php" method="POST" id="postForm">
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between">
                        <p class="modal-title  fw-bold" id="staticBackdropLabel">Reschedule / Update </p>
                        <a href="<?php echo $location_back; ?>" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="inner_body shadow-lg">
                            <input type="hidden" value="<?php echo $booking_id; ?>" name="booking_id">
                             <input type="hidden" value="<?php echo $location_back; ?>" name="location_back">
                              <input type="hidden" value="<?php echo $room_id; ?>" name="room_id">
                            <div class="row mb-0">
                                    <div class="col-lg-12 mb-4">
                                        <label class="form-label">Select Date</label>
                                        <input type="date" class="form-control" id="start_date" name="date_selected" value="<?php echo $start_date; ?>" required>
                                    </div>
                            </div>
                            <div class="row mb-0" id="not_custom">
                                <div class="col-lg-6 mb-4">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" name="start_time" value="<?php echo !empty($start_time) ? date('H:i', strtotime($start_time)) : ''; ?>" class="form-control" >
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" name="end_time" value="<?php echo !empty($end_time) ? date('H:i', strtotime($end_time)) : ''; ?>" class="form-control" >
                                </div>
                            </div> 
                            <div class="row mb-0">
                                <div class="col-lg-6 mb-4">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <div class="position-relative">
                                        <!-- Search Input -->
                                        <input type="text" id="empSearch" name="employee_name1" class="form-control" value="<?php echo htmlspecialchars($fullname ?? '', ENT_QUOTES, 'UTF-8') ?>"  placeholder="Type to search..." autocomplete="off" required>
                                            <input type="hidden" value="<?php echo htmlspecialchars($fullname ?? '', ENT_QUOTES, 'UTF-8') ?>" name="employee_name"  id="selectedEmployee" >
                                            <!-- Dropdown -->
                                            <div id="empDropdown" 
                                                class="dropdown_body card position-absolute w-100 shadow-sm mt-1 d-none" >
                                                <div class="list-group list-group-flush" id="empList">
                                                    <?php
                                                    $users = $conn1->prepare("SELECT * FROM `employee` ORDER BY LastName, FirstName ASC");
                                                    $users->execute();
                                                    $result_users = $users->get_result();

                                                    if($result_users->num_rows > 0){
                                                        while ($row_users = $result_users->fetch_assoc()) {

                                                            $lastname  = htmlspecialchars($row_users['LastName'] ?? '', ENT_QUOTES, 'UTF-8');
                                                            $firstname = htmlspecialchars($row_users['FirstName'] ?? '', ENT_QUOTES, 'UTF-8');
                                                            $middlename = htmlspecialchars($row_users['MiddleName'] ?? '', ENT_QUOTES, 'UTF-8');
                                                            $fullname  = trim("$lastname $firstname $middlename");
                                                    ?>
                                                        <button type="button" 
                                                                class="list-group-item list-group-item-action emp-item"
                                                                data-name="<?php echo $fullname; ?>">
                                                            <?php echo $fullname; ?>
                                                        </button>
                                                    <?php 
                                                        }
                                                    } 
                                                    ?>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label for="meeting_title" class="form-label ">Meeting Title</label>
                                     <div class="position-relative">
                                        <!-- Search Input -->     
                                         <input type="text" id="empSearchmeeting" name="meeting_title1" class="form-control" value="<?php echo htmlspecialchars($meeting_title ?? '', ENT_QUOTES, 'UTF-8') ?>"  placeholder="Type to search..." autocomplete="off" required>
                                             <input type="hidden" value="<?php echo $meeting_title ?? ''; ?>" class="form-control uppercase_function" id="meeting_title" name="meeting_title" placeholder="Enter Meeting Title" >
                                            <!-- Dropdown -->
                                            <div id="empDropdown_meeting" 
                                                class="dropdown_body card position-absolute w-100 shadow-sm mt-1 d-none" >
                                                <div class="list-group list-group-flush" id="empList_meeting">
                                                    <?php
                                                    $meeting_selection = $conn2->prepare("SELECT DISTINCT meeting_title FROM `booking`");
                                                    $meeting_selection->execute();
                                                    $result_selection = $meeting_selection->get_result();
                                                    if($result_selection->num_rows>0){
                                                        while($row_meet =mysqli_fetch_assoc($result_selection)){
                                                        $meeting_title =  htmlspecialchars($row_meet['meeting_title'] ?? '', ENT_QUOTES, 'UTF-8');

                                                    ?>
                                                        <button type="button" 
                                                                class="list-group-item list-group-item-action emp-item_meeting"
                                                                data-name="<?php echo $meeting_title; ?>">
                                                            <?php echo $meeting_title; ?>
                                                        </button>
                                                    <?php 
                                                        }
                                                    } 
                                                    ?>
                                                </div>
                                            </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                        <button type="submit" name="reservation_update" class="btn btn_save pt-1"> 
                            Update <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                        </button>
                        <div id="loadingOverlay">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
       </form>
    </section>
</main>


