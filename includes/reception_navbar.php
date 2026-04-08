<nav class="navbar navbar-expand-lg shadow-sm mb-4" style="background-color: #28a745;">
    <div class="container">
        <!-- شعار النظام -->
        <a class="navbar-brand fw-bold text-white" href="reception_dashboard.php">
            <i class="fas fa-desktop me-2"></i> نظام إدارة الاستقبال
        </a>

        <!-- عرض اسم الموظف وأزرار الخروج -->
        <div class="d-flex align-items-center ms-auto">
            <span class="text-white me-3 d-none d-md-inline small">
                <i class="fas fa-user-circle me-1"></i> الموظف: <strong><?php echo $_SESSION['full_name']; ?></strong>
            </span>

            <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-3">
                <i class="fas fa-sign-out-alt me-1"></i> خروج
            </a>
        </div>
    </div>
</nav>