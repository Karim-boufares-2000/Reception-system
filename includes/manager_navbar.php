<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>
<nav class="navbar navbar-expand-lg shadow-sm mb-4" style="background-color: #ffffff;">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="manager_dashboard.php">
            <i class="fas fa-user-tie text-success me-2"></i> 
            لوحة المدير
        </a>

        <button class="navbar-toggler border-success" type="button" data-bs-toggle="collapse" data-bs-target="#managerNav">
            <span class="navbar-toggler-icon" style="filter: invert(50%) sepia(100%) saturate(500%) hue-rotate(100deg);"></span>
        </button>

        <div class="collapse navbar-collapse" id="managerNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active text-success fw-semibold" href="manager_dashboard.php">
                        <i class="fas fa-list-ul me-1"></i> قائمة الطلبات
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success fw-semibold" href="profile.php">
                        <i class="fas fa-id-card me-1"></i> ملفي الشخصي
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <div class="me-3 d-none d-md-block" style="color:#6c757d;">
                    <small>مرحباً،</small> 
                    <span class="fw-bold text-success"><?php echo $_SESSION['full_name']; ?></span>
                </div>
                
                <div class="vr" style="border-color: #28a745; opacity: 0.3; height: 25px;" ></div>
                
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3" 
                   onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">
                    <i class="fas fa-power-off me-1"></i> خروج
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small" style="background-color:#f8f9fa; border-radius:8px; padding:8px 15px;">
            <li class="breadcrumb-item">
                <a href="manager_dashboard.php" class="text-decoration-none text-success">الرئيسية</a>
            </li>
            <li class="breadcrumb-item active text-danger" aria-current="page">إدارة الطلبات الموجهة</li>
        </ol>
    </nav>
</div>