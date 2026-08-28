
<?php
  include '../config.php'; 
    //go to current user page  role
     
    if (isset($role)) {
        if ($role  === "2") {
            echo "<script>location.href='../admin/index.php';</script>";
            exit;
        }elseif($role  === "3") {
            echo "<script>location.href='../user/index.php';</script>";
            exit;
        } 
          
    }else{
        echo "<script>location.href='../index.php';</script>";
        exit;
    }
 include '../../mysidebar/Sidebar_Portal.php';
 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accounts</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
    <script src="../assets/js/jquery.js"></script>
  </head>
  <body>

    <header>
      <div class="top_bar_nav">
        <div class="container d-flex justify-content-between">
          <img src="../assets/images/FsLogoW.png" class="fs-icon">
        <div class="w-100 text-end">
        <button id="BtnBurger" class="btn btn-ghost ">
          ☰
        </button>
        </div>
        </div>
      </div>
    </header>
   


   <div class="main_content">
        <?php include 'fab.php'; ?>
        <section class="accounts_superadmin_section">
          <div class="container">
            <h2 class="text-header">Accounts </h2>
            <div class="inner_con ">
              <div class="card  p-2">
                 <div class="d-flex gap-2">
                    <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i class='bx bx-search'></i></span>
                        <input type="search" class="form-control" id="myInput" placeholder="Search">
                    </div>
                    <a href="account_add.php" class="btn btn_add"> Add <img src="../assets/images/add_user_icon.png" loading="lazy" alt=""></a>
                </div>
           </div>
            <div class="overflow-auto pt-3">
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
                      
                      <!--account data-->
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
          </div>
        </section>
      </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/walkthrough_tour.js?v=9"></script>;
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