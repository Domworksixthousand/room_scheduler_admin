<?php

    include 'rooms.php'; 
            $date    = $_GET['date'] ?? '';
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
       <form action="../functions.php" method="POST" id="postForm">
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between">
                        <p class="modal-title  fw-bold" id="staticBackdropLabel">Add Reservation</p>
                        <a href="rooms.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="inner_body shadow-lg">
                            <input type="hidden" value="<?php echo htmlspecialchars($_GET['room_id'] ?? ''); ?>" name="room_id">
                            <div class="row mb-0">
                                    <div class="col-lg-6 mb-4">
                                        <label class="form-label">Select Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $_SESSION['start_date_admin'] ?? $date; ?>" required>
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
                                    <div class="position-relative">
                                        <!-- Search Input -->
                                        <input type="text" id="empSearch" name="employee_name1" class="form-control" value="<?php echo htmlspecialchars($_SESSION['employee_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"  placeholder="Type to search..." autocomplete="off" required>
                                            <input type="hidden" value="<?php echo htmlspecialchars($_SESSION['employee_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" name="employee_name"  id="selectedEmployee_meeting" >
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
                                        <input type="text" id="empSearchmeeting" name="meeting_title1" class="form-control" value="<?php echo htmlspecialchars($_SESSION['meeting_title_admin'] ?? '', ENT_QUOTES, 'UTF-8') ?>"  placeholder="Type to search..." autocomplete="off" required>
                                             <input type="hidden" value="<?php echo $_SESSION['meeting_title_admin'] ?? ''; ?>" class="form-control uppercase_function" id="meeting_title" name="meeting_title" placeholder="Enter Meeting Title" >
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
                      <button type="submit" name="reservation_save" class="btn btn_save ">
                         Save <i class="bx bx-checkbox-checked fs-4 "></i>
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