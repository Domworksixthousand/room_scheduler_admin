<?php

    include 'index.php'; 
            $date    = $_GET['date'] ?? '';
            $booked_details = isset($_SESSION['booked_details_admin']) ? $_SESSION['booked_details_admin'] : [];
            if(isset($_SESSION['checkbox_admin']) && $_SESSION['checkbox_admin'] === "yes"){
            #para auto labas ng checkbox
                echo "<script>
                document.addEventListener('DOMContentLoaded', function(){
                    const wrapper = document.getElementById('wrapper');
                    const checkbox = document.getElementById('terms-checkbox-37');

                    if(checkbox) {
                        checkbox.checked = true;
                    }

                    if (window.selectedDatesArr && window.selectedDatesArr.length > 1) {
                        wrapper.classList.remove('d-none');
                        checkbox.dispatchEvent(new Event('change'));
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
                        <a href="index.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <div class="inner_body shadow-lg">
                            <input type="hidden" value="<?php echo htmlspecialchars($_GET['room_id'] ?? ''); ?>" name="room_id">

                            <div class="row mb-0">
                                <div class="col-lg-12 mb-4">
                                    <label class="form-label">Select Date(s)</label>
                                    <input type="text" class="form-control" value="<?php echo $date ?? ''; ?>" id="multi_dates" placeholder="Click to Select Single or Multiple Dates" readonly required>
                                    <!-- One hidden input per selected date gets injected here on change -->
                                    <div id="selected_dates_hidden"></div>
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
                                        <input type="text" id="empSearch" name="employee_name1" class="form-control uppercase_function" value="<?php echo htmlspecialchars($_SESSION['employee_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"  placeholder="Type to search..." autocomplete="off" required>
                                            <input type="hidden" value="<?php echo htmlspecialchars($_SESSION['employee_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" name="employee_name"  id="selectedEmployee" >
                                            <!-- Dropdown -->
                                            <div id="empDropdown" 
                                                class="dropdown_body card position-absolute w-100 shadow-sm mt-1 d-none" >
                                                <div class="list-group list-group-flush" id="empList">
                                                    <?php
                                            
                                                        if (isset($conn1)) {
                                                            $conn1->set_charset("utf8mb4");
                                                        }

                                                        $users = $conn1->prepare("SELECT * FROM `employee` ORDER BY LastName, FirstName ASC");
                                                        $users->execute();
                                                        $result_users = $users->get_result();

                                                        if($result_users->num_rows > 0){
                                                            while ($row_users = $result_users->fetch_assoc()) {
                                                                $lastname   = htmlspecialchars($row_users['LastName'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
                                                                $firstname  = htmlspecialchars($row_users['FirstName'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
                                                                $middlename = htmlspecialchars($row_users['MiddleName'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
                                                                $employee_id = htmlspecialchars($row_users['Employee_ID'] );
                                                                
                                                                $fullname   = trim("$lastname, $firstname $middlename");
                                                    ?>
                                                                <button type="button" 
                                                                        class="list-group-item list-group-item-action emp-item"
                                                                        data-id="<?php echo $employee_id; ?>"
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
                        <button type="submit" name="reservation_save" class="btn btn_save"> 
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
    const wrapper = document.getElementById("wrapper");
    const checkbox = document.getElementById("terms-checkbox-37");
    const not_custom = document.getElementById("not_custom");
    const custom_section = document.getElementById("custom");
    const container = document.getElementById("custom_container");
    const labelText = document.querySelector(".label-text");
    const hiddenDatesWrap = document.getElementById("selected_dates_hidden");

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

    // ---- Build the list of ISO ("YYYY-MM-DD") strings from flatpickr's selectedDates ----
    function toIsoList(dateObjs) {
        return dateObjs
            .map(d => {
                let y = d.getFullYear();
                let m = String(d.getMonth() + 1).padStart(2, '0');
                let day = String(d.getDate()).padStart(2, '0');
                return `${y}-${m}-${day}`;
            })
            .sort(); // keep chronological order regardless of click order
    }

    // ---- Writes one hidden <input name="selected_date[]"> per chosen date so the ----
    // ---- backend (functions.php) receives them as an array on submit             ----
    function syncHiddenDateInputs(isoList) {
        hiddenDatesWrap.innerHTML = "";
        isoList.forEach(dateStr => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "selected_date[]";
            input.value = dateStr;
            hiddenDatesWrap.appendChild(input);
        });
        window.selectedDatesArr = isoList;
    }

    function buildCustomSlotsFromDates(isoList) {
        container.innerHTML = "";
        isoList.forEach(dateStr => {
            const [y, m, d] = dateStr.split('-').map(Number);
            const dateObj = new Date(y, m - 1, d);
            const displayDate = dateObj.toLocaleDateString('en-US', {
                weekday: 'short', month: 'short', day: 'numeric'
            });

            let sStart = "";
            let sEnd = "";
            const foundMatch = sessionBookedDetails.find(item => item.date === dateStr);
            if (foundMatch) {
                sStart = convertTo24h(foundMatch.start);
                sEnd = convertTo24h(foundMatch.end);
            }

            createTimeSlot(dateStr, displayDate, sStart, sEnd);
        });
    }

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

    // ---- Multi-date picker: lets the user click Aug 1, then 10, then 20, then 25 ----
    // ---- (non-contiguous) instead of only a start/end range                      ----
    const preselected = sessionBookedDetails.map(item => item.date).filter(Boolean);

    const fp = flatpickr("#multi_dates", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "M j, Y",
        minDate: "today",
        defaultDate: preselected.length ? preselected : undefined,
        onChange: function(selectedDates) {
            const isoList = toIsoList(selectedDates);
            syncHiddenDateInputs(isoList);

            if (isoList.length < 2) {
                // Custom per-day scheduling only makes sense with 2+ dates
                wrapper.classList.add("d-none");
                checkbox.checked = false;
                resetToSingleMode();
                return;
            }

            wrapper.classList.remove("d-none");

            if (checkbox.checked) {
                buildCustomSlotsFromDates(isoList);
            }
        }
    });

    checkbox.addEventListener("change", function() {
        const isoList = window.selectedDatesArr || [];

        if (this.checked) {
            if (isoList.length < 2) {
                this.checked = false;
                return;
            }

            labelText.innerHTML = "Use same time for all dates";
            not_custom.style.display = "none";
            custom_section.style.display = "block";
            buildCustomSlotsFromDates(isoList);
        } else {
            resetToSingleMode();
        }
    });

    // Auto-trigger if session is active (only relevant with 2+ dates)
    // Show the checkbox wrapper whenever 2+ dates carried over from a previous
    // submission (e.g. after a validation/conflict error redirected back here) —
    // even if "custom" wasn't checked before, so the user can still switch to
    // per-day times if they change their mind.
    if (preselected.length > 0) {
        syncHiddenDateInputs(preselected.slice().sort());
    }

    if (preselected.length > 1) {
        wrapper.classList.remove("d-none");

        if (sessionCheckbox === "yes") {
            checkbox.checked = true;
            checkbox.dispatchEvent(new Event('change'));
        }
    }
});
</script>