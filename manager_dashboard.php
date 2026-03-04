<?php
include('db.php');
session_start();

// التحقق من صلاحية المدير
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header('Location: login.php');
    exit();
}

$manager_id = $_SESSION['user_id'];

// 1. إعدادات تعدد الصفحات
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. إعدادات البحث والفلترة
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status_filter']) ? mysqli_real_escape_string($conn, $_GET['status_filter']) : '';

// 3. بناء جملة الاستعلام (عرض الطلبات الموجهة لهذا المدير فقط)
$where = "a.manager_id = '$manager_id'";
if ($search) $where .= " AND (c.first_name LIKE '%$search%' OR c.last_name LIKE '%$search%' OR c.national_id LIKE '%$search%')";
if ($status_filter) $where .= " AND a.status = '$status_filter'";

// 4. جلب البيانات النهائية
$sql = "SELECT a.*, c.first_name, c.last_name, c.gender, c.national_id 
        FROM appointments a 
        JOIN citizens c ON a.citizen_id = c.national_id 
        WHERE $where 
        ORDER BY a.created_at DESC 
        LIMIT $limit OFFSET $offset";

$results = mysqli_query($conn, $sql);

// 5. حساب إجمالي الصفحات للترقيم
$total_q = "SELECT COUNT(*) as total FROM appointments a JOIN citizens c ON a.citizen_id = c.national_id WHERE $where";
$total_rows = mysqli_fetch_assoc(mysqli_query($conn, $total_q))['total'];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير - الطلبات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, sans-serif; }
        .main-card { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .clickable-row { cursor: pointer; transition: 0.2s; }
        .clickable-row:hover { background-color: #f1f8ff !important; }
        .status-badge { width: 90px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <span class="navbar-brand"><i class="fas fa-user-tie me-2"></i> لوحة المدير: <?php echo $_SESSION['full_name']; ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm"><i class="fas fa-sign-out-alt"></i> خروج</a>
    </div>
</nav>

<div class="container">
    <div class="row mb-4 justify-content-end">
        <div class="col-md-9">
            <form method="GET" class="row g-2 shadow-sm bg-white p-2 rounded-3 border">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control border-0" placeholder="بحث باسم المواطن أو رقم الهوية..." value="<?php echo $search; ?>">
                </div>
                <div class="col-md-4">
                    <select name="status_filter" class="form-select border-0 bg-light">
                        <option value="">كل الطلبات</option>
                        <option value="pending" <?php if($status_filter=='pending') echo 'selected'; ?>>قيد الانتظار ⏳</option>
                        <option value="accepted" <?php if($status_filter=='accepted') echo 'selected'; ?>>المقبولة ✅</option>
                        <option value="rejected" <?php if($status_filter=='rejected') echo 'selected'; ?>>المرفوضة ❌</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-dark w-100"><i class="fas fa-search"></i> تصفية</button>
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
                        <th>تاريخ الطلب</th>
                        <th>الحالة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($results) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($results)): ?>
                        <tr class="clickable-row" onclick="viewFullProfile('<?php echo $row['national_id']; ?>')">
                            <td class="text-end px-4">
                                <i class="fas <?php echo ($row['gender']=='female'?'fa-female text-danger':'fa-male text-primary'); ?> me-2"></i>
                                <strong><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></strong>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?php echo $row['national_id']; ?></span></td>
                            <td><small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></small></td>
                            <td>
                                <?php 
                                $badge = ($row['status'] == 'accepted') ? 'success' : (($row['status'] == 'rejected') ? 'danger' : 'warning');
                                $text = ($row['status'] == 'accepted') ? 'مقبول' : (($row['status'] == 'rejected') ? 'مرفوض' : 'انتظار');
                                echo "<span class='badge bg-$badge status-badge'>$text</span>";
                                ?>
                            </td>
                            <td>
                                <?php if($row['status'] == 'pending'): ?>
                                    <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); openDecisionModal(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-reply"></i> الرد
                                    </button>
                                <?php else: ?>
                                    <i class="fas fa-check-double text-success"></i>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="py-4 text-muted">لا توجد طلبات تطابق هذا البحث.</td></tr>
                    <?php endif; ?>
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

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg border-0" id="profileContent">
            </div>
    </div>
</div>

<div class="modal fade" id="decisionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="update_status.php" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">اتخاذ قرار بشأن الطلب</h5>
                </div>
                <div class="modal-body text-end">
                    <input type="hidden" name="app_id" id="modal_app_id">
                    <div class="mb-3">
                        <label class="form-label">القرار:</label>
                        <select name="status" class="form-select" required>
                            <option value="accepted">قبول الطلب وتحديد موعد</option>
                            <option value="rejected">رفض الطلب</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الرد (سيظهر لموظف الاستقبال):</label>
                        <textarea name="response" class="form-control" rows="3" placeholder="اكتب ردك هنا..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">حفظ وإرسال الرد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function viewFullProfile(id) {
    fetch('fetch_manager_history.php?national_id=' + id)
    .then(r => r.text())
    .then(d => {
        document.getElementById('profileContent').innerHTML = d;
        new bootstrap.Modal(document.getElementById('profileModal')).show();
    });
}

function openDecisionModal(id) {
    document.getElementById('modal_app_id').value = id;
    new bootstrap.Modal(document.getElementById('decisionModal')).show();
}
</script>
</body>
</html>