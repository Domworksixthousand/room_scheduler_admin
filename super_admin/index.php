
<?php include '../config.php'; ?>


<!DOCTYPE html>
<!-- Coding By CodingNepal - youtube.com/@codingnepal -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
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
                    <a href="index.php" class="menu-link active">
                        <span class="material-symbols-rounded "><img src="../assets/images/dashboard_icon.png" alt=""></span>
                        <span class="menu-label">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="accounts.php" class="menu-link">
                    <span class="material-symbols-rounded "><img src="../assets/images/accounts_icon.png" alt=""></span>
                    <span class="menu-label">Accounts</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="history.php" class="menu-link">
                    <span class="material-symbols-rounded "><img src="../assets/images/history_icon.png" alt=""></span>
                    <span class="menu-label">History</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="reservations.php" class="menu-link">
                    <span class="material-symbols-rounded "><img src="../assets/images/reservation_icon.png" alt=""></span>
                    <span class="menu-label">Reservations</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                    <span class="material-symbols-rounded "><img src="../assets/images/signout_icon.png" alt=""></span>
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
                <h2 class="page-title">Dashboard </h2>
                
            </div>
        </section>
    </div>
</div>



<script src="../assets/js/script.js"></script>
<script src="../assets/js/boostrap.js"></script>
</body>
</html>
