

<?php
include 'config.php';



  

        #kuhaon an role
        $get_role = $conn2->prepare("SELECT * FROM `accounts` WHERE `employee_id` = ? AND `user_status` != 'Inactive'");
        $get_role->bind_param("s",$_SESSION['User_Id']);
        $get_role->execute();
        $result_get=$get_role->get_result();
       if($result_get->num_rows > 0){
            $row_role = $result_get->fetch_assoc();
            $role = htmlspecialchars($row_role['role'] ?? '');
        }
 

        #role destination
    
        if($role === "1"){
            $_SESSION['superadmin_login'] = $_SESSION['User_Id'];
          header('Location: super_admin');
            exit();
     
        }elseif($role === "2"){
            $_SESSION['admin_login'] = $_SESSION['User_Id'];
           header('Location: admin');
            exit();
        }else{
          $_SESSION['user_login'] = $_SESSION['User_Id'];
            header('Location: user');
            exit();
        }


?>