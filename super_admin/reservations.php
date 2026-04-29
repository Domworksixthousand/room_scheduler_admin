
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
    <title>Reservations</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
    <link rel="stylesheet" href="../assets/css/sidebar_superadmin.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
  </head>
  <body>

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
            <img src="../assets/images/fs_logo.jpg" alt="CodingNepal" class="header-logo" />
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
                    <a href="history.php" class="menu-link">
                    <span class="material-symbols-rounded "><i class='bx bx-history fs-4'></i></span>
                    <span class="menu-label">History</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="reservations.php" class="menu-link active">
                    <span class="material-symbols-rounded "><i class='bx bx-calendar fs-4'></i></span>
                    <span class="menu-label">Reservations</span>
                    </a>
                </li>
                <li class="menu-item" >
                    <a href="logout.php?location_back=reservations.php" class="menu-link">
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
                <h2 class="page-title"> Reservations</h2>
            </div>
        </div>
        <section class="reservation_superadmin_section">
            <div class="container">
                <div class="inner_con">
                <div class="d-flex gap-2 mb-4">
                    <input type="search" id="superadmin_input_reservation" class="form-control" placeholder="Search Floor Name or Date">
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
                    <tbody id="superadmin_reservation_body">
                        <!-- floors -->
                    </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end align-items-end">
                    <nav aria-label="Page navigation">
                    <ul class="pagination" id="superadmin_pagination_reservation">
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
    <script src="../assets/js/chart.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <?php include '../alert.php'; ?>
  </body>
</html>
