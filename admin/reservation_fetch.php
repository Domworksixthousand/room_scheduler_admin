<?php
include '../config.php';

// 1. SETTINGS & INPUTS
$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$selected_floors = isset($_GET['floors']) ? $_GET['floors'] : [];
$selected_status = isset($_GET['status']) ? $_GET['status'] : [];
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';

$offset = max(0, ($current_page - 1) * $items_per_page);
$today = date('Y-m-d');
$current_time = date('H:i:s');

// 2. BUILD THE SQL
$where_clauses = ["1=1"]; 
$params = [];
$types = "";

// Filter by Date (Defaults to today if no search is active)
if (!empty($filter_date)) {
    $where_clauses[] = "b.start_date = ?";
    $params[] = $filter_date;
    $types .= "s";
} else if (empty($search_query)) {
    $where_clauses[] = "(b.start_date <= ? AND b.end_date >= ?)";
    array_push($params, $today, $today);
    $types .= "ss";
}

// Search Bar Logic
if (!empty($search_query)) {
    $search_param = "%" . $search_query . "%";
    $where_clauses[] = "(r.room_name LIKE ? OR b.fullname LIKE ? OR f.floor_name LIKE ?)";
    array_push($params, $search_param, $search_param, $search_param);
    $types .= "sss";
}

// Floor Filter Logic
if (!empty($selected_floors)) {
    $placeholders = implode(',', array_fill(0, count($selected_floors), '?'));
    $where_clauses[] = "r.floor_id IN ($placeholders)";
    foreach ($selected_floors as $f_id) {
        $params[] = (int)$f_id;
        $types .= "i";
    }
}

$where_sql = implode(" AND ", $where_clauses);

// 3. GET TOTAL COUNT FOR PAGINATION
$count_sql = "SELECT COUNT(*) as total FROM booking b 
              JOIN rooms r ON b.room_id = r.room_id 
              JOIN floors f ON r.floor_id = f.floor_id 
              WHERE $where_sql";
$stmt_count = $conn2->prepare($count_sql);
if (!empty($types)) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$total_rows = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $items_per_page);

// 4. FETCH DATA
$query_sql = "SELECT b.*, r.room_name, r.image, f.floor_name 
              FROM booking b 
              JOIN rooms r ON b.room_id = r.room_id 
              JOIN floors f ON r.floor_id = f.floor_id 
              WHERE $where_sql 
              ORDER BY b.start_date DESC, b.start_time  
              LIMIT ?, ?";

$data_params = $params;
$data_types = $types;
$data_params[] = $offset;
$data_params[] = $items_per_page;
$data_types .= "ii";

$stmt_data = $conn2->prepare($query_sql);
$stmt_data->bind_param($data_types, ...$data_params);
$stmt_data->execute();
$display_data = $stmt_data->get_result();

// 5. OUTPUT UI
$display_date_label = !empty($filter_date) ? date('M d, Y', strtotime($filter_date)) : date('M d, Y');
echo "<p class='fw-bold mt-2'>RESERVATIONS FOR " . strtoupper($display_date_label) . "</p>";

