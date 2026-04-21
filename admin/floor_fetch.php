<?php
    include '../config.php';

    $items_per_page = 8;
    $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';
    $offset = ($current_page - 1) * $items_per_page;
    $search_param = "%" . $search_query . "%";

  
    $count_sql = "SELECT COUNT(*) FROM `floors` WHERE `floor_name` LIKE ?";
    $stmt_count = $conn2->prepare($count_sql);
    $stmt_count->bind_param("s", $search_param);
    $stmt_count->execute();
    $total_records = $stmt_count->get_result()->fetch_row()[0];
    $total_pages = ceil($total_records / $items_per_page);


    $data_sql = "SELECT * FROM `floors` WHERE `floor_name` LIKE ? LIMIT ? OFFSET ?";
    $stmt_data = $conn2->prepare($data_sql);
    $stmt_data->bind_param("sii", $search_param, $items_per_page, $offset);
    $stmt_data->execute();
    $result = $stmt_data->get_result();


    if ($result->num_rows > 0) {
        while ($row_floor = $result->fetch_assoc()) {
            $floor_name = htmlspecialchars($row_floor['floor_name'] ?? '');
            $rooms_number = htmlspecialchars($row_floor['rooms_number'] ?? '');
            $floor_id = htmlspecialchars($row_floor['floor_id'] ?? '');

            echo "
                <tr>
                    <td>$floor_name</td>
                    <td>$rooms_number</td>
                    <td>
                        <a href='floor_delete.php?floor_id=$floor_id' class='btn btn-danger btn-sm'><i class='bx bx-trash'></i> Delete</a>
                        <a href='floor_update.php?floor_id=$floor_id' class='btn btn-primary btn-sm'><i class='bx bx-edit'></i> Update</a>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center'>No Data Found</td></tr>";
    }

 
    echo "|||";

  
    if ($total_pages > 1) {
        // Previous Button
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