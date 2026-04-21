
<?php
#success alert
if(isset($_SESSION['success'])){
    echo "<script>
        document.addEventListener('DOMContentLoaded', function(){
            CoolAlert.show({
                icon: 'success',
                title: 'Success',
                text: '{$_SESSION['success']}',
                showConfirmButton: true,
                confirmButtonText: 'Proceed',
                 showConfirmButton: true,
                confirmButtonText: 'OK',

                showDenyButton: false,
                showCancelButton: false,
                showCloseIcon: false
            });
        });
    </script>";
    unset($_SESSION['success']);
}

#error alert
if(isset($_SESSION['error'])){
    echo "<script>
       document.addEventListener('DOMContentLoaded', function(){
            CoolAlert.show({
                icon: 'error',
                title: 'Error',
                text: '{$_SESSION['error']}',

                showConfirmButton: true,
                confirmButtonText: 'OK',

                showDenyButton: false,
                showCancelButton: false,
                showCloseIcon: false
            });
        });
    </script>";
    unset($_SESSION['error']);
}
?>




