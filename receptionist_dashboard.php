<?php
include('db.php');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'receptionist') header('Location: login.php');

// 1. إعدادات تعدد الصفحات (10 طلبات لكل صفحة)
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. إعدادات البحث والفلترة
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status_filter']) ? mysqli_real_escape_string($conn, $_GET['status_filter']) : '';

// 3. بناء جملة الاستعلام
$where = "1=1";
if ($search) $where .= " AND (c.first_name LIKE '%$search%' OR c.last_name LIKE '%$search%' OR c.national_id LIKE '%$search%')";
if ($status_filter) $where .= " AND a.status = '$status_filter'";

// 4. جلب البيانات (أحدث 10 طلبات بناءً على الفلترة)
$sql = "SELECT c.*, a.status as last_status, a.created_at as request_date
        FROM citizens c 
        JOIN appointments a ON c.national_id = a.citizen_id 
        WHERE $where
        ORDER BY a.created_at DESC 
        LIMIT $limit OFFSET $offset";

$results = mysqli_query($conn, $sql);

// 5. حساب إجمالي الصفحات
$total_q = "SELECT COUNT(*) as total FROM appointments a JOIN citizens c ON a.citizen_id = c.national_id WHERE $where";
$total_rows = mysqli_fetch_assoc(mysqli_query($conn, $total_q))['total'];
$total_pages = ceil($total_rows / $limit);
?>

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

<nav class="navbar navbar-dark bg-primary mb-4 shadow">
    <div class="container">
        <span class="navbar-brand"><i class="fas fa-desktop me-2"></i> نظام إدارة الاستقبال</span>
        <a href="logout.php" class="btn btn-outline-light btn-sm"><i class="fas fa-sign-out-alt"></i> خروج</a>
    </div>
</nav>

<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-4">
            <button class="btn btn-success shadow-sm py-2 px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-user-plus me-2"></i> إضافة مواطن جديد
            </button>
        </div>
        <div class="col-md-8">
            <form method="GET" class="row g-2 shadow-sm bg-white p-2 rounded-3 border">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control border-0" placeholder="ابحث بالاسم، اللقب أو رقم التعريف..." value="<?php echo $search; ?>">
                </div>
                <div class="col-md-4">
                    <select name="status_filter" class="form-select border-0 bg-light">
                        <option value="">كل الحالات</option>
                        <option value="pending" <?php if($status_filter=='pending') echo 'selected'; ?>>قيد الانتظار ⏳</option>
                        <option value="accepted" <?php if($status_filter=='accepted') echo 'selected'; ?>>مقبول ✅</option>
                        <option value="rejected" <?php if($status_filter=='rejected') echo 'selected'; ?>>مرفوض ❌</option>
                    </select>
                </div>
                <div class="col-md-3 text-start">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> تصفية</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card main-card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th class="text-end px-4">المواطن</th>
                        <th>رقم الهوية</th>
                        <th>تاريخ الإدخال</th>
                        <th>الحالة</th>
                        <th>إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($results)): ?>
                    <tr class="clickable-row" onclick="viewHistory('<?php echo $row['national_id']; ?>')">
                        <td class="text-end px-4">
                            <i class="fas <?php echo ($row['gender']=='female'?'fa-female text-danger':'fa-male text-primary'); ?> me-2"></i>
                            <strong><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></strong>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo $row['national_id']; ?></span></td>
                        <td><small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($row['request_date'])); ?></small></td>
                        <td>
                            <?php 
                            $badge = ($row['last_status'] == 'accepted') ? 'success' : (($row['last_status'] == 'rejected') ? 'danger' : 'warning');
                            $text = ($row['last_status'] == 'accepted') ? 'مقبول' : (($row['last_status'] == 'rejected') ? 'مرفوض' : 'انتظار');
                            echo "<span class='badge bg-$badge px-3'>$text</span>";
                            ?>
                        </td>
                        <td><button class="btn btn-sm btn-link"><i class="fas fa-chevron-left text-primary"></i></button></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
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

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg border-0">
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> تسجيل مواطن وطلب جديد</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 row text-end">
                    <div class="col-md-6 mb-3"><label>رقم الهوية</label><input type="text" name="national_id" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>رقم الهاتف</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>الاسم</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>اللقب</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="col-md-6 mb-3">
                        <label>الجنس</label>
                        <select name="gender" class="form-select"><option value="male">ذكر</option><option value="female">أنثى</option></select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>توجيه للمدير</label>
                        <select name="manager_id" class="form-select">
                            <?php 
                            $mgrs = mysqli_query($conn, "SELECT id, full_name FROM users WHERE role='manager'");
                            while($m = mysqli_fetch_assoc($mgrs)) echo "<option value='".$m['id']."'>".$m['full_name']."</option>";
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3"><label>سبب المقابلة</label><textarea name="message" class="form-control" rows="2"></textarea></div>
                    <div class="col-md-12 mb-3"><label>مرفقات (اختياري)</label><input type="file" name="attachment" class="form-control"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" name="save_citizen" class="btn btn-success px-5 py-2">حفظ وإرسال الطلب</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content shadow-lg border-0" id="historyContent"></div></div>
</div>

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