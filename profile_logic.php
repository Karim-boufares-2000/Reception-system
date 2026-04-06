<?php
include('db.php');
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// 1. جلب بيانات المستخدم الحالية بأمان
$stmt = $conn->prepare("SELECT full_name, password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

// 2. معالجة التحديث عند إرسال النموذج
if (isset($_POST['update_profile'])) {
    $name = $_POST['full_name'];
    $new_pass = $_POST['password'];

    // تشفير كلمة المرور الجديدة قبل تخزينها (تجنب النص الواضح!)
    $hashed_pass = password_hash($new_pass, PASSWORD_BCRYPT);

    $update_stmt = $conn->prepare("UPDATE users SET full_name = ?, password = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $name, $hashed_pass, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['full_name'] = $name; // تحديث الاسم في الجلسة
        $success_msg = "تم تحديث البيانات بنجاح ✅";
        
        // تحديث البيانات المعروضة في الصفحة فوراً
        $user_data['full_name'] = $name;
    } else {
        $error_msg = "حدث خطأ أثناء التحديث، حاول مرة أخرى ❌";
    }
}