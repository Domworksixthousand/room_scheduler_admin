

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/css/admin_sidebar.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
  </head>
  <body>
 

  <?php
    include '../config.php'; 
    if(!isset($_SESSION['admin_login'])){
      echo "<script>location.href='../index.php';</script>";
    }
    include '../loading_animation.php';

    
    //calendar ini
    $occupied = 'Occupied';
    $Done = 'Done';
    $query = "SELECT meeting_title, start_date, end_date, start_time, end_time, fullname, status FROM `booking` WHERE `status` = '$occupied' OR `status` = '$Done' ORDER BY start_time ASC";
    $result = mysqli_query($conn2, $query);

    $events = [];
    while($row = mysqli_fetch_assoc($result)) {
        $start = $row['start_date'] . 'T' . date("H:i:s", strtotime($row['start_time']));
        $end = $row['end_date'] . 'T' . date("H:i:s", strtotime($row['end_time']));
        $timeRange = date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time']));
        $start_date = $row['start_date'];
        $start_date = $row['start_date'];
        $status = $row['status'];
        /*
       $status = " ";
       if($row['status'] === 'Cancelled'){
        $status = "Cancelled";
       }elseif($row['status'] === "Done" || $start_date <= $datetoday && )
*/
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

 


      <div class="sidebar close">
        <div class="logo-details">
          <img src="../assets/images/fs_logo.jpg"  alt="">
          <span class="logo_name"></span>
        </div>
        <ul class="nav-links">
          <li>
            <a href="index.php" class="active">
              <i class="bx bx-grid-alt"></i>
              <span class="link_name">Dashboard</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="index.php">Dashboard</a></li>
            </ul>
          </li>
          <li>
            <a href="floors.php">
              <i class='bx bx-building'></i>
              <span class="link_name">Floors</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="floors.php">Floors</a></li>
            </ul>
          </li>
          <li>
            <a href="rooms.php">
              <i class="bx bx-door-open" ></i>
              <span class="link_name">Rooms</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="rooms.php">Rooms</a></li>
            </ul>
          </li>
          <li>
            <a href="history.php">
              <i class="bx bx-history"></i>
              <span class="link_name">History</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="history.php">History</a></li>
            </ul>
          </li>
          <li>
            <a href="reservations.php">
              <i class='bx bx-calendar'></i>
              <span class="link_name">Reservations</span>
            </a>
            <ul class="sub-menu blank">
              <li><a class="link_name" href="reservations.php">Reservations</a></li>
            </ul>
          </li>
          <li>
            <div class="profile-details">
              <div class="profile-content"><i class="bx bx-user-circle fs-2"></i></div>
              <div class="name-job">
                <div class="job"><?php echo strlen($fullname) > 18 ? substr($fullname, 0, 18) . '...' : $fullname; ?></div>
                <div class="job">Admin</div>
              </div>
              <a href="logout.php?location_back=index.php"><i class="bx bx-log-out"></i></a>
            </div>
          </li>
        </ul>
      </div>
      
      <div class="main_content">
        <div class="header-content">
          <span class="menu_toggle"><i class="bx bx-menu-alt-left"></i></span>
          <h3>Good Day <?php echo $firstname; ?>!</h3>
        </div>
        <section class="dashboard_admin_section">
          <div class="container">
            <h2 class="text_header">Dashboard </h2>
            <div class="inner_con">


            <!--room box ini pogi-->
              <div class="row">
                <div class="col-lg-12 mb-3 left_third_row">
                  <div class="box" onclick="location.href='rooms.php'">
                    <img src="../assets/images/meeting_icon.png" alt="Logo">
                    <div class="details">
                      <?php
                         $count_rooms= $conn2->query("SELECT COUNT(*) AS count FROM rooms")->fetch_assoc()['count'];
                      ?>
                      <h2 class="fw-bold text-dark"><?php echo $count_rooms; ?></h2>
                      <p class="text-secondary">Rooms</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4 mb-3 left_first_row">
                  <div class="box" onclick="location.href='rooms.php?filter=available'">
                    <img src="../assets/images/availble_calendar_icon.png" alt="Logo">
                    <div class="details">
                  <?php
                    $sql = "SELECT COUNT(*) as total_available FROM rooms 
                            WHERE room_id NOT IN (
                                SELECT room_id FROM booking 
                                WHERE start_date = ? 
                                AND status = 'Occupied' 
                                AND ? BETWEEN start_time AND end_time
                            )";

                    $stmt = $conn2->prepare($sql);
                    $stmt->bind_param("ss", $datetoday, $timetoday2);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $available = 0;

                    if($row = $result->fetch_assoc()){
                        $available = $row['total_available'];
                    }
                    ?>
                      <h2 class="fw-bold "><?php echo $available; ?></h2>
                      <p class="text-secondary">Available Today</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 mb-3 right_first_row">
                  <div class="box" onclick="location.href='rooms.php?filter=partially_occupied'">
                      <img src="../assets/images/partially_room_icon.png" alt="Logo">
                      <div class="details">
                            <div class="details">
                                      <?php
                        $end_time_limit = "23:00:00";
                        $status = 'Occupied';

                        $sql = "SELECT COUNT(*) as total_partially FROM rooms 
                                WHERE room_id IN (
                                    SELECT room_id FROM booking 
                                    WHERE start_date = ? 
                                    AND status = ? 
                                    AND end_time <= ?
                                )";

                        $stmt = $conn2->prepare($sql);

                       
                        $stmt->bind_param("sss", $datetoday, $status, $end_time_limit);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $total_partially = 0; 
                        if($row = $result->fetch_assoc()){
                            $total_partially = $row['total_partially'];
                        }
                        ?>

                        <h2 class="fw-bold "><?php echo $total_partially; ?></h2>
                        <p class="text-secondary">Partially Occupied Today</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 mb-3 middle_first_row">
                  <div class="box" onclick="location.href='rooms.php?filter=fully_occupied'">
                      <img src="../assets/images/fullybooked_calendar_icon.png" alt="Logo">
                      <div class="details">
                            <div class="details">
                              <?php
                                  $end_time_limit = "23:00:00";
                                  $status = 'Occupied';

                                  $sql = "SELECT COUNT(*) as total_occupied FROM rooms 
                                          WHERE room_id IN (
                                              SELECT room_id FROM booking 
                                              WHERE start_date = ? 
                                              AND status = ? 
                                              AND end_time >= ?
                                          )";

                                  $stmt = $conn2->prepare($sql);

                               
                                  $stmt->bind_param("sss", $datetoday, $status, $end_time_limit);
                                  $stmt->execute();
                                  $result = $stmt->get_result();

                                  $total_occupied = 0; 
                                  if($row = $result->fetch_assoc()){
                                      $total_occupied = $row['total_occupied'];
                                  }
                                  ?>

                        <h2 class="fw-bold text-danger"><?php echo $total_occupied; ?></h2>
                        <p class="text-secondary">Fully Occupied Today</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-6 mb-3 second_row_first" >
                  <div class="box" onclick="location.href='reservations.php?filter=ongoing'">
                    <img src="../assets/images/ongoing _booked_icons.png" alt="">
                    <div class="details">
                      <?php
                        $stmt = $conn2->prepare("SELECT COUNT(*) AS total_ongoing FROM booking WHERE start_time <= ? AND end_time >= ? AND start_date = ?");

                        $stmt->bind_param("sss", $timetoday2, $timetoday2, $datetoday);
                        $stmt->execute();

                        $result = $stmt->get_result();
                        $ongoing_count = 0;

                        if ($row = $result->fetch_assoc()) {
                            $ongoing_count = $row['total_ongoing'];
                        }
                        ?>
                      <h2 class="fw-bold text-success"><?php echo $ongoing_count; ?></h2>
                      <p class="text-secondary">Ongoing Booked Today</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3 second_row_second ">
                  <div class="box" onclick="location.href='reservations.php?filter=upcoming'">
                    <img src="../assets/images/upcoming_booked_icon.png" alt="">
                    <div class="details">
                        <?php
                        $stmt = $conn2->prepare("SELECT COUNT(*) AS total_upcoming FROM booking WHERE start_time > ?  AND start_date = ?");

                        $stmt->bind_param("ss", $timetoday2, $datetoday);
                        $stmt->execute();

                        $result = $stmt->get_result();
                        $ongoing_count = 0;

                        if ($row = $result->fetch_assoc()) {
                            $upcoming_count = $row['total_upcoming'];
                        }
                        ?>
                      <h2 class="fw-bold text-primary"><?php echo $upcoming_count; ?></h2>
                      <p class="text-secondary">Upcoming Booked Today </p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3 left_first_row">
                  <div class="box" onclick="location.href='reservations.php?filter=done'">
                      <img src="../assets/images/calendar_check_icon.png" alt="">
                      <div class="details">
                          <?php
                        $stmt = $conn2->prepare("SELECT COUNT(*) AS total_done FROM booking WHERE end_time < ?  AND start_date = ?");

                        $stmt->bind_param("ss", $timetoday2, $datetoday);
                        $stmt->execute();

                        $result = $stmt->get_result();
                        $ongoing_count = 0;

                        if ($row = $result->fetch_assoc()) {
                            $done_count = $row['total_done'];
                        }
                        ?>
                        <h2 class="fw-bold text-dark"><?php echo $done_count; ?></h2>
                        <p class="text-secondary">Done Booked Today</p>
                      </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3 second_row_fourt" >
                  <div class="box" onclick="location.href='reservations.php?filter=cancelled'">
                    <img src="../assets/images/cancel-event_cabcelled.png" alt="">
                    <div class="details">
                         <?php
                         $status = 'Cancelled';
                        $stmt = $conn2->prepare("SELECT COUNT(*) AS total_cancelled FROM booking WHERE status = ?  AND start_date = ?");

                        $stmt->bind_param("ss", $status, $datetoday);
                        $stmt->execute();

                        $result = $stmt->get_result();
                        $ongoing_count = 0;

                        if ($row = $result->fetch_assoc()) {
                            $cancelled_count = $row['total_cancelled'];
                        }
                        ?>
                      <h2 class="fw-bold text-danger"><?php echo $cancelled_count; ?></h2>
                      <p class="text-secondary">Total Cancelled</p>
                    </div>
                  </div>
                </div>
              </div>

          </div>
        </section>
        <section class="graph_admin_section">
          <div class="container">
            <div class="inner_graph">
              <div id="calendar-container" >
                  <div id="calendar"></div>
              </div>
            </div>
          </div>
        </section>
      </div>

    
 


  <script>
    document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');
    const modalEl = document.getElementById('modal_system');
    const bookingData = <?php echo $eventsJson; ?>;

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


<script src="../assets/js/chart.js"></script>
<script src="../assets/js/cool_alert.js"></script>
<script src="../assets/js/box_icons.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/boostrap.js"></script>
<script src="../assets/js/calendar.io.js"></script>
<?php include '../chart.php' ?>
</body>
</html>
