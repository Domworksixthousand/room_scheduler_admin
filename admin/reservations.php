


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
              <div class="upper_search">
                  <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i class='bx bx-search'></i></span>
                    <input type="search" id="input_reservation" class="form-control" placeholder="Search Floor Name or Date">
                </div>
                <div class="button_dec">
                  <a href="reservation_add.php" class="btn btn_add "> Add <i class="bx bx-plus-circle  fs-5"></i></a>
                  <button type="button" id="filter_btn" class="btn btn_add">Filter <i class="bx bx-slider fs-5"></i></button>
                </div>
              </div>
              <div class="filter_options">
                <div class="mb-4">
                  <h6 class="fw-bold mb-3">Floors</h6>
                  <ul class="d-flex gap-3 p-0">
                    <?php
                    $floor_sql = "SELECT * FROM floors";
                    $floor_result = $conn2->query($floor_sql);
                    if ($floor_result->num_rows > 0) {
                      while ($floor_row = $floor_result->fetch_assoc()) {
            
                    echo "<li class='filter-item'> 
                          <input type='checkbox' class='hidden-checkbox' id='floor_" . $floor_row['floor_id'] . "'> 
                          <label class='clickable-label' for='floor_" . $floor_row['floor_id'] . "'>
                              <i class='bx bx-layer'></i> " . $floor_row['floor_name'] . "
                          </label>
                        </li>";
                      
                      }
                    }
                    ?>
                  </ul>
                </div>
                <div class="mb-3">
                  <h6 class="fw-bold mb-3">Availability</h6>
                  <ul class="d-flex gap-3 p-0">
                    <li class='filter-item'><input type="checkbox" ' class='hidden-checkbox' id="available">  <label for="available" class='clickable-label'> <i class='bx bx-check-circle'></i> Available</label></li>
                    <li class='filter-item'><input type="checkbox" ' class='hidden-checkbox' id="partially_occupied"> <label for="partially_occupied" class='clickable-label'><i class='bx bx-time-five'></i> Partially Occupied</label></li>
                    <li class='filter-item'><input type="checkbox" ' class='hidden-checkbox' id="fully_occupied"> <label for="fully_occupied" class='clickable-label'><i class='bx bx-x-circle'></i> Fully Occupied</label></li>
                    <li class='filter-item'><input type="checkbox" ' class='hidden-checkbox' id="cancelled"> <label for="cancelled" class='clickable-label'><i class='bx bx-block'></i> Cancelled</label></li>
                  </ul>
                </div>
               </div>
              <div class="row" id="reservation_body">
                <!--data-->
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

    <style>
      
 .card{
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease;
    border-radius: 13px;
}

.card:hover{
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
}

 .card img{
    height: 200px;
    object-fit: cover;
    border-top-left-radius: 13px;
    border-top-right-radius: 13px;
    transition-duration: 300ms;
    transition-timing-function: ease-in-out;
}

 .card img:hover{
    transform: scale(1.1);
}

 .floor_name{
  font-size: 12px;
}

 .buttton_actions {
  display: flex;
  gap:10px;
  position: absolute;
  top: 50px;
  left: -50px;
  color: #fff;
  padding: 5px 10px;
  border-radius: 5px;
  display: flex;
  flex-direction: column;
  gap: 5px;
  transition: all 0.3s ease;
}

 .buttton_actions a {
    transition: all 0.3s ease;
    opacity: 0; 
    transform: translateX(-20px);
}

 .card:hover .buttton_actions {
    top: 50px;
    left: 0px;
}

 .card:hover .buttton_actions a {
    opacity: 1;
    transform: translateX(0);
}

 .card:hover .buttton_actions a:nth-child(1) {
    transition-delay: 0.1s;
}

 .card:hover .buttton_actions a:nth-child(2) {
    transition-delay: 0.2s;
}

 .card:hover .buttton_actions a:nth-child(3) {
    transition-delay: 0.3s;
}

 .card .btn_reserve{
  font-weight:900;
  padding:10px;
  border-radius: 10px;
  color:white;
  transition-duration: 300ms;
  transition-timing-function: ease-in-out;
 background: #0F1595;
}

 .card .btn_reserve:hover{
 background: #141cc3;
}

.room_admin_section .floor_name {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: rgba(0, 0, 0, 0.6);
  color: #fff;
  padding: 5px 10px;
  border-radius: 5px;
}
    </style>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/script.js"></script>
    <?php include '../alert.php'; ?>
</body>
</html>


