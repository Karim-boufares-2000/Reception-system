<?php
include('db.php');

// تهيئة متغيرات الرسائل
$error = null;
$citizen = null;
$appointments = [];

if (!isset($_GET['national_id'])) {
    $error = "خطأ: لم يتم تحديد المواطن.";
} else {
    $id = mysqli_real_escape_string($conn, $_GET['national_id']);

    // 1. جلب معلومات المواطن
    $citizen_query = mysqli_query($conn, "SELECT * FROM citizens WHERE national_id = '$id'");
    $citizen = mysqli_fetch_assoc($citizen_query);

    if (!$citizen) {
        $error = "عذراً، لم يتم العثور على بيانات المواطن";
    } else {
        // 2. جلب جميع الطلبات
        $app_query = mysqli_query($conn, "SELECT a.*, u.full_name as manager_name 
                                          FROM appointments a 
                                          JOIN users u ON a.manager_id = u.id 
                                          WHERE a.citizen_id = '$id' 
                                          ORDER BY a.created_at DESC");
        
        while ($row = mysqli_fetch_assoc($app_query)) {
            $appointments[] = $row;
        }
    }
}