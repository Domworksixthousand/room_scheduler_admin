<?php
include '../config.php';

// Role redirection check
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
    header('Content-Type: application/json');

    $limit = 8;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page);
    $offset = ($page - 1) * $limit;

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $search_param = "%$search%";

    // Fetch Records
    $sql = "SELECT b.*, r.room_name, flo.floor_name 
            FROM `booking` b
            INNER JOIN `rooms` r ON b.room_id = r.room_id
            LEFT JOIN `floors` flo ON r.floor_id = flo.floor_id 
            WHERE (
                r.room_name LIKE ? 
                OR b.fullname LIKE ? 
                OR b.meeting_title LIKE ? 
                OR b.start_date LIKE ? 
                OR b.end_date LIKE ?   
            )
            AND b.end_date < ?
            ORDER BY b.start_date DESC, b.start_time DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn2->prepare($sql);
    $stmt->bind_param("ssssssii", $search_param, $search_param, $search_param, $search_param, $search_param, $datetoday, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $table_html = "";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status_class = ($row['status'] == 'Occupied') ? 'bg-primary' : (($row['status'] == 'Done') ? 'bg-success' : 'bg-danger');
            
            $start_formatted = !empty($row['start_date']) ? date('M d, Y', strtotime($row['start_date'])) : '';
            $start_time = !empty($row['start_time']) ? date('h:i A', strtotime($row['start_time'])) : '';
            $end_time = !empty($row['end_time']) ? date('h:i A', strtotime($row['end_time'])) : '';

            $table_html .= "<tr>
                <td>
                    <p class='small mb-1 text-center'>" . htmlspecialchars($start_formatted) . "</p>
                </td>
                <td><small>" . htmlspecialchars($start_time) . " to " . htmlspecialchars($end_time) . "</small></td>
                <td>" . htmlspecialchars($row['meeting_title'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['fullname'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['room_name'] ?? '') . "</td>
                <td>" . htmlspecialchars($row['floor_name'] ?? '') . "</td>
                <td>" . (!empty($row['cancelled_at']) ? htmlspecialchars($row['cancelled_at']) : 'Not Cancelled') . "</td>
                <td><span class='badge $status_class'>" . htmlspecialchars($row['status'] ?? '') . "</span></td>
            </tr>";
        }
    } else {
        $table_html = "<tr>
            <td colspan='8' class='text-center py-4'>
                <div class='d-flex flex-column text-muted justify-content-center align-items-center'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='lucide lucide-folder-clock'><path d='M16 14v2.2l1.6 1'/><path d='M7 20H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H20a2 2 0 0 1 2 2'/><circle cx='16' cy='16' r='6'/></svg>
                    No results found.
                </div>
            </td>
        </tr>";
    }

    // Fetch Total Rows for Pagination
    $count_sql = "SELECT COUNT(*) as total FROM `booking` b 
                  INNER JOIN `rooms` r ON b.room_id = r.room_id 
                  WHERE (r.room_name LIKE ? OR b.fullname LIKE ? OR b.meeting_title LIKE ? OR b.start_date LIKE ? OR b.end_date LIKE ?) 
                  AND b.end_date < ?";

    $c_stmt = $conn2->prepare($count_sql);
    $c_stmt->bind_param("ssssss", $search_param, $search_param, $search_param, $search_param, $search_param, $datetoday);
    $c_stmt->execute();
    $total_rows = $c_stmt->get_result()->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $limit);

    // Build Pagination HTML
    $pagination_html = "";
    if ($total_pages > 1) {
        $prev_disabled = ($page <= 1) ? 'disabled' : '';
        $prev_val = max(1, $page - 1);
        $pagination_html .= "<li class='page-item $prev_disabled'><a class='page-link page-link-ajax' href='#' data-page='$prev_val'>Previous</a></li>";

        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            $pagination_html .= "<li class='page-item $active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
        }

        $next_disabled = ($page >= $total_pages) ? 'disabled' : '';
        $next_val = min($total_pages, $page + 1);
        $pagination_html .= "<li class='page-item $next_disabled'><a class='page-link page-link-ajax' href='#' data-page='$next_val'>Next</a></li>";
    }

    echo json_encode([
        'table_html' => $table_html,
        'pagination_html' => $pagination_html
    ]);
    exit;
}
 include '../../mysidebar/Sidebar_Portal.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>History</title>
    <link rel="shortcut icon" href="../assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/boostrap.css">
    <link rel="stylesheet" href="../node_modules/boxicons/css/boxicons.min.css">
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
        <section class="history_superadmin_section">
          <div class="container">
            <h2 class="text-header">History</h2>
            <div class="inner_con"> 
              <div class="card p-2 mb-3">
                  <div class="d-flex gap-2">
                    <div class="input-group flex-nowrap">
                      <span class="input-group-text" id="addon-wrapping"><i class='bx bx-search'></i></span>
                      <input type="search" class="form-control" id="input_history" placeholder="Search Date, Meeting Title, Room Name..." aria-label="Search" aria-describedby="addon-wrapping">
                    </div>
                </div>
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
                      <th>Cancelled At</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="history_body">
                    <!-- Populated via AJAX -->
                  </tbody>
                </table>
              </div>

              <div class="d-flex justify-content-end align-items-end">
                <nav aria-label="Page navigation">
                  <ul class="pagination pagination-sm mb-0" id="history_pagination">
                    <!-- Populated via AJAX -->
                  </ul>
                </nav>
              </div>

            </div>
          </div>
        </section>
    </div>

    <!-- Scripts (Inuna ang jQuery para ma-load bago ang ating script) -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/cool_alert.js"></script>
    <script src="../assets/js/box_icons.js"></script>
    <script src="../assets/js/boostrap.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/walkthrough_tour.js?v=9"></script>;



    <?php include '../alert.php'; ?>

</body>
</html>