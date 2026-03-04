<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'manager') {
    $app_id = mysqli_real_escape_string($conn, $_POST['app_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $response = mysqli_real_escape_string($conn, $_POST['response']);

    $query = "UPDATE appointments SET 
              status = '$status', 
              manager_response = '$response' 
              WHERE id = '$app_id'";

    if (mysqli_query($conn, $query)) {
        header('Location: manager_dashboard.php?msg=success');
    } else {
        die("Error updating record: " . mysqli_error($conn));
    }
}
?>