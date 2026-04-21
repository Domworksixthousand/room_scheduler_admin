<?php
include 'accounts.php';
$id = htmlspecialchars($_GET['data'] ?? '');

if ($id) {
    $get = $conn2->prepare("SELECT * FROM `accounts` WHERE `id` = ?");
    $get->bind_param("s", $id);
    $get->execute();
    $result_data = $get->get_result();

    if ($result_data->num_rows > 0) {
        $row_get = $result_data->fetch_assoc();
        $employee_id = htmlspecialchars($row_get['employee_id'] ?? '');
    }
}



$get1 = $conn1->prepare("SELECT * FROM `employee` WHERE `employee_id` = ?");
$get1->bind_param("s", $employee_id);
$get1->execute();
$result_data1 = $get1->get_result();

if ($result_data1->num_rows > 0) {
    $row_get1 = $result_data1->fetch_assoc();
    $firstname = htmlspecialchars($row_get1['FirstName'] ?? '');
    $lastname = htmlspecialchars($row_get1['LastName'] ?? '');
    $gender = htmlspecialchars($row_get1['Gender_ID'] ?? '');
    $data_assign = ($gender === "1") ? "Mr." : "Mrs.";
}

?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="delete_account" value="1">
</form>

<script>
// Auto-show alert on page load
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "warning",
        title: "Important!",
        text: "Are you sure to Delete Assigned Account <?php echo $data_assign . ' ' . $lastname; ?>?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
   
            document.getElementById('assignForm').submit();
        } else {
  
            location.href = 'accounts.php';
        }
    });
});
</script>