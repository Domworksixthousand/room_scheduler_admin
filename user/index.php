
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rooms</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/css/admin_sidebar.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
  
    <script src="../assets/js/jquery.js"></script>
  </head>
  <body>

<?php
  include '../config.php'; 
  //go to current user page  role
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
?>





    <!--main contetent-->
    <div class="main_content">

      <?php include 'fab.php'; ?>
      <section class="room_admin_section">
        <div class="container">
          <h2 class="text_header">Rooms</h2>
          <div class="inner_con">
              <div class="card p-2 mb-3">
                  <div class="upper_search">
                    <div class="input-group flex-nowrap">
                      <span class="input-group-text" id="addon-wrapping"><i class='bx bx-search'></i></span>
                      <input type="search" class="form-control "  id="input_room"  placeholder="Search rooms, Serial Number, or floors..." aria-label="Username" aria-describedby="addon-wrapping">
                    </div>
                  </div>
                  <div class="filter_options ">
                      <ul>
                        <li class='filter-item'>
                          <input type="checkbox" ' class='hidden-checkbox' id="available"> 
                          <label for="available" class='clickable-label  m-0'> 
                            <i class='bx bx-check-circle'></i> Available
                          </label>
                        </li>
                        <li class='filter-item m-0'><input type="checkbox" ' class='hidden-checkbox' id="partially_occupied">
                          <label for="partially_occupied" class='clickable-label  m-0'>
                            <i class='bx bx-time-five'></i> Partially&nbsp;Occupied</label>
                          </li>
                        <li class='filter-item m-0'><input type="checkbox" ' class='hidden-checkbox' id="fully_occupied"> 
                          <label for="fully_occupied" class='clickable-label  m-0'><i class='bx bx-x-circle'></i> Fully&nbsp;Occupied</label>
                        </li>
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



    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/calendar.io.js"></script>
    <script src="../assets/js/walkthrough_tour.js"></script>;
    <?php include '../alert.php'; ?>
  </body>
</html>
