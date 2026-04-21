<?php

if(isset($_COOKIE['remember_token'])){
    $token = $_COOKIE['remember_token'];

    // Step 1: Find token in accounts
    $stmt1 = $conn2->prepare("SELECT * FROM accounts WHERE remember_token = ?");
    $stmt1->bind_param("s", $token);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    if($result1->num_rows > 0){
        $row_acc = $result1->fetch_assoc();
        $employee_id = $row_acc['employee_id'];
        $role = $row_acc['role'];

            $stmt2 = $conn1->prepare("SELECT * FROM employeesystemcredential WHERE Employee_ID = ?");
            $stmt2->bind_param("s", $employee_id);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if($result2->num_rows > 0){
                $user = $result2->fetch_assoc();

                #role destination
            if($role === "1"){

            }elseif($role === "2"){
                $_SESSION['admin_login'] = $employee_id;
                header('Location: admin');
                exit();
            }else{
                // Token invalid, clear cookie
                setcookie('remember_token', '', time() - 3600, "/");
            }

            
            }
    } else {
        // Token invalid, clear cookie
        setcookie('remember_token', '', time() - 3600, "/");
    }
}


?>