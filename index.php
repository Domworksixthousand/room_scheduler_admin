
<?php 
    include 'config.php'; 
    include 'remember_token.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/images/fs_logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/boostrap.css">
    <title>Farmstation.ph</title>
</head>
<body>


<main>
    <section class="login_section">
        <div class="container inner_section">
            <form action="functions.php" method="POST">
                <div class="mb-3 text-center">
                    <img src="assets/images/fs2_logo.png" alt="">
                </div>
                <div class="mb-3">
                    <label for="user_id"  class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id" class="form-control input" placeholder="Enter Employee ID" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control input" placeholder="Enter Password" required>
                    <div class="d-flex justify-content-end align-items-center mt-2">
                        <input type="checkbox" id="show_password" class="me-1">
                        <label for="show_password" id="show_password_text">Show Password</label>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" name="login" class="btn btn_login w-100">Login</button>
                </div>
            </form>
        </div>
    </section>
</main>


<script src="assets/js/cool_alert.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/boostrap.js"></script>
<?php include 'alert.php'; ?>
</body>
</html>