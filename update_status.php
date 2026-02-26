<?php
include('db.php');
session_start();

if ($_SESSION['role'] != 'manager') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $app_id = $_POST['app_id'];
    $status = $_POST['status'];
    $response = mysqli_real_escape_string($conn, $_POST['response']);
    $date = date('Y-m-d H:i:s'); // تاريخ المعالجة

    $query = "UPDATE appointments SET 
              status = '$status', 
              manager_response = '$response', 
              appointment_date = '$date' 
              WHERE id = '$app_id'";

    if (mysqli_query($conn, $query)) {
        header('Location: manager_dashboard.php?msg=updated');
    } else {
        echo "خطأ في تحديث البيانات: " . mysqli_error($conn);
    }
}
?>