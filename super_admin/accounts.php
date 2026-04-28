
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
    <title>Accounts</title>
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
                    <a href="accounts.php" class="menu-link active">
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
                    <a href="reservations.php" class="menu-link">
                    <span class="material-symbols-rounded "><i class='bx bx-calendar fs-4'></i></span>
                    <span class="menu-label">Reservations</span>
                    </a>
                </li>
                <li class="menu-item" >
                    <a href="logout.php?location_back=accounts.php" class="menu-link">
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
                <h2 class="page-title"> Accounts</h2>
            </div>
        </div>
        <section class="accounts_superadmin_section">
           <div class="container">
            <div class="d-flex gap-2">
                <input type="search" class="form-control" id="myInput" placeholder="Search">
                <a href="account_add.php" class="btn btn_add"> Add <img src="../assets/images/add_user_icon.png" alt=""></a>
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
                    <tbody id="accounts_data">
                      
                      
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end align-items-end">
              <nav aria-label="Page navigation">
                <ul class="pagination" id="account_pagination">
                  <!--data-->
                </ul>
              </nav>
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
<script>
    

//admin/history.php
 $(document).ready(function() {
    let searchTimer;
    let currentPage = 1;

    const fetchHistory = (page = 1) => {
        const searchTerm = $('#myInput').val();
        currentPage = page;

        $.ajax({
            url: "account_fetch.php",
            method: "GET",
            data: { search: searchTerm, page: page },
            success: function(response) {

                const [tableData, paginationData] = response.split("|||");
                
                $('#accounts_data').hide().html(tableData).fadeIn(200);
                $('#account_pagination').html(paginationData);
            },
            error: function() {
                $('#accounts_data').html("<tr><td colspan='5' class='text-center text-danger'>Connection error.</td></tr>");
            }
        });
    };

    // Trigger search on typing (with 300ms delay to save server resources)
    $(document).on('keyup', '#myInput', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchHistory(1), 300);
    });

    // Handle Pagination Clicks using Delegation
    $(document).on('click', '.page-link-ajax', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        fetchHistory(page);
    });

    // Initial Load
    fetchHistory();

});

</script>