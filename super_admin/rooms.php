

<?php
  include '../config.php'; 
  if(!isset($_SESSION['superadmin_login'])){
    echo "<script>location.href='../index.php';</script>";
  }
 ?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
    <link rel="stylesheet" href="../assets/css/sidebar_superadmin.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
  </head>
  <body>
<?php
 include '../loading_animation.php'; 
  if(isset($_GET['filter'])){
        $filter = $_GET['filter']; 
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
<!-- Navbar -->
<nav class="site-nav">
    <button class="sidebar-toggle">
        <span class="material-symbols-rounded"><img src="../assets/images/menus_icon.png" alt=""></span>
    </button>
</nav>

<div class="container-fluid p-0">
    <!-- Sidebar -->
    <aside class="sidebar collapsed">
        <!-- Sidebar header -->
        <div class="sidebar-header">
            <img src="../assets/images/fs_logo.jpg" alt="" class="header-logo" />
            <button class="sidebar-toggle">
            <span class="material-symbols-rounded"><img src="../assets/images/menu_icon.png" alt=""></span>
            </button>
        </div>
        <div class="sidebar-content">
            <!-- Sidebar Menu -->
            <ul class="menu-list">
                <li class="menu-item ">
                    <a href="index.php" class="menu-link ">
                        <span class="material-symbols-rounded"><i class="bx bx-grid-alt "></i></span>
                        <span class="menu-label">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="accounts.php" class="menu-link">
                    <span class="material-symbols-rounded"><i class='bx bx-user-circle  '></i></span>
                    <span class="menu-label ">Accounts</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="rooms.php" class="menu-link active">
                    <span class="material-symbols-rounded"><i class="bx bx-door-open" ></i></span>
                    <span class="menu-label ">Rooms</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="history.php" class="menu-link">
                    <span class="material-symbols-rounded "><i class='bx bx-history fs-4'></i></span> 
                    <span class="menu-label">History</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="reservations.php" class="menu-link">
                    <span class="material-symbols-rounded "><i class='bx bx-calendar fs-4'></i></span>
                    <span class="menu-label">Reservations</span>
                    </a>
                </li>
                  <li class="menu-item" >
                    <a href="logout.php?location_back=index.php" class="menu-link">
                        <span class="material-symbols-rounded "><i class='bx bx-log-out fs-4'></i></span>
                        <span class="menu-label">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Site main content -->
    <div class="main-content">
        <div class="header_title ">
            <div class="container">
                <h2 class="page-title"> Rooms</h2>
            </div>
        </div>
           <section class="room_superadmin_section">
                    <div class="container">
                        <div class="inner_con">
                            <div class="card mb-3 p-2">
                                <div class="upper_search">
                                        <div class="input-group flex-nowrap">
                                            <span class="input-group-text" id="addon-wrapping"><i class='bx bx-search'></i></span>
                                            <input type="search" class="form-control "  id="input_room"  placeholder="Search rooms, serials, or floors..." aria-label="Username" aria-describedby="addon-wrapping">
                                        </div>
                                    </div>
                                    <div class="filter_options ">
                                        <ul>
                                            <li class='filter-item '><input type="checkbox" ' class='hidden-checkbox' id="available">  <label for="available" class='clickable-label  m-0'> <i class='bx bx-check-circle'></i> Available</label></li>
                                            <li class='filter-item m-0'><input type="checkbox" ' class='hidden-checkbox' id="partially_occupied"> <label for="partially_occupied" class='clickable-label  m-0'><i class='bx bx-time-five'></i> Partially&nbsp;Occupied</label></li>
                                            <li class='filter-item m-0'><input type="checkbox" ' class='hidden-checkbox' id="fully_occupied"> <label for="fully_occupied" class='clickable-label  m-0'><i class='bx bx-x-circle'></i> Fully&nbsp;Occupied</label></li>
                                        </ul>
                                    </div>
                                </div>
                            <div class="row" id="room_body">
                                
                                <!-- rooms -->
                            </div>
                            <div class="d-flex justify-content-end align-items-end">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" id="pagination_links">
                                    <!--data-->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>

                 </section>
            </div>
        </div>







    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/calendar.io.js"></script>
</body>
</html>