if ($total_rows > 0) {
    echo '<div class="row">'; // Wrap cards in a row for better layout
    while ($row = $display_data->fetch_assoc()) {
        // Status Logic
    // Inside the while ($row = $display_data->fetch_assoc()) loop:

        $start_timestamp = strtotime($row['start_time']);
        $end_timestamp = strtotime($row['end_time']);
        $now_timestamp = time(); // Use current system time
        $today_date = date('Y-m-d');

        $db_status = $row['status']; // Status from database (e.g., Cancelled)
        $row_start_date = $row['start_date'];

        // LOGIC CHECK
        $display_none = " ";
        if ($db_status === "Cancelled") {
            $status_label = "Cancelled";
            $badge_class = "bg-danger";
            $display_none = 'disabled';
        } elseif ($today_date > $row_start_date) {
            // If the day has passed
            $status_label = "Done";
            $badge_class = "bg-success";
            $display_none = 'd-none';
        } elseif ($today_date < $row_start_date) {
            // If the day is in the future
            $status_label = "Upcoming";
            $badge_class = "bg-primary";
        } else {
            // It is TODAY - check the clock
            if ($now_timestamp >= $start_timestamp && $now_timestamp <= $end_timestamp) {
                $status_label = "On Going";
                $badge_class = "bg-danger";
                $display_none = 'disabled';
            } elseif ($now_timestamp < $start_timestamp) {
                $status_label = "Upcoming";
                $badge_class = "bg-primary";
            } else {
                $status_label = "Done";
                $badge_class = "bg-success";
                $display_none = 'disabled';
            }
        }

        // FILTERING: If the user checked specific boxes, skip rows that don't match
        if (!empty($selected_status) && !in_array($status_label, $selected_status)) {
            continue; 
        }

        $room_name = htmlspecialchars($row['room_name']);
        $booking_id = htmlspecialchars($row['booking_id']);
        $start_date = htmlspecialchars($row['start_date']);
        $user_name = htmlspecialchars($row['fullname']);
        $room_name_short = (strlen($room_name) > 22 ? substr($room_name, 0, 22) . '...' : $room_name);
        $user_name_short = (strlen($user_name) > 22 ? substr($user_name, 0, 22) . '...' : $user_name);

        echo '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="overflow-hidden position-relative">
                        <img src="../assets/uploads/'.$row['image'].'" class="card-img-top" alt="'.$room_name_short.'" style="height: 160px; object-fit: cover;">
                        <span class="badge position-absolute rounded-pill top-0 end-0 m-2 '.$badge_class.' p-2">'.$status_label.'</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1 ">'.$room_name.'</h6>
                        <p class="small mb-1 text-secondary p-0 m-0 d-flex align-items-center gap-2"><i class="bx bx-calendar fs-5"></i> '. $start_date .'</p>
                        <p class="small mb-1 text-secondary p-0 m-0 d-flex align-items-center gap-2"><i class="bx bx-time-five fs-5"></i> '.date('h:i A', $start_timestamp).' - '.date('h:i A', $end_timestamp).'</p>
                        <p class="small mb-1 text-secondary p-0 m-0 d-flex align-items-center gap-2"><i class="bx bx-user-circle fs-5"></i> '.$user_name_short.'</p>
                        <p class="small mb-1 text-secondary p-0 m-0 d-flex align-items-center gap-2"><i class="bx bx-layer fs-5"></i> '.htmlspecialchars($row['floor_name']).'</p>
                    </div>
                    <div class="action_butt mt-auto d-flex">
                        <a href="room_info.php?room_id='.$row['room_id'].'&location_back=reservations.php" class="btn btn_view w-100 d-flex align-items-center justify-content-center gap-1">
                            <i class="bx bx-info-circle"></i> Info
                        </a>
                        <a href="reservation_cancel.php?booking_id=' .$booking_id. '" class="btn btn_cancelled w-100 d-flex align-items-center justify-content-center gap-1 ' . $display_none . '"><i class="bx bx-trash"></i> Cancel</a>
                    </div>
                </div>
              </div>';  
    }
    echo '</div>';
} else {
    echo "<div class='col-12 text-center py-5'><i class='bx bx-calendar-x text-muted' style='font-size: 3rem;'></i><p class='mt-2 text-secondary'>No reservations found for this selection.</p></div>";
}

echo "|||"; // AJAX Splitter
if ($total_pages > 1) {
    echo '<ul class="pagination pagination-sm mb-0">';
    
    // Previous Link
    $prev_disabled = ($current_page <= 1) ? 'disabled' : '';
    $prev_val = max(1, $current_page - 1);
    echo "<li class='page-item $prev_disabled'><a class='page-link page-link-ajax' href='#' data-page='$prev_val'>Previous</a></li>";

    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $current_page) ? 'active' : '';
        echo "<li class='page-item $active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
    }

    // Next Link
    $next_disabled = ($current_page >= $total_pages) ? 'disabled' : '';
    $next_val = min($total_pages, $current_page + 1);
    echo "<li class='page-item $next_disabled'><a class='page-link page-link-ajax' href='#' data-page='$next_val'>Next</a></li>";
    
    echo '</ul>';
}
?>