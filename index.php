

<?php
include 'config.php';


    $_SESSION['user_id'] = "1968";

  

        #kuhaon an role
        $get_role = $conn2->prepare("SELECT * FROM `accounts` WHERE `employee_id` = ?");
        $get_role->bind_param("s",$_SESSION['user_id']);
        $get_role->execute();
        $result_get=$get_role->get_result();
       if($result_get->num_rows > 0){
            $row_role = $result_get->fetch_assoc();
            $role = htmlspecialchars($row_role['role'] ?? '');
        }else{
            $_SESSION['error'] = "Invalid Username or Password";
            header('Location: index.php');
            exit();
        }

       
        $remember_token = bin2hex(random_bytes(32)); // 64-character secure token

        $update_token = $conn2->prepare("UPDATE accounts SET remember_token = ? WHERE employee_id = ?");
        $update_token->bind_param("ss", $remember_token, $_SESSION['user_id']);
        $update_token->execute();

        setcookie("remember_token", $remember_token, time() + (7 * 24 * 60 * 60), "/", "", false, true); // httponly

        #role destination
        if($role === "1"){
            $_SESSION['superadmin_login'] = $_SESSION['user_id'];
            header('Location: super_admin');
            exit();
        }elseif($role === "2"){
            $_SESSION['admin_login'] = $_SESSION['user_id'];
            header('Location: admin');
            exit();
        }else{
            $_SESSION['user_login'] = $_SESSION['user_id'];
            header('Location: user');
            exit();
        }

    
?>