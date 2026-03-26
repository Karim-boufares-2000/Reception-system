<?php
include('db.php');
session_start();

// 1. حماية الجلسة والتحقق من الصلاحية
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// 2. إضافة مستخدم جديد (إدخال آمن + تشفير كلمة المرور)
if (isset($_POST['add_user'])) {
    $name = $_POST['full_name'];
    $rank = $_POST['rank'];
    $user = $_POST['username'];
    // تشفير كلمة المرور باستخدام خوارزمية BCRYPT القوية
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    
    // استخدام Prepared Statements لمنع SQL Injection نهائياً
    $stmt = $conn->prepare("INSERT INTO users (full_name, rank, username, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $rank, $user, $pass, $role);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?success=1");
    } else {
        die("خطأ في الإضافة: " . $conn->error);
    }
    $stmt->close();
    exit();
}

// 3. تحديث مستخدم (إدخال آمن)
if (isset($_POST['update_user'])) {
    $id   = (int)$_POST['user_id']; // التأكد من أن المعرف رقمي
    $name = $_POST['full_name'];
    $rank = $_POST['rank'];
    $user = $_POST['username'];
    $role = $_POST['role'];
    
    if (!empty($_POST['password'])) {
        // إذا تم تغيير كلمة المرور، نقوم بتشفير الجديدة
        $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET full_name=?, rank=?, username=?, role=?, password=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $rank, $user, $role, $pass, $id);
    } else {
        // تحديث البيانات بدون لمس كلمة المرور القديمة
        $stmt = $conn->prepare("UPDATE users SET full_name=?, rank=?, username=?, role=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $rank, $user, $role, $id);
    }
    
    $stmt->execute();
    header("Location: admin_dashboard.php?updated=1");
    $stmt->close();
    exit();
}

// 4. تغيير حالة المستخدم (تفعيل / تعطيل) بدلاً من الحذف
if (isset($_GET['toggle_status'])) {
    $id = (int)$_GET['toggle_status'];
    $current_status = $_GET['current'];
    
    // تبديل الحالة
    $new_status = ($current_status == 'active') ? 'inactive' : 'active';
    
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?status_updated=1");
    }
    $stmt->close();
    exit();
}
?>