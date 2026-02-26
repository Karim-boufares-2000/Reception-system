<?php
include('db.php');

if (isset($_POST['save_citizen'])) {
    $n_id = $_POST['national_id'];
    $f_name = $_POST['first_name'];
    $l_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $manager_id = $_POST['manager_id'];
    $msg = $_POST['message'];

    // منطق الصورة: إذا لم يرفع صورة، نحدد مسار الصورة الافتراضية
    $photo_name = $_FILES['photo']['name'];
    if (!empty($photo_name)) {
        $target_photo = "uploads/photos/" . time() . "_" . $photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_photo);
    } else {
        // تعيين صورة افتراضية بناءً على النوع
        $target_photo = ($gender == 'male') ? "assets/male_default.png" : "assets/female_default.png";
    }

    // 1. إدخال أو تحديث بيانات المواطن (ON DUPLICATE KEY UPDATE)
    $query_citizen = "INSERT INTO citizens (national_id, first_name, last_name, gender, photo_path) 
                      VALUES ('$n_id', '$f_name', '$l_name', '$gender', '$target_photo')
                      ON DUPLICATE KEY UPDATE first_name='$f_name', last_name='$l_name'";
    
    if (mysqli_query($conn, $query_citizen)) {
        // 2. إنشاء طلب مقابلة جديد
        $query_app = "INSERT INTO appointments (citizen_id, manager_id, message, status) 
                      VALUES ('$n_id', '$manager_id', '$msg', 'pending')";
        mysqli_query($conn, $query_app);
        
        echo "<script>alert('تم تسجيل الطلب بنجاح'); window.location='add_citizen.php';</script>";
    }
}
?>