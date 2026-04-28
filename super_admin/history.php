

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
    <title>History</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar_superadmin.css">
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
                    <a href="accounts.php" class="menu-link ">
                    <span class="material-symbols-rounded"><i class='bx bx-user-circle  '></i></span>
                    <span class="menu-label ">Accounts</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="history.php" class="menu-link active">
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
                    <a href="logout.php?location_back=history.php" class="menu-link">
                        <span class="material-symbols-rounded "><i class='bx bx-log-out fs-4'></i></span>
                        <span class="menu-label">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Site main content -->
    <div class="main-content">
        <section class="dashboard_superadmin_section">
            <div class="container">
                <h2 class="page-title">History </h2>
                <h1>history</h1>
            </div>
        </section>
    </div>
</div>


    <script src="../assets/js/chart.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <?php include '../admin/chart.php'; ?>  
  </body>
</html>
