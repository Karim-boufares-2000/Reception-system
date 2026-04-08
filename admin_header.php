<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة النظام - لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body{ background:#eef2f7; font-family:'Segoe UI'; }
        .navbar{
    background: linear-gradient(45deg, #0f8f45, #1ecf66) !important;
}
        .card{ border-radius:15px; border:none; transition:.3s; }
        .card:hover{ transform:translateY(-3px); }
        .table thead{ background:#2a5298; color:white; }
        .btn{ border-radius:8px; }
        .role-badge{ padding:6px 12px; border-radius:20px; font-size:12px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg shadow mb-4">
    <div class="container">
        <a class="navbar-brand text-white" href="admin_dashboard.php">
            <i class="fas fa-user-shield me-2"></i> لوحة تحكم المسؤول
        </a>
        <div class="d-flex align-items-center">
            <a href="admin_profile.php" class="btn btn-sm btn-outline-light me-2 border-0">
                <i class="fas fa-user-circle fa-lg me-1"></i> ملفي الشخصي
            </a>
            <span class="text-white-50 me-3">|</span>
            <span class="text-white me-3 small">مرحباً، <?php echo $_SESSION['full_name']; ?></span>
            <a href="logout.php" class="btn btn-sm btn-danger ms-2 shadow-sm">
                <i class="fas fa-sign-out-alt"></i> خروج
            </a>
        </div>
    </div>
</nav>

</body>
