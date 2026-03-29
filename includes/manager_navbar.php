<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="manager_dashboard.php">
            <i class="fas fa-user-tie text-primary me-2"></i> 
            لوحة المدير
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#managerNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="managerNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="manager_dashboard.php">
                        <i class="fas fa-list-ul me-1"></i> قائمة الطلبات
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-id-card me-1"></i> ملفي الشخصي
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <div class="text-white-50 me-3 d-none d-md-block">
                    <small>مرحباً،</small> 
                    <span class="text-white fw-bold"><?php echo $_SESSION['full_name']; ?></span>
                </div>
                
                <div class="vr text-white opacity-25 me-3 d-none d-md-block" style="height: 20px;"></div>
                
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
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="manager_dashboard.php" class="text-decoration-none">الرئيسية</a></li>
            <li class="breadcrumb-item active" aria-current="page">إدارة الطلبات الموجهة</li>
        </ol>
    </nav>
</div>