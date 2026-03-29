<?php include('manager_logic.php'); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير - الطلبات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/manager_style.css"> </head>
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
                <thead class="table-dark">
                    <tr>
                        <th class="text-end px-4">المواطن</th>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/manager_scripts.js"></script> </body>
</html>