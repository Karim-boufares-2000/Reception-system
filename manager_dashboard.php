<?php include('manager_logic.php'); ?>
<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير - الطلبات</title>

    <!-- Bootstrap RTL من الملفات المحلية -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">

    <!-- FontAwesome محلي -->
    <link rel="stylesheet" href="assets/css/all.min.css">

    <style>
        /* ألوان حديثة: أبيض + أخضر + أحمر */
        body { background-color: #f9f9f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background-color: #ffffff; } 
        .navbar .navbar-brand, .navbar .nav-link { color: #28a745 !important; }
        .navbar .nav-link.active { font-weight: bold; color: #dc3545 !important; }
        .breadcrumb { background-color: #ffffff; border-radius: 8px; }
        .breadcrumb .active { color: #dc3545; }
        .main-card { background-color: #ffffff; border-radius: 12px; }
        .table thead { background-color: #28a745; color: #ffffff; }
        .table tbody tr:hover { background-color: #e6f4ea; }
        .btn-outline-danger { border-color: #dc3545; color: #dc3545; }
        .btn-outline-danger:hover { background-color: #dc3545; color: #ffffff; }
        .btn-success { background-color: #28a745; border-color: #28a745; color: #ffffff; }
        .btn-success:hover { background-color: #218838; }
    </style>

</head>
<body>

<?php include('includes/manager_navbar.php'); ?>

<div class="container mt-4">
    <div class="row mb-4 justify-content-end">
        <div class="col-md-9">
            <?php include('includes/filter_form.php'); ?>
        </div>
    </div>

    <div class="card main-card overflow-hidden shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead style=" background-color:#28a745 !important;">
                    <tr>
                        <th>المواطن</th>
                        <th>رقم الهوية</th>
                        <th>تاريخ الطلب</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($results) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($results)): ?>
                            <?php include('includes/appointment_row.php'); ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="py-4 text-muted">لا توجد طلبات تطابق هذا البحث.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('includes/pagination.php'); ?>
</div>

<?php include('includes/manager_modals.php'); ?>

<!-- Bootstrap + JS محلي -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="js/manager_scripts.js"></script>

</body>
</html>