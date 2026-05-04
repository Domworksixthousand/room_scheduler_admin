


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reservations</title>
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
  ?>



   <div class="sidebar close">
      <div class="logo-details">
        <img src="../assets/images/fs_logo.jpg" alt="Logo">
        <span class="logo_name"></span>
      </div>
      <ul class="nav-links">
        <li><a href="index.php">
          <i class="bx bx-grid-alt"></i><span class="link_name">Dashboard</span></a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="index.php">Dashboard</a></li>
          </ul>
        </li>
        <li>
          <a href="floors.php" ><i class='bx bx-building'></i><span class="link_name">Floors</span></a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="floors.php">Floors</a></li>
          </ul>
        </li>
        <li>
          <a href="rooms.php" ><i class="bx bx-door-open"></i><span class="link_name">Rooms</span></a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="rooms.php">Rooms</a></li>
          </ul>
        </li>
        <li>
          <a href="history.php" ><i class="bx bx-history"></i><span class="link_name">History</span></a>
          <ul class="sub-menu blank">
            <li><a class="link_name" href="history.php">History</a></li>
          </ul>
        </li>
        <li>
          <a href="reservations.php" class="active"><i class='bx bx-calendar'></i><span class="link_name">Reservations</span></a>
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
            <a href="logout.php?location_back=reservations.php"><i class="bx bx-log-out"></i></a>
          </div>
        </li>
      </ul>
    </div>


    <div class="main_content">
        <div class="header-content">
          <span class="menu_toggle"><i class="bx bx-menu-alt-left"></i></span>
          <h3>Good Day <?php echo $firstname; ?>!</h3>
        </div>
        <section class="reservation_admin_section">
          <div class="container">
            <h2 class="text_header">Reservations </h2>
            <div class="inner_con">
              <div class="d-flex gap-2 mb-4">
                <input type="search" id="input_reservation" class="form-control" placeholder="Search Floor Name or Date">
                <a href="reservation_add.php" class="btn btn_add "> Add <i class="bx bx-plus-circle  fs-5"></i></a>
              </div>  
              <div class="overflow-auto">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Meeting Title</th>
                      <th>Employee</th>
                      <th>Room Name</th>
                      <th>Floor Name</th>
                      <th>Status</th>
                      <th>Cancelled At</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="reservation_body">
                    <!-- floors -->
                  </tbody>
                </table>
              </div>
              <div class="d-flex justify-content-end align-items-end">
                <nav aria-label="Page navigation">
                  <ul class="pagination" id="pagination_reservation">
                    <!--data-->
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </section>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/script.js"></script>
    <?php include '../alert.php'; ?>
</body>
</html>


