<?php
include('db.php');
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// جلب بيانات المستخدم الحالية
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

// معالجة تحديث البيانات
if (isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // التحقق من كلمة المرور القديمة
    if (password_verify($old_password, $user_data['password'])) {
        
        if (!empty($new_password)) {
            // تحديث الاسم وكلمة المرور الجديدة
            $hashed_new = password_hash($new_password, PASSWORD_BCRYPT);
            $up_stmt = $conn->prepare("UPDATE users SET full_name = ?, password = ? WHERE id = ?");
            $up_stmt->bind_param("ssi", $full_name, $hashed_new, $user_id);
        } else {
            // تحديث الاسم فقط
            $up_stmt = $conn->prepare("UPDATE users SET full_name = ? WHERE id = ?");
            $up_stmt->bind_param("si", $full_name, $user_id);
        }

        if ($up_stmt->execute()) {
            $_SESSION['full_name'] = $full_name;
            $success = "تم تحديث بياناتك بنجاح ✅";
            // تحديث البيانات في المصفوفة لعرضها فوراً
            $user_data['full_name'] = $full_name;
        }
    } else {
        $error = "كلمة المرور القديمة غير صحيحة ❌";
    }
}