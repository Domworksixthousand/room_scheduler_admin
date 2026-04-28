

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
          <a href="rooms.php">
            <i class="bx bx-door-open" ></i>
            <span class="link_name">Rooms</span>
          </a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="rooms.php">Rooms</a></li>
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

              <div class="row">
                <div class="col-lg-6 mb-3 left_first_row">
                  <div class="box" onclick="location.href='reservations.php'">
                    <img src="../assets/images/booked_today_icon.png" alt="Logo">
                    <div class="details">
                      <?php
                         $count_booked_today = $conn2->query("SELECT COUNT(*) AS count FROM booking WHERE `start_date` >= '$datetoday' AND `end_date` <= '$datetoday' AND status = 'Occupied'")->fetch_assoc()['count'];
                      ?>
                      <h2 class="fw-bold text-dark"><?php echo $count_booked_today; ?></h2>
                      <p class="text-secondary">Occupied Booked Today</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mb-3 right_first_row">
                 <div class="box" onclick="location.href='reservations.php'">
                    <img src="../assets/images/cancel-event_cabcelled.png" alt="Logo">
                    <div class="details">
                       <?php
                         $count_cancelled_today = $conn2->query("SELECT COUNT(*) AS count FROM booking WHERE `start_date` >= '$datetoday' AND `end_date` <= '$datetoday' AND status = 'Cancelled'")->fetch_assoc()['count'];
                      ?>
                      <h2 class="fw-bold text-dark"><?php echo $count_cancelled_today; ?></h2>
                      <p class="text-secondary">Cancelled Booked Today</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-6 mb-3 second_row_first" >
                  <div class="box" onclick="location.href='reservations.php'">
                    <img src="../assets/images/booking_icon.png" alt="">
                    <div class="details">
                         <?php
                         $total_booked = $conn2->query("SELECT COUNT(*) AS count FROM booking ")->fetch_assoc()['count'];
                      ?>
                      <h2 class="fw-bold text-dark"><?php echo $total_booked; ?></h2>
                      <p class="text-secondary">Total Booked</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3 right_first_row ">
                  <div class="box" onclick="location.href='reservations.php'">
                    <img src="../assets/images/calendar_cancelled_icon.png" alt="">
                    <div class="details">
                         <?php
                         $total_ALL_cancelled = $conn2->query("SELECT COUNT(*) AS count FROM booking WHERE status = 'Cancelled'")->fetch_assoc()['count'];
                      ?>
                      <h2 class="fw-bold text-dark"><?php echo $total_ALL_cancelled; ?></h2>
                      <p class="text-secondary">Total Cancelled</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3 left_first_row">
                  <div class="box" onclick="location.href='reservations.php'">
                      <img src="../assets/images/calendar_check_icon.png" alt="">
                      <div class="details">
                          <?php
                          $total_ALL_occupied = $conn2->query("SELECT COUNT(*) AS count FROM booking WHERE status = 'Occupied'")->fetch_assoc()['count'];
                        ?>
                        <h2 class="fw-bold text-dark"><?php echo $total_ALL_occupied; ?></h2>
                        <p class="text-secondary">Total Occupied</p>
                      </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3 second_row_first" >
                  <div class="box" onclick="location.href='reservations.php'">
                    <img src="../assets/images/calendar_done.png" alt="">
                    <div class="details">
                      <?php
                         $total_booked_done = $conn2->query("SELECT COUNT(*) AS count FROM booking WHERE status = 'Done'")->fetch_assoc()['count'];
                      ?>
                      <h2 class="fw-bold text-dark"><?php echo $total_booked_done; ?></h2>
                      <p class="text-secondary">Total Done</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-3 left_third_row">
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
                <div class="col-lg-6 mb-3 right_third_row">
                 <div class="box" onclick="location.href='floors.php'">
                    <img src="../assets/images/building_icon.png" alt="Logo">
                    <div class="details">
                       <?php
                         $count_floors = $conn2->query("SELECT COUNT(*) AS count FROM floors")->fetch_assoc()['count'];
                      ?>
                      <h2 class="fw-bold text-dark"><?php echo $count_floors; ?></h2>
                      <p class="text-secondary">Floors</p>
                    </div>
                  </div>
                </div>
              </div>

          </div>
        </section>
        <section class="graph_admin_section">
          <div class="container">
            <div class="inner_graph">
              <h3 class="text-center fw-bold mb-5">Daily, Weekly, Montly, Yearly Booked Chart</h3>
              <form action="" method="GET" id="filterForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <select name="view" class="form-control mb-3" onchange="this.form.submit()">
                            <option value="daily" <?php echo ($_GET['view'] ?? '') == 'daily' ? 'selected' : ''; ?>>Daily</option>
                            <option value="weekly" <?php echo ($_GET['view'] ?? '') == 'weekly' ? 'selected' : ''; ?>>Weekly</option>
                            <option value="monthly" <?php echo ($_GET['view'] ?? '') == 'monthly' ? 'selected' : ''; ?>>Monthly</option>
                            <option value="yearly" <?php echo ($_GET['view'] ?? '') == 'yearly' ? 'selected' : ''; ?>>Yearly</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input type="month" name="month" class="form-control" value="<?php echo $_GET['month'] ?? date('Y-m'); ?>" onchange="this.form.submit()">
                    </div>
                </div>
              </form>
              <?php
              
                $view = $_GET['view'] ?? 'daily';
                $month_filter = $_GET['month'] ?? date('Y-m');

                // I-adjust ang SQL base sa filter
                if ($view == 'weekly') {
                    $date_format = "CONCAT('Week ', WEEK(start_date))";
                } elseif ($view == 'monthly') {
                    $date_format = "DATE_FORMAT(start_date, '%Y-%m')";
                } elseif ($view == 'yearly') {
                    $date_format = "DATE_FORMAT(start_date, '%Y')";
                } else { // default is daily
                    $date_format = "DATE_FORMAT(start_date, '%Y-%m-%d')";
                }


                $query = "SELECT $date_format AS label, status, COUNT(*) AS total 
                          FROM booking 
                          WHERE start_date LIKE '$month_filter%' 
                          GROUP BY label, status 
                          ORDER BY label ASC";

                $result = $conn2->query($query)->fetch_all(MYSQLI_ASSOC);


                $labels = [];
                $status_data = ['Done' => [], 'Occupied' => [], 'Cancelled' => []];

                foreach ($result as $row) {
                    if (!in_array($row['label'], $labels)) $labels[] = $row['label'];
                }

                foreach ($labels as $lbl) {
                    foreach (['Done', 'Occupied', 'Cancelled'] as $st) {
                        $val = 0;
                        foreach ($result as $row) {
                            if ($row['label'] == $lbl && $row['status'] == $st) {
                                $val = (int)$row['total'];
                                break;
                            }
                        }
                        $status_data[$st][] = $val;
                    }
                }

                $js_labels = json_encode($labels);
                $js_done = json_encode($status_data['Done']);
                $js_occupied = json_encode($status_data['Occupied']);
                $js_cancelled = json_encode($status_data['Cancelled']);
              ?>
              <div id="chart" class="chart"></div>
            </div>
          </div>
        </section>
    </div>

    
 


<script src="../assets/js/chart.js"></script>
<script src="../assets/js/cool_alert.js"></script>
<script src="../assets/js/box_icons.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/boostrap.js"></script>
<?php include 'chart.php' ?>
</body>
</html>
