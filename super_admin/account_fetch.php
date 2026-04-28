<?php
include '../config.php';

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_param = "%$search%";

// 1. Get all employee_ids from conn2 (the accounts table)
$account_ids = [];
$get_auth = $conn2->query("SELECT employee_id FROM `accounts`");
while($auth_row = $get_auth->fetch_assoc()){
    $account_ids[] = $auth_row['employee_id'];
}

$tableData = "";


if (!empty($account_ids)) {

    $placeholders = implode(',', array_fill(0, count($account_ids), '?'));

    $sql = "SELECT * FROM `employee` 
            WHERE (`lastname` LIKE ? OR `firstname` LIKE ? OR `middlename` LIKE ?) 
            AND `Employee_ID` IN ($placeholders)
            ORDER BY lastname, firstname, middlename 
            LIMIT ? OFFSET ?";

    $stmt = $conn1->prepare($sql);

  
    $types = "sss" . str_repeat("s", count($account_ids)) . "ii";
    $params = array_merge([$search_param, $search_param, $search_param], $account_ids, [$limit, $offset]);
    
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $fname = htmlspecialchars($row['FirstName'] ?? '');
            $lname = htmlspecialchars($row['LastName'] ?? '');
            $mname = htmlspecialchars($row['MiddleName'] ?? '');
            $suffix = htmlspecialchars($row['Suffix_ID'] ?? '');
            $emp_id = htmlspecialchars($row['Employee_ID'] ?? '');
           
            $tableData .= "
            <tr>
                <td>$lname</td>
                <td>$fname</td>
                <td>$mname</td>
                <td>$suffix</td>
                <td>
                    <a href='account_delete.php?emp_id=$emp_id' class='btn btn-danger btn-sm'><i class='bx bx-trash'></i> Delete</a>
                </td>
            </tr>";
        }
    } else {
        $tableData = "<tr><td colspan='5' class='text-center'>No matching accounts found.</td></tr>";
    }
} else {
    $tableData = "<tr><td colspan='5' class='text-center'>No accounts registered in the system.</td></tr>";
}

echo $tableData;

echo "|||";

if (!empty($account_ids)) {
    $count_placeholders = implode(',', array_fill(0, count($account_ids), '?'));
    $count_sql = "SELECT COUNT(*) as total FROM `employee` 
                  WHERE (`lastname` LIKE ? OR `firstname` LIKE ? OR `middlename` LIKE ?)
                  AND `Employee_ID` IN ($count_placeholders)";
                  
    $c_stmt = $conn1->prepare($count_sql);
    $c_types = "sss" . str_repeat("s", count($account_ids));
    $c_params = array_merge([$search_param, $search_param, $search_param], $account_ids);
    
    $c_stmt->bind_param($c_types, ...$c_params);
    $c_stmt->execute();
    $total_rows = $c_stmt->get_result()->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $limit);

    if ($total_pages > 1) {
        // ... (Your pagination HTML loop from the previous example goes here)
    }
}
?>