<?php
    include 'rooms.php'; 
    if(isset($_GET['room_id'])){
        $room_id = htmlspecialchars($_GET['room_id'] ?? '');
    }

    $get_rooms = $conn2->prepare("SELECT * FROM `rooms`  LEFT JOIN `floors` ON rooms.floor_id = floors.floor_id WHERE `room_id` = ?");
    $get_rooms->bind_param("s",$room_id);
    $get_rooms->execute();
    $result_rooms = $get_rooms->get_result();
    if($result_rooms->num_rows>0){
    while($row_get = mysqli_fetch_assoc($result_rooms)){
        $room_name = htmlspecialchars($row_get['room_name'] ?? '');
        $serial_number = htmlspecialchars($row_get['serial_number'] ?? '');
        $floor_name  = htmlspecialchars($row_get['floor_name'] ?? '');
        $serial_number = htmlspecialchars($row_get['serial_number'] ?? '');
        $floor_id = htmlspecialchars($row_get['floor_id'] ?? '');
        $capacity = htmlspecialchars($row_get['capacity'] ?? '');
        $description = htmlspecialchars($row_get['description'] ?? '');
        $image = htmlspecialchars($row_get['image'] ?? '');
        }
    }

    //calendar ini
    $occupied = 'Occupied';
    $Done = 'Done';
    $query = "SELECT meeting_title, start_date, end_date, start_time, end_time, fullname, status FROM `booking` WHERE room_id = '$room_id' AND (`status` = '$occupied' OR `status` = '$Done' ) ORDER BY start_time ASC";
    $result = mysqli_query($conn2, $query);

    $events = [];
    while($row = mysqli_fetch_assoc($result)) {
        $start = $row['start_date'] . 'T' . date("H:i:s", strtotime($row['start_time']));
        $end = $row['end_date'] . 'T' . date("H:i:s", strtotime($row['end_time']));
        $timeRange = date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time']));
        $status = $row['status'];
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
                        <p class="modal-title fs-5 fw-bold" id="staticBackdropLabel"> Room Information</p>
                        <a href="rooms.php" class=" btn_x "><i class="bx bx-x"></i></a>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_id); ?>" readonly>
                        <div class="inner_body shadow-lg">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button">
                                        Room Details
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button">
                                        Calendar
                                    </button>
                                </li>
                            </ul>
                              <div class="tab-content p-3 border border-top-0" id="myTabContent">
                                <div class="tab-pane fade show active" id="home">
                                    <div class="row">
                                        <div class="col-lg-5 .col-md-6 .col-sm-12">
                                            <img src="../assets/uploads/<?php echo htmlspecialchars($image); ?>" alt="Room Image" class="img-fluid rounded mb-3">
                                        </div>
                                        <div class="col-lg-7 .col-md-6 .col-sm-12">
                                          <ul class="list-group list-group-flush border-top">
                                            <!-- Serial Number (Featured Item) -->
                                            <li class="list-group-item d-flex align-items-center py-3">
                                                <div class="icon-box bg-primary text-white rounded p-2 me-3 shadow-sm">
                                                    <i class='bx bx-barcode fs-4'></i>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem; letter-spacing: 1px;">Serial Number</small>
                                                    <h6 class="m-0 fw-bold text-dark"><?php echo htmlspecialchars($serial_number); ?></h6>
                                                </div>
                                            </li>

                                            <!-- Room Name -->
                                            <li class="list-group-item d-flex align-items-center py-2">
                                                <i class='bx bx-door-open me-3 text-secondary fs-5'></i>
                                                <div class="d-flex justify-content-between w-100 align-items-center">
                                                    <span class="text-muted small">Room Name:</span>
                                                    <span class="fw-semibold"><?php echo htmlspecialchars($room_name); ?></span>
                                                </div>
                                            </li>

                                            <!-- Floor -->
                                            <li class="list-group-item d-flex align-items-center py-2">
                                                <i class='bx bx-layer me-3 text-secondary fs-5'></i>
                                                <div class="d-flex justify-content-between w-100 align-items-center">
                                                    <span class="text-muted small">Floor:</span>
                                                    <span class="fw-semibold"><?php echo htmlspecialchars($floor_name); ?></span>
                                                </div>
                                            </li>

                                            <!-- Capacity -->
                                            <li class="list-group-item d-flex align-items-center py-2">
                                                <i class='bx bx-group me-3 text-secondary fs-5'></i>
                                                <div class="d-flex justify-content-between w-100 align-items-center">
                                                    <span class="text-muted small">Capacity:</span>
                                                    <span class="badge bg-light text-dark border fw-normal"><?php echo htmlspecialchars($capacity); ?> Persons</span>
                                                </div>
                                            </li>

                                            <!-- Description -->
                                            <li class="list-group-item border-0 py-3">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class='bx bx-info-circle me-3 text-secondary fs-5'></i>
                                                    <span class="text-muted small">Description:</span>
                                                </div>
                                                <p class="text-secondary mb-0 ps-5 small leading-relaxed">
                                                    <?php echo htmlspecialchars($description); ?>
                                                </p>
                                            </li>
                                        </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile">
                                    <div id="calendar-container" >
                                        <div id="calendar"></div>
                                    </div>
                                </div>
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
    // 1. Initialize the calendar variable
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
                        <small style="color: white; text-transform: uppercase; font-weight: bold; font-size: 0.7rem;">Organizer</small>
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
