<?php include('reception_logic.php'); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة الاستقبال المتكاملة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .main-card { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .clickable-row { cursor: pointer; transition: 0.2s; }
        .clickable-row:hover { background-color: #f1f8ff !important; }
    </style>
</head>
<body>

<?php include('includes/reception_navbar.php'); ?>

<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <button class="btn btn-success shadow-sm py-2 px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-user-plus me-2"></i> إضافة مواطن جديد
            </button>
        </div>
        <div class="col-md-8">
            <?php include('includes/reception_filters.php'); ?>
        </div>
    </div>

    <div class="card main-card overflow-hidden shadow-sm">
        <?php include('includes/reception_table.php'); ?>
    </div>

    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <li class="page-item <?php echo ($page == $i)?'active':''; ?>">
                    <a class="page-link shadow-sm mx-1 rounded" href="?page=<?php echo $i; ?>&status_filter=<?php echo $status_filter; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<?php include('includes/reception_modals.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function viewHistory(id) {
    fetch('fetch_history.php?national_id=' + id)
    .then(r => r.text())
    .then(d => {
        document.getElementById('historyContent').innerHTML = d;
        new bootstrap.Modal(document.getElementById('historyModal')).show();
    });
}
</script>
</body>
</html>