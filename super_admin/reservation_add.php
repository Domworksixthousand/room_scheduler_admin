<?php

    include 'reservations.php'; 

    $data1 = "Select Room"; 
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

            $booked_details = isset($_SESSION['booked_details_admin']) ? $_SESSION['booked_details_admin'] : [];
            if(isset($_SESSION['checkbox_admin']) && $_SESSION['checkbox_admin'] === "yes"){
            #para auto labas ng checkbox
                echo "<script>
                document.addEventListener('DOMContentLoaded', function(){
                    const startDateInput = document.getElementById('start_date');
                    const endDateInput = document.getElementById('end_date');
                    const wrapper = document.getElementById('wrapper');
                    const checkbox = document.getElementById('terms-checkbox-37');


                    if(checkbox) {
                        checkbox.checked = true;
                    }


                    const startVal = startDateInput.value;
                    const endVal = endDateInput.value;

                    if (startVal && endVal) {
                        const start = new Date(startVal);
                        const end = new Date(endVal);

                        if (end > start) {
                
                            wrapper.classList.remove('d-none');
                    
                
                            checkbox.dispatchEvent(new Event('change'));
                        }
                    }
                });
                </script>";
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
                        <div class="inner_body shadow-lg">
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
                            <div class="row mb-0">
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label">Select Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $_SESSION['start_date_admin'] ?? ''; ?>" required>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label">Select End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $_SESSION['end_date_admin'] ?? ''; ?>" required>
                                    </div>
                            </div>
                            <div id="wrapper" class="checkbox-wrapper mb-3 d-none">
                                    <input id="terms-checkbox-37" name="checkbox" type="checkbox" value="yes">
                                    <label class="terms-label" for="terms-checkbox-37">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 200 200" class="checkbox-svg">
                                        <mask fill="white" id="path-1-inside-1_476_5-37">
                                            <rect height="200" width="200"></rect>
                                        </mask>
                                        <rect mask="url(#path-1-inside-1_476_5-37)" stroke-width="40" class="checkbox-box" height="200" width="200"></rect>
                                        <path stroke-width="15" d="M52 111.018L76.9867 136L149 64" class="checkbox-tick"></path>
                                        </svg>
                                        <span class="label-text">Custom daily time schedule</span>
                                    </label>
                            </div>
                            <div class="row mb-0" id="not_custom">
                                <div class="col-lg-6 mb-4">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" name="start_time" value="<?php echo $_SESSION['start_time_admin'] ?? ''; ?>"  class="form-control" >
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" value="<?php echo $_SESSION['end_time_admin'] ?? ''; ?>" name="end_time" class="form-control" >
                                </div>
                            </div>
                            <div id="custom" class="custom_time">
                                <p class="text-muted small fw-bold mb-2">Set time for each day:</p>
                                <div id="custom_container">
                                <!--show data-->
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
                                        while ($row_users = $result_users->fetch_assoc()) {

                                                $lastname   = htmlspecialchars($row_users['LastName'] ?? '', ENT_QUOTES, 'UTF-8');
                                                $firstname  = htmlspecialchars($row_users['FirstName'] ?? '', ENT_QUOTES, 'UTF-8');
                                                $middlename = htmlspecialchars($row_users['MiddleName'] ?? '', ENT_QUOTES, 'UTF-8');

                                                $fullname = "$lastname $firstname $middlename";

                                                echo "<option value='$fullname'>$fullname</option>";
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
                                <label for="custom_fullname" class="form-label">Type Your Full Name</label>
                                <input type="text" value="<?php echo $_SESSION['custom_fullname_admin'] ?? ''; ?>" name="custom_fullname" class="form-control uppercase_function"  placeholder="Enter Your Full Name" >
                            </div>
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






<script>
document.addEventListener("DOMContentLoaded", function() {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const wrapper = document.getElementById("wrapper");
    const checkbox = document.getElementById("terms-checkbox-37");
    const not_custom = document.getElementById("not_custom");
    const custom_section = document.getElementById("custom");
    const container = document.getElementById("custom_container");
    const labelText = document.querySelector(".label-text");


    const sessionBookedDetails = <?php echo isset($_SESSION['booked_details_admin']) ? json_encode($_SESSION['booked_details_admin']) : '[]'; ?>;
    const sessionCheckbox = "<?php echo $_SESSION['checkbox_admin'] ?? ''; ?>";

    function convertTo24h(timeStr) {
        if (!timeStr) return "";
        let [time, modifier] = timeStr.split(' ');
        let [hours, minutes] = time.split(':');
        if (hours === '12') hours = '00';
        if (modifier === 'PM') hours = parseInt(hours, 10) + 12;
        return `${String(hours).padStart(2, '0')}:${minutes}`;
    }

    function resetToSingleMode() {
        not_custom.style.display = "flex";
        custom_section.style.display = "none";
        labelText.innerHTML = "Custom daily time schedule";
        container.innerHTML = "";
    }

    checkbox.addEventListener("change", function() {
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;

        if (this.checked) {
            if (!startVal || !endVal) {
                this.checked = false;
                return;
            }

            const start = new Date(startVal);
            const end = new Date(endVal);

            labelText.innerHTML = "Use same time for all dates";
            not_custom.style.display = "none";
            custom_section.style.display = "block";
            container.innerHTML = ""; 

            let current = new Date(start);
            while (current <= end) {
                let y = current.getFullYear();
                let m = String(current.getMonth() + 1).padStart(2, '0');
                let d = String(current.getDate()).padStart(2, '0');
                let dateStr = `${y}-${m}-${d}`;

                let displayDate = current.toLocaleDateString('en-US', { 
                    weekday: 'short', month: 'short', day: 'numeric' 
                });

                // 2. SEARCH the array for the matching date
                let sStart = "";
                let sEnd = "";
                
                const foundMatch = sessionBookedDetails.find(item => item.date === dateStr);
                
                if (foundMatch) {
                    // Convert AM/PM from session to 24h for the input value
                    sStart = convertTo24h(foundMatch.start);
                    sEnd = convertTo24h(foundMatch.end);
                }

                createTimeSlot(dateStr, displayDate, sStart, sEnd);
                current.setDate(current.getDate() + 1);
            }
        } else {
            resetToSingleMode();
        }
    });

    function createTimeSlot(dateValue, dateLabel, startTime, endTime) {
        const div = document.createElement("div");
        div.className = "p-3 mb-2 border rounded bg-light shadow-sm";
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-4">
                    <span class="fw-bold text-primary">${dateLabel}</span>
                    <input type="hidden" name="custom_date[]" value="${dateValue}">
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Start</span>
                        <input type="time" name="custom_start[]" value="${startTime}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">End</span>
                        <input type="time" name="custom_end[]" value="${endTime}" class="form-control" required>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // Auto-trigger if session is active
    if (sessionCheckbox === "yes" && startDateInput.value && endDateInput.value) {
        wrapper.classList.remove("d-none");
        checkbox.checked = true;
        checkbox.dispatchEvent(new Event('change'));
    }
});
</script>