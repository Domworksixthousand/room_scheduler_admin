
<?php

ob_start();
 
include '../config.php';
 
// Go to current user page role
if (isset($role)) {
    if ($role === "2") {
        echo "<script>location.href='../admin/index.php';</script>";
        exit;
    } elseif ($role === "3") {
        echo "<script>location.href='../user/index.php';</script>";
        exit;
    }
} else {
    echo "<script>location.href='../index.php';</script>";
    exit;
}
 
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {

    ini_set('display_errors', '0');
    error_reporting(0);
 
    while (ob_get_level()) { ob_end_clean(); }
    header('Content-Type: application/json');
 
    $db = isset($conn2) ? $conn2 : $conn;
    $floor_status = "Active";
 
    $items_per_page = 8;
    $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
    $offset = ($current_page - 1) * $items_per_page;
    $search_param = "%" . $search_query . "%";
 
    $no_data_html = "<tr>
            <td colspan='3' class='text-center'>
                <div class='d-flex flex-column text-muted justify-content-center align-items-center py-3'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='35' height='35' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-hotel-icon lucide-hotel mb-2'><path d='M10 22v-6.57'/><path d='M12 11h.01'/><path d='M12 7h.01'/><path d='M14 15.43V22'/><path d='M15 16a5 5 0 0 0-6 0'/><path d='M16 11h.01'/><path d='M16 7h.01'/><path d='M8 11h.01'/><path d='M8 7h.01'/><rect x='4' y='2' width='16' height='20' rx='2'/></svg>
                    <span>No Data Found</span>
                </div>
            </td>
        </tr>";
 

    $table_html = $no_data_html;
    $pagination_html = "";
 
    try {
        if (!$db) {
            throw new Exception('no_db');
        }
 
        $count_sql = "SELECT COUNT(*) FROM `floors` WHERE (`floor_name` LIKE ? OR CAST(`rooms_number` AS CHAR) LIKE ?) AND `floor_status` = ?";
        $stmt_count = $db->prepare($count_sql);
        $total_records = 0;
 
        if ($stmt_count) {
            $stmt_count->bind_param("sss", $search_param, $search_param, $floor_status);
            $stmt_count->execute();
            $res = $stmt_count->get_result();
            if ($res) {
                $count_row = $res->fetch_row();
                $total_records = (int)($count_row[0] ?? 0);
            }
            $stmt_count->close();
        }
 
        $total_pages = ($total_records > 0) ? (int)ceil($total_records / $items_per_page) : 1;
 
        $data_sql = "SELECT * FROM `floors` WHERE (`floor_name` LIKE ? OR CAST(`rooms_number` AS CHAR) LIKE ?) AND `floor_status` = ? LIMIT ? OFFSET ?";
        $stmt_data = $db->prepare($data_sql);
 
        $result = false;
        if ($stmt_data) {
            $stmt_data->bind_param("sssii", $search_param, $search_param, $floor_status, $items_per_page, $offset);
            $stmt_data->execute();
            $result = $stmt_data->get_result();
        }
 
        if ($result && $result->num_rows > 0) {
            $rows_html = "";
            while ($row_floor = $result->fetch_assoc()) {
                $floor_name = htmlspecialchars($row_floor['floor_name'] ?? '');
                $rooms_number = htmlspecialchars($row_floor['rooms_number'] ?? '');
                $floor_id = htmlspecialchars($row_floor['floor_id'] ?? '');
 
                $rows_html .= "
                    <tr>
                        <td>$floor_name</td>
                        <td>$rooms_number</td>
                        <td>
                          <a href='floor_delete.php?floor_id=$floor_id' class='btn btn-danger btn-sm'>
                            <i class='bx bx-trash'></i> Delete
                          </a>
                          <a href='floor_update.php?floor_id=$floor_id' class='btn btn-primary btn-sm'>
                            <i class='bx bx-edit'></i> Update
                          </a>
                        </td>
                    </tr>";
            }
            $table_html = $rows_html;
        } else {
           
            $table_html = $no_data_html;
        }
 
        if ($total_pages > 1) {
            $pagination_html = "";
            $prev_disabled = ($current_page <= 1) ? 'disabled' : '';
            $prev_val = max(1, $current_page - 1);
            $pagination_html .= "<li class='page-item $prev_disabled'><a class='page-link page-link-ajax' href='#' data-page='$prev_val'>Previous</a></li>";
 
            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($i == $current_page) ? 'active' : '';
                $pagination_html .= "<li class='page-item $active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
            }
 
            $next_disabled = ($current_page >= $total_pages) ? 'disabled' : '';
            $next_val = min($total_pages, $current_page + 1);
            $pagination_html .= "<li class='page-item $next_disabled'><a class='page-link page-link-ajax' href='#' data-page='$next_val'>Next</a></li>";
        }
    } catch (Throwable $e) {

        $table_html = $no_data_html;
        $pagination_html = "";
    }
 
    echo json_encode([
        'table_html' => $table_html,
        'pagination_html' => $pagination_html
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
 
if (ob_get_level()) { ob_end_flush(); }
include '../../mysidebar/Sidebar_Portal.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Floors</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="../assets/css/admin_sidebar.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
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
   
 
    <!-- Main Content -->
    <div class="main_content">
      <?php include 'fab.php'; ?>
      <section class="floor_superadmin_section">
        <div class="container">
          <h2 class="text-header">Floors </h2>
          <div class="inner_con">
              <div class="card p-2 mb-3">
                  <div class="d-flex gap-2">
                    <div class="input-group flex-nowrap">
                      <span class="input-group-text" id="addon-wrapping"><i class='bx bx-search'></i></span>
                      <input type="search" class="form-control" id="input_floor" value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>" placeholder="Search Floor Name or Number of Floors" aria-label="Search" aria-describedby="addon-wrapping">
                    </div>
                    <a href="floor_add.php" class="btn btn_add"> Add <i class="bx bx-plus-circle fs-5"></i></a>
                </div>
              </div>
              <div class="overflow-auto">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Floor Name</th>
                      <th>Room Numbers</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="mybody_floor">
                    <!-- Populated dynamically via AJAX -->
                  </tbody>
                </table>
              </div>
              <div class="d-flex justify-content-end align-items-end">
                <nav aria-label="Page navigation">
                  <ul class="pagination pagination-sm mb-0" id="pagination_links_floor">
                    <!-- Populated dynamically via AJAX -->
                  </ul>
                </nav>
              </div>
          </div>
        </div>
      </section>
    </div>
 
    <!-- Scripts -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/walkthrough_tour.js?v=9"></script>;
    <?php include '../alert.php'; ?>

 
</body>
</html>