<?php
// تفعيل إظهار الأخطاء للتطوير
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');
session_start();

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // 1. أضفنا حقل 'status' إلى الاستعلام
    $stmt = $conn->prepare("SELECT id, full_name, password, role, status FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // 2. التحقق من حالة الحساب أولاً
        if ($row['status'] == 'inactive') {
            $error = "عذراً، هذا الحساب معطل حالياً. يرجى مراجعة المسؤول.";
        } 
        // 3. إذا كان الحساب نشطاً، نتحقق من كلمة المرور
        elseif (password_verify($pass, $row['password'])) {
            
            session_regenerate_id(true);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];

            // التوجيه حسب الرتبة
            if ($row['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } elseif ($row['role'] == 'receptionist') {
                header('Location: receptionist_dashboard.php');
            } elseif ($row['role'] == 'manager') {
                header('Location: manager_dashboard.php');
            }
            $stmt->close();
            exit(); 
        } else {
            // كلمة المرور خاطئة
            $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
        }
    } else {
        // المستخدم غير موجود
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">

<title>تسجيل الدخول</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

body{
    font-family: 'Cairo', sans-serif;
    background: linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)),
    url('image/wilaya.jpg');
    background-size: cover;
    background-position:center;
    height:100vh;
    display:flex;
    align-items:center;
}

.card{
    border-radius:15px;
    backdrop-filter: blur(10px);
}

.card h3{
    font-weight:700;
}

.form-control{
    border-radius:10px;
    padding:10px;
}

.form-control:focus{
    box-shadow:0 0 10px rgba(13,110,253,0.3);
    border-color:#0d6efd;
}

.btn{
    border-radius:10px;
    font-weight:600;
}

.btn-primary{
    transition:0.3s;
}

.btn-primary:hover{
    transform:translateY(-2px);
}

</style>

</head>

<body>

<div class="container">
<div class="row justify-content-center">

<div class="col-md-4">

<div class="card shadow-lg border-0">
<div class="card-body p-4">

<h3 class="text-center mb-4">دخول الموظفين</h3>

<?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST">

<div class="mb-3">
<label class="form-label">اسم المستخدم</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">كلمة المرور</label>
<input type="password" name="password" class="form-control" required>
</div>

<button type="submit" name="login" class="btn btn-primary w-100 py-2 mb-3">
<i class="fas fa-sign-in-alt me-1"></i> دخول
</button>

<a href="index.php" class="btn btn-outline-secondary w-100 py-2">
<i class="fas fa-arrow-right me-1"></i> العودة للرئيسية
</a>

</form>

</div>
</div>

<p class="text-center mt-3 text-white small">
جميع الحقوق محفوظة &copy; 2026
</p>

</div>

</div>
</div>

</body>
</html>