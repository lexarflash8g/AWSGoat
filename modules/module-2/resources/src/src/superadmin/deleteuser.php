<?php

include_once '../config.inc';

if (isset($_POST['delete'])){
    $response = $_POST['email'];
    $query = "DELETE FROM `users` WHERE `email`='$response'";
    $query2 = "DELETE FROM `users_info` WHERE `email`='$response'";
    mysqli_prepare($conn, $query2);
    mysqli_prepare($conn, $query);
    header('Location: user-settings.php');
}

?>