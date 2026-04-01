<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="reception_dashboard.php">
            <i class="fas fa-desktop me-2"></i> نظام إدارة الاستقبال
        </a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 d-none d-md-inline small">
                <i class="fas fa-user-circle me-1"></i> الموظف: <?php echo $_SESSION['full_name']; ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                <i class="fas fa-sign-out-alt"></i> خروج
            </a>
        </div>
    </div>
</nav>