
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

    if(isset($_GET['filter'])){
        $filter = $_GET['filter']; // e.g., "ongoing"
        echo '<script>
          document.addEventListener("DOMContentLoaded", function(){
              const filterOptions = document.querySelector(".filter_options");
              if(filterOptions) {
      
             
                  const checkbox = document.getElementById("'.$filter.'");
                  if(checkbox) {
                      checkbox.checked = true;
       
                      $(checkbox).trigger("change");
                  }
              }
          });
        </script>';
    }
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
               <div class="card p-2 mb-3 ">
                   <div class="upper_search p-0">
                      <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping"><i class='bx bx-search'></i></span>
                        <input type="search" id="input_reservation" class="form-control" placeholder="Search Floor Name or Date">
                      </div>
                      <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping"><i class="bx bx-calendar"></i></span>
                        <input type="date" id="date_reservation" class="form-control" placeholder="Search Floor Name or Date">
                      </div>
                  </div>
                  <div class="filter_options mt-3">
                    <ul>
                      <li class='filter-item'><input type="checkbox" ' class='hidden-checkbox' id="ongoing">  <label for="ongoing" class='clickable-label'> <i class='bx bx-calendar-event'></i> On&nbsp;Going</label></li>
                      <li class='filter-item'><input type="checkbox" ' class='hidden-checkbox' id="upcoming"> <label for="upcoming" class='clickable-label'><i class='bx bx-time-five'></i> Upcoming</label></li>
                      <li class='filter-item'><input type="checkbox" ' class='hidden-checkbox' id="done"> <label for="done" class='clickable-label'><i class='bx bx-check-circle'></i> Done</label></li>
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


    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/calendar.io.js"></script>
    <?php include '../alert.php'; ?>
</body>
</html>


