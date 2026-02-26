<?php
include('db.php');
session_start();

// التأكد أن المستخدم مسجل دخول
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$user_id = $_SESSION['user_id'];

// جلب بيانات المستخدم الحالية
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user_data = mysqli_fetch_assoc($query);

// معالجة التحديث
if (isset($_POST['update_profile'])) {
    $name = $_POST['full_name'];
    $pass = $_POST['password'];
    
    $update_sql = "UPDATE users SET full_name='$name', password='$pass' WHERE id='$user_id'";
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['full_name'] = $name; // تحديث الاسم في الجلسة
        echo "<script>alert('تم تحديث البيانات بنجاح');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <title>تعديل بياناتي</title>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">تعديل ملفي الشخصي</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>الاسم الكامل</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo $user_data['full_name']; ?>">
                        </div>
                        <div class="mb-3">
                            <label>كلمة المرور الجديدة</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $user_data['password']; ?>">
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary w-100">حفظ التغييرات</button>
                        <a href="admin_dashboard.php" class="btn btn-link w-100 mt-2">العودة للوحة التحكم</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>