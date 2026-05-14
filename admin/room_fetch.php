<?php
include '../config.php';

$items_per_page = 8;//limit sa page an maluwas
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;//current page deafult na maluwas
$search_query = isset($_GET['search']) ? $_GET['search'] : '';//in type sa searching bar
$selected_status = isset($_GET['status']) ? $_GET['status'] : [];//in select na status

$offset = max(0, ($current_page - 1) * $items_per_page);
$search_param = "%" . $search_query . "%";

// 1. Simplified WHERE clause: Removed floor_id logic
$where_clauses = ["(rooms.room_name LIKE ? OR rooms.serial_number LIKE ? OR floors.floor_name LIKE ? OR rooms.description LIKE ?)"];
$params = [$search_param, $search_param, $search_param, $search_param];
$types = "ssss";

$where_sql = implode(" AND ", $where_clauses);
echo " <p class='fw-bold mt-2'>ROOM STATUS TODAY (".date('M d, Y').")</p>";

// 2. Main Query
$data_sql = "SELECT rooms.*, floors.floor_name FROM `rooms` 
             LEFT JOIN `floors` ON rooms.floor_id = floors.floor_id 
             WHERE $where_sql";

$stmt_data = $conn2->prepare($data_sql);
$stmt_data->bind_param($types, ...$params);
$stmt_data->execute();
$all_results = $stmt_data->get_result();

$filtered_rooms = [];
$today = date('Y-m-d');
$current_time = date('H:i:s');

while ($row = $all_results->fetch_assoc()) {
    $id = $row['room_id'];
    $is_currently_occupied = false;
    $total_booked_minutes = 0;

    // Check bookings for today
    $status_sql = "SELECT start_time, end_time, start_date FROM `booking` 
                   WHERE room_id = ? AND start_date = ? AND `status` = 'Occupied'";
    $stmt_status = $conn2->prepare($status_sql);
    $stmt_status->bind_param("ss", $id, $today);
    $stmt_status->execute();
    $res_status = $stmt_status->get_result();

    while ($b = $res_status->fetch_assoc()) {
        $start = $b['start_time'];
        $end = $b['end_time'];
        $start_date = $b['start_date'];

        $comparison_end = ($end == '00:00:00') ? '23:59:59' : $end;
        
        // Check current occupancy
        if ($current_time >= $start && $current_time <= $comparison_end) {
            $is_currently_occupied = true;
        }

        // Calculate minutes
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        if ($end_ts <= $start_ts) {
            $end_ts += 86400; 
        }
        $total_booked_minutes += ($end_ts - $start_ts) / 60;
    }
    $stmt_status->close();

    // Determine Label
    if ($total_booked_minutes >= 900) {
        $current_label = "Fully Occupied";
    } else if ($is_currently_occupied) {
        $current_label = "Partially Occupied";
    }else if ($total_booked_minutes > 0 && $current_time < $start) {
       // Walang gumagamit ngayon, pero may booking sa ibang oras ng araw na ito
       $current_label = "Partially Occupied";
    } 
    else {
        $current_label = "Available";
    }

    // 3. Availability Filter (PHP Side)
    if (empty($selected_status) || in_array($current_label, $selected_status)) {
        $row['calc_label'] = $current_label;
        $filtered_rooms[] = $row;
    }
}

// Manual Pagination
$total_rooms = count($filtered_rooms);
$total_pages = ceil($total_rooms / $items_per_page);
$display_rooms = array_slice($filtered_rooms, $offset, $items_per_page);

// --- OUTPUT UI (Cards) ---
if (!empty($display_rooms)) {
    foreach ($display_rooms as $row) {
        $status_label = $row['calc_label'];
        $badge_class = ($status_label == "Fully Occupied") ? "bg-glasmorphism text-light border border-danger border-2  fw-bold p-2" : (($status_label == "Partially Occupied") ? "bg-glasmorphism text-light border border-warning border-2   p-2" : "bg-glasmorphism text-light border border-success border-2 fw-bold p-2");
        $desc = htmlspecialchars($row['description'] ?? '');
        $room_name = htmlspecialchars($row['room_name'] ?? '');
        $short_desc = (strlen($desc) > 25 ? substr($desc, 0, 25) . '...' : $desc);
        $room_name_short = (strlen($room_name) > 22 ? substr($room_name, 0, 22) . '...' : $room_name);
        
        echo '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="overflow-hidden position-relative">
                        <img src="../assets/uploads/'.$row['image'].'" class="card-img-top" alt="'.$room_name.'" style="height: 180px; object-fit: cover;">
                        <span class="badge position-absolute rounded-pill top-0 end-0 m-2 '.$badge_class.'">'.$status_label.'</span>
                        <div class="buttton_actions">
                            <a href="room_delete.php?room_id='.$row['room_id'].'" class="btn btn-danger btn-sm '.($status_label != "Available" ? "d-none" : "").'"><i class="bx bx-trash"></i></a>
                            <a href="room_update.php?room_id='.$row['room_id'].'" class="btn btn-primary btn-sm '.($status_label != "Available" ? "d-none" : "").'"><i class="bx bx-edit"></i></a>
                            <a href="room_info.php?room_id='.$row['room_id'].'&location_back=rooms.php" class="btn btn-info btn-sm text-light"><i class="bx bx-info-circle"></i></a>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold mb-1">'.$room_name_short.'</h6>
                        <p class="card-text text-secondary small mb-1"><i class="bx bx-star"></i> '.$short_desc.'</p>
                        <p class="card-text small mb-1 text-muted"><i class="bx bx-layer"></i> '.htmlspecialchars($row['floor_name']).'</p>
                        <div class="reservation_bottona mt-3">
                            <a href="reservation_add.php?room_id='.$row['room_id'].'" class="btn btn_reserve btn-sm w-100 '.($status_label == "Fully Occupied" ? "disabled" : "").'"><i class="bx bxs-calendar-check"></i> Book Now</a>
                        </div>
                    </div>
                </div>
              </div>';
                }
            } else {
                echo "<div class='col-12 text-center py-5'><i class='bx bx-search-alt text-muted' style='font-size: 3rem;'></i><p class='mt-2 text-secondary'>No rooms found matching your filters.</p></div>";
            }
            echo "|||"; // Pagination Splitter ito pogi

            // --- CONSISTENT PAGINATION WITH PREV/NEXT ---
            if ($total_pages > 1) {
                echo '<nav aria-label="Page navigation"><ul class="pagination pagination-sm mb-0 ">';
                
                // Previous Button
                if ($current_page > 1) {
                    $prev_page = $current_page - 1;
                    echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='$prev_page'>Previous</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
                }

                // Page Numbers
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($i == $current_page) ? ' active' : '';
                    echo "<li class='page-item$active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
                }

                // Next Button
                if ($current_page < $total_pages) {
                    $next_page = $current_page + 1;
                    echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='$next_page'>Next</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
                }
                
                echo '</ul></nav>';
            }
            ?>