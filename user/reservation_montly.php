<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reservations - Monthly View</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
    <script src="../assets/js/jquery.js"></script>
  </head>
  <body>

  <?php
    include '../config.php';
    // Redirect based on user role
    if($role === "1"){
        echo "<script>location.href='../super_admin/index.php';</script>";
    }elseif($role === "2"){
        echo "<script>location.href='../admin/index.php';</script>";
    }

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
    include '../../mysidebar/Sidebar_Portal.php';
?>

    <header>
      <div class="top_bar_nav">
        <div class="container d-flex justify-content-between">
          <img src="../assets/images/FsLogoW.png" class="fs-icon">
          <div class="w-100 text-end">
            <button id="BtnBurger" class="btn btn-ghost">☰</button>
          </div>
        </div>
      </div>
    </header>

    <div class="main_content">
        <?php include 'fab.php'; ?>
        <section class="reservation_admin_section">
            <div class="container">
              <h2 class="text_header">Monthly Reservations</h2>
              <div class="card p-2 mb-3 ">
                   <div class="upper_search p-0">
                      <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping"><i class='bx bx-search'></i></span>
                       <input type="search" id="input_reservation_montly" class="form-control" placeholder="Search Fullname">
                      </div>
                      <div class="input-group flex-nowrap gap-1">
                          <span class="input-group-text p-0 border-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Weekly">
                            <button type="button" id="weekly_cal" class="btn btn-secondary date-mode-btn w-100" data-mode="week" onclick="location.href='reservations.php'"><i class="bx bx-calendar"></i>W</button>
                          </span>
                          <span class="input-group-text p-0 border-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Daily">
                            <button type="button" id="daily_cal" class="btn btn-secondary date-mode-btn w-100" data-mode="date" onclick="location.href='reservation_daily.php'"><i class="bx bx-calendar"></i>D</button>
                          </span>
                          <span class="input-group-text p-0 border-0" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Monthly">
                            <button type="button" id="daily_montly" class="btn btn-secondary date-mode-btn w-100 my_color" data-mode="month" onclick="location.href='reservation_montly.php'"><i class="bx bx-calendar"></i>M</button>
                          </span>
                          <input type="month" id="reservation_montly" class="form-control" placeholder="Select Month">
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
              <div class="row d-flex" id="reservation_body_montly">
                <!-- Data loaded via AJAX -->
              </div>
              <div class="d-flex justify-content-end align-items-end">
              <nav aria-label="Page navigation">
                <ul class="pagination" id="pagination_reservations_montly">
                  <!-- Pagination loaded via AJAX -->
                </ul>
              </nav>
            </div>
            </div>
        </section>
    </div>

    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/calendar.io.js"></script>
    <script src="../assets/js/walkthrough_tour.js?v=9"></script>
    <?php include '../alert.php'; ?>
</body>
</html>