<?php
include('db.php');

if (isset($_POST['save_citizen'])) {
    // تأمين المدخلات من الرموز التي قد تسبب أخطاء SQL
    $n_id = mysqli_real_escape_string($conn, $_POST['national_id']);
    $f_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $l_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $m_id = mysqli_real_escape_string($conn, $_POST['manager_id']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    // معالجة رفع الملف
    $file_path = "";
    if (!empty($_FILES['attachment']['name'])) {
        $upload_dir = "uploads/";
        // التأكد من وجود المجلد
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        
        $file_name = time() . "_" . basename($_FILES['attachment']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
            $file_path = mysqli_real_escape_string($conn, $target_file);
        }
    }

    // 1. إدخال أو تحديث بيانات المواطن
    $q_citizen = "INSERT INTO citizens (national_id, first_name, last_name, phone, gender) 
                  VALUES ('$n_id', '$f_name', '$l_name', '$phone', '$gender') 
                  ON DUPLICATE KEY UPDATE phone='$phone'";
    mysqli_query($conn, $q_citizen);

    // 2. إدخال طلب المقابلة (هنا كان الخطأ)
    // لاحظ استخدام علامات التنصيص المفردة '' حول كل قيمة
    $q_app = "INSERT INTO appointments (citizen_id, manager_id, message, files_path, status, created_at) 
              VALUES ('$n_id', '$m_id', '$msg', '$file_path', 'pending', NOW())";

    if (mysqli_query($conn, $q_app)) {
        header("Location: receptionist_dashboard.php?success=1");
    } else {
        // في حال حدوث خطأ، سيطبع لك السبب بالتفصيل
        die("خطأ في تنفيذ الطلب: " . mysqli_error($conn));
    }
}
?>