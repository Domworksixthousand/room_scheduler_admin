<?php
include 'account_add.php';
$employee_id = htmlspecialchars($_GET['data'] ?? '');

if ($employee_id) {
    $get = $conn1->prepare("SELECT * FROM `employee` WHERE `Employee_ID` = ?");
    $get->bind_param("s", $employee_id);
    $get->execute();
    $result_data = $get->get_result();

    if ($result_data->num_rows > 0) {
        $row_get = $result_data->fetch_assoc();
        $firstname = htmlspecialchars($row_get['FirstName'] ?? '');
        $lastname = htmlspecialchars($row_get['LastName'] ?? '');
        $gender = htmlspecialchars($row_get['Gender_ID'] ?? '');
        $data_assign = ($gender === "1") ? "Mr." : "Mrs.";
    }
}
?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">
    <input type="hidden" name="assign_user" value="1">
</form>

<script>
// Auto-show alert on page load
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "question",
        title: "Important!",
        text: "Are you sure to assign <?php echo $data_assign . ' ' . $lastname; ?>?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
   
            document.getElementById('assignForm').submit();
        } else {
  
            location.href = 'account_add.php';
        }
    });
});
</script>