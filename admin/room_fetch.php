<?php
include '../config.php';

$items_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$offset = max(0, ($current_page - 1) * $items_per_page);
$search_param = "%" . $search_query . "%";

// 1. Get Total for Pagination
$count_sql = "SELECT COUNT(*) FROM `rooms` 
              LEFT JOIN `floors` ON rooms.floor_id = floors.floor_id 
              WHERE rooms.room_name LIKE ? OR rooms.serial_number LIKE ? 
              OR floors.floor_name LIKE ? OR rooms.description LIKE ?";
$stmt_count = $conn2->prepare($count_sql);
$stmt_count->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
$stmt_count->execute();
$total_rooms = $stmt_count->get_result()->fetch_row()[0];
$total_pages = ceil($total_rooms / $items_per_page);

// 2. Fetch Results
$data_sql = "SELECT rooms.*, floors.floor_name FROM `rooms` 
             LEFT JOIN `floors` ON rooms.floor_id = floors.floor_id 
             WHERE rooms.room_name LIKE ? OR rooms.serial_number LIKE ? 
             OR floors.floor_name LIKE ? OR rooms.description LIKE ? 
             LIMIT ? OFFSET ?";
$stmt_data = $conn2->prepare($data_sql);
$stmt_data->bind_param("ssssii", $search_param, $search_param, $search_param, $search_param, $items_per_page, $offset);
$stmt_data->execute();
$result = $stmt_data->get_result();

// --- Output Table Rows ---
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['room_id'];
        $desc = htmlspecialchars($row['description'] ?? '');
        $short_desc = (strlen($desc) > 25 ? substr($desc, 0, 25) . '...' : $desc);

       echo '
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="card border-0 shadow-sm">
                   <div class="overflow-hidden">
                     <img src="../assets/uploads/'.$row['image'].'" class="card-img-top" alt="">
                   </div>
                    <div class="card-body">
                        <p class="card-title fw-bold fs-6">'.htmlspecialchars($row['room_name']).'</p>
                        <p class="card-text text-secondary">'.$short_desc.'</p>
                        <p class="card-text floor_name "><i class="bx bx-building mt-1"></i> '.htmlspecialchars($row['floor_name']).'</p>
                        <div class="reservation_bottona">
                        <a href="reserve.php?room_id='.$id.'" class="btn  btn_reserve btn-sm w-100 "><i class="bx bxs-calendar-check"></i> Book</a>
                        </div>
                        <div class="buttton_actions">
                            <a href="room_delete.php?room_id='.$id.'" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Delete" class="btn btn-danger btn-sm w-100"><i class="bx bx-trash "></i> </a>
                            <a href="room_update.php?room_id='.$id.'" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Update" class="btn btn-primary btn-sm w-100"><i class="bx bx-edit "></i> </a>
                            <a href="room_info.php?room_id='.$id.'" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="More Info" class="btn btn-info btn-sm w-100"><i class="bx bx-info-circle text-light"></i> </a>
                        </div>
                    </div>
                </div>
            </div>
       ';

            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No rooms found.</td></tr>";
        }


        echo "|||";


        if ($total_pages > 1) {
            if ($current_page > 1) {
                echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='".($current_page - 1)."'>Previous</a></li>";
            }
            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($i == $current_page ? ' active' : '');
                echo "<li class='page-item$active'><a class='page-link page-link-ajax' href='#' data-page='$i'>$i</a></li>";
            }
            if ($current_page < $total_pages) {
                echo "<li class='page-item'><a class='page-link page-link-ajax' href='#' data-page='".($current_page + 1)."'>Next</a></li>";
            }
        }
        ?>

