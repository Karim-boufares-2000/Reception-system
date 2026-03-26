<?php
include('db.php');
session_start();

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

if (isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // التحقق من كلمة المرور القديمة أولاً
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
            $_SESSION['full_name'] = $full_name; // تحديث الاسم في الجلسة
            $success = "تم تحديث بياناتك بنجاح ✅";
            // تحديث البيانات المعروضة في الصفحة
            $user_data['full_name'] = $full_name;
        }
    } else {
        $error = "كلمة المرور القديمة غير صحيحة ❌";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ملفي الشخصي - إعدادات الأمان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; font-family: 'Cairo', sans-serif; }
        .profile-card { border-radius: 20px; border: none; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card profile-card shadow-lg p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary d-inline-block p-4 rounded-circle mb-3 shadow">
                        <i class="fas fa-user-shield fa-3x text-white"></i>
                    </div>
                    <h4>إعدادات الحساب الأساسي</h4>
                </div>

                <?php if($error) echo "<div class='alert alert-danger small'>$error</div>"; ?>
                <?php if($success) echo "<div class='alert alert-success small'>$success</div>"; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">الاسم الكامل</label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user_data['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">الصلاحية (ثابتة للمدير)</label>
                        <input type="text" class="form-control bg-light" value="<?php echo $user_data['role']; ?>" disabled>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-danger">كلمة المرور القديمة (مطلوب للتأكيد)</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">كلمة المرور الجديدة (اتركها فارغة إذا لم ترد التغيير)</label>
                        <input type="password" name="new_password" class="form-control">
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-primary w-100 py-2 shadow">
                        <i class="fas fa-check-double me-1"></i> حفظ التغييرات النهائية
                    </button>
                    <a href="admin_dashboard.php" class="btn btn-link w-100 mt-2 text-decoration-none text-muted small">
                        <i class="fas fa-arrow-right me-1"></i> العودة للوحة التحكم
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>