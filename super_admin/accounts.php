
<?php include '../config.php'; ?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accounts</title>
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
                    <a href="index.php" class="menu-link ">
                        <span class="material-symbols-rounded "><img src="../assets/images/dashboard_icon.png" alt=""></span>
                        <span class="menu-label">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="accounts.php" class="menu-link active">
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
        <section class="accounts_superadmin_section">
           <div class="container">
            <h2 class="page-title">Accounts</h2>
            <div class="d-flex gap-2">
                <input type="search" class="form-control" id="myInput" placeholder="Search">
                <a href="account_add.php" class="btn btn_add"><img src="../assets/images/add_user_icon.png" alt=""> Add</a>
            </div>
            <div class="inner_con">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lastname</th>
                            <th>Firstname</th>
                            <th>Middlename</th>
                            <th>Suffix</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php
                            $get_accounts1 = $conn2->prepare("SELECT * FROM `accounts`");
                            $get_accounts1->execute();
                            $result_get1 = $get_accounts1->get_result();
                            if($result_get1->num_rows>0){
                                $row_get1 = $result_get1->fetch_assoc();
                                $employee_id = htmlspecialchars($row_get1['employee_id'] ?? '');
                                $id = htmlspecialchars($row_get1['id'] ?? '');
                            
                            $get_accounts2 = $conn1->prepare("SELECT * FROM `employee` WHERE `employee_id` = ?");
                            $get_accounts2->bind_param("s",$employee_id);
                            $get_accounts2->execute();
                            $result_get2 = $get_accounts2->get_result();
                            if($result_get2->num_rows>0){
                                $row_get2 = $result_get2->fetch_assoc();
                                $firstname = htmlspecialchars($row_get2['FirstName'] ?? '');
                                $lastname = htmlspecialchars($row_get2['LastName'] ?? '');
                                $middlename = htmlspecialchars($row_get2['MiddleName'] ?? '');
                                $suffix = htmlspecialchars($row_get2['Suffix_ID'] ?? '');
                                $employee_id = htmlspecialchars($row_get2['Employee_ID'] ?? '');
                     
                           
                        echo"
                        <tr>
                            <td>$lastname</td>
                            <td>$firstname</td>
                            <td>$middlename</td>
                            <td>$suffix</td>
                            <td><a href='account_delete.php?data=$id' class='btn btn_delete '><img src='../assets/images/delete_icon.png' alt=''> Delete</a></td>
                        </tr>";
                     
                         }
                        }else{
                            echo "<tr><td colspan='5'><p class='text-center'>No Accounts Found</p></td></tr>";
                        }
                        ?>
                        <tr id="nofound" class="d-none"><td colspan='5'><p class='text-center'>No Accounts Found</p></td></tr>
                    </tbody>
                </table>
            </div>
           </div>
        </section>
    </div>
</div>

<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/cool_alert.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/boostrap.js"></script>
<?php include '../alert.php'; ?>
</body>
</html>
