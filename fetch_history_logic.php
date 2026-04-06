<?php
include('db.php');

// التأكد من وجود رقم الهوية
if (!isset($_GET['national_id'])) {
    $error_message = "خطأ: لم يتم تحديد المواطن.";
    return; // التوقف عن التنفيذ إذا كان الملف مُضمّناً
}

$id = mysqli_real_escape_string($conn, $_GET['national_id']);

// 1. جلب معلومات المواطن
$citizen_query = mysqli_query($conn, "SELECT * FROM citizens WHERE national_id = '$id'");
$c = mysqli_fetch_assoc($citizen_query);

if (!$c) {
    $error_message = "عذراً، لم يتم العثور على بيانات المواطن";
    return;
}

// 2. جلب جميع الطلبات المرتبطة بالمواطن مع اسم المدير
$app_query = mysqli_query($conn, "SELECT a.*, u.full_name as manager_name 
                                  FROM appointments a 
                                  JOIN users u ON a.manager_id = u.id 
                                  WHERE a.citizen_id = '$id' 
                                  ORDER BY a.created_at DESC");

$total_requests = mysqli_num_rows($app_query);
?>