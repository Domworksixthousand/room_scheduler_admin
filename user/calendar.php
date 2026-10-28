<?php
    include 'index.php';
    $room_id = htmlspecialchars($_GET['room_id'] ?? '');



    
    $today_date = date('Y-m-d');
    $now_time   = date('H:i:s');
    $occupied = 'Occupied';
    $Done = 'Done';
    $stmt = $conn2->prepare("SELECT meeting_title, start_date, end_date, start_time, end_time, fullname, status FROM `booking` WHERE room_id = ? AND (status = ? OR status = ?) ORDER BY start_time ASC");
    $stmt->bind_param("sss", $room_id, $occupied, $Done);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while($row = mysqli_fetch_assoc($result)) {
        $row_start_date = $row['start_date'];
        $row_end_date   = $row['end_date'];
        $row_start_time = $row['start_time'];
        $row_end_time   = $row['end_time'];

        if ($row['status'] === 'Cancelled') {
            $status = "Cancelled";
        }
        elseif ($row_end_date < $today_date || ($row_end_date === $today_date && $now_time > $row_end_time)) {
            $status = "Done";
        }
        elseif (
            ($row_start_date < $today_date || ($row_start_date === $today_date && $now_time >= $row_start_time))
            &&
            ($row_end_date > $today_date || ($row_end_date === $today_date && $now_time <= $row_end_time))
        ) {
            $status = "On Going";
        }
        else {
            $status = "Upcoming";
        }

        $start = $row_start_date . 'T' . date("H:i:s", strtotime($row_start_time));
        $end = $row_end_date . 'T' . date("H:i:s", strtotime($row_end_time));
        $timeRange = date("g:i A", strtotime($row_start_time)) . " - " . date("g:i A", strtotime($row_end_time));

        $events[] = [
            'title' => $row['meeting_title'],
            'start' => $start,
            'end'   => $end,
            'allDay' => false,
            'extendedProps' => [
                'fullname'  => $row['fullname'],
                'timeRange' => $timeRange,
                'status'    => $status
            ]
        ];
    }
    $eventsJson = json_encode($events);

?>

<main >
    <section class="room_info_section" >
       <form action="../functions.php" method="POST">
            <div class="modal fade" id="modal_system" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                    <div class="modal-header border-0 d-flex justify-content-between">
                        <p class="modal-title fs-6 fw-bold" id="staticBackdropLabel"> Calendar</p>
                        <a href="index.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_id); ?>" readonly>
                        <div class="inner_body shadow-lg">
                            <div id="calendar-container" >
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" name="room_update" class="btn btn_update"> Update <i class='bx bx-edit '></i></button>-->
                    </div>
                    </div>
                </div>
            </div>
       </form>
    </section>
</main>


  <script>
    document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');
    const modalEl = document.getElementById('modal_system');
    const bookingData = <?php echo $eventsJson; ?>;
    const today = new Date().toISOString().split('T')[0];
    //  Initialize the calendar variable
    const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    selectable: true,
    headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: bookingData,
    navLinks: true,
    selectable: true,
    dayMaxEvents: 3, 
    height: 'auto',
 
    dateClick: function(info) {
        const roomId = "<?php echo $room_id; ?>";
        const clickedDate = info.dateStr; 
           if (clickedDate < today) {
                CoolAlert.show({
                    title: "Selected Date Already Passed",
                    icon:"error",
                    confirmButtonText: "Return to Calendar",
                    confirmButtonColor: "#000080"
                });
            return; 
        }
        window.location.href = `reservation_add.php?room_id=${roomId}&date=${clickedDate}`;
    },

    eventContent: function(arg) {
    let container = document.createElement('div');
    container.style.cssText = 'overflow: hidden;';
    container.innerHTML = `
        <div style="font-size: 0.75rem; font-weight: 700; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            ${arg.event.title}
        </div>
        <div style="font-size: 0.65rem; color: rgba(255,255,255,0.85); display: flex; align-items: center; margin-top: 2px;">
            <span style="margin-right: 4px;">•</span> ${arg.event.extendedProps.timeRange}
        </div>
    `;
    return { domNodes: [container] };
    },

    eventDidMount: function(info) {
    info.el.setAttribute('title', `${info.event.title} | ${info.event.extendedProps.fullname}`);
    },

    eventClick: function(info) {
    CoolAlert.show({
        title: `<span style="color:white; font-weight: bold; border-bottom: 2px solid white; padding-bottom: 5px;">Event Detailed Brief</span>`,
        html: `
            <div style="text-align: left; margin-top: 20px; font-family: 'Segoe UI', sans-serif;">
                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="margin-bottom: 15px;">
                        <small style="color: white; text-transform: uppercase; font-weight: bold; font-size: 0.7rem;">Meeting Title</small>
                        <div style="font-size: 1.1rem; font-weight: 600; color: white;">${info.event.title}</div>
                    </div>
                    <div>
                        <small style="color: white; text-transform: uppercase; font-weight: bold; font-size: 0.7rem;">Status</small>
                        <div style="color: white;">${info.event.extendedProps.status}</div>
                    </div>
                </div>
                <div style="display: flex; gap: 20px;">
                    <div>
                        <small style="color: white; text-transform: uppercase; font-weight: bold; font-size: 0.7rem;">Schedule</small>
                        <div style="color: white;">${info.event.extendedProps.timeRange}</div>
                    </div>
                    <div>
                        <small style="color: white; text-transform: uppercase; font-weight: bold; font-size: 0.7rem;">Reserved by</small>
                        <div style="color: white;">${info.event.extendedProps.fullname}</div>
                    </div>
                </div>
            </div>`,
        confirmButtonText: "Return to Calendar",
        confirmButtonColor: "#000080"
    });
    }
    });


    calendar.render();

        modalEl.addEventListener('shown.bs.modal', function () {
            calendar.updateSize();
        });


        const calendarTabTrigger = document.querySelector('#profile-tab');
        if (calendarTabTrigger) {
            calendarTabTrigger.addEventListener('shown.bs.tab', function () {
                calendar.updateSize(); 
            });
        }
    });
</script>