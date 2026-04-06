<?php
include('db.php');
session_start();

// تفعيل إظهار الأخطاء للتطوير (يفضل إغلاقها في النسخة النهائية)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // استخدام الـ Prepared Statements للأمان
    $stmt = $conn->prepare("SELECT id, full_name, password, role, status FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // 1. التحقق من حالة الحساب
        if ($row['status'] == 'inactive') {
            $error = "عذراً، هذا الحساب معطل حالياً. يرجى مراجعة المسؤول.";
        } 
        // 2. التحقق من كلمة المرور
        elseif (password_verify($pass, $row['password'])) {
            
            // تأمين الجلسة
            session_regenerate_id(true);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];

            // التوجيه الذكي حسب الرتبة
            $redirects = [
                'admin'       => 'admin_dashboard.php',
                'receptionist'=> 'receptionist_dashboard.php',
                'manager'     => 'manager_dashboard.php'
            ];

            $location = $redirects[$row['role']] ?? 'index.php';
            
            $stmt->close();
            header("Location: $location");
            exit(); 
        } else {
            $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
        }
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
    $stmt->close();
}