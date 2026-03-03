<?php
include('db.php');
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'receptionist') header('Location: login.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$sql = "SELECT c.*, 
        MAX(a.created_at) as last_request_date,
        (SELECT status FROM appointments WHERE citizen_id = c.national_id ORDER BY created_at DESC LIMIT 1) as last_status
        FROM citizens c 
        LEFT JOIN appointments a ON c.national_id = a.citizen_id 
        WHERE c.first_name LIKE '%$search%' OR c.last_name LIKE '%$search%' OR c.national_id LIKE '%$search%'
        GROUP BY c.national_id 
        ORDER BY last_request_date DESC";

$citizens = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>لوحة الاستقبال</title>
    <style>
        body { background-color: #f8f9fa; }
        .clickable-row { cursor: pointer; transition: 0.3s; }
        .clickable-row:hover { background-color: #e9ecef !important; }
        .avatar-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary shadow mb-4">
    <div class="container">
        <span class="navbar-brand"><i class="fas fa-user-check"></i> قسم الاستقبال - أهلاً <?php echo $_SESSION['full_name']; ?></span>
        <a href="logout.php" class="btn btn-light btn-sm">خروج</a>
    </div>
</nav>

<div class="container">
    <div class="card shadow-sm border-0 p-4">
        <div class="d-flex justify-content-between mb-4">
            <h4>قائمة المواطنين</h4>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> إضافة مواطن</button>
        </div>

        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو رقم التعريف..." value="<?php echo $search; ?>">
                <button type="submit" class="btn btn-primary">بحث</button>
            </div>
        </form>

        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>المواطن</th>
                    <th>رقم التعريف</th>
                    <th>رقم الهاتف</th>
                    <th>آخر حالة</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php while($c = mysqli_fetch_assoc($citizens)): ?>
                <tr class="clickable-row" onclick="viewHistory('<?php echo $c['national_id']; ?>')">
                    <td>
                        <?php $avatar = ($c['gender'] == 'female') ? 'female_avatar.png' : 'male_avatar.png'; ?>
                        <img src="assets/<?php echo $avatar; ?>" class="avatar-img me-2">
                        <strong><?php echo $c['first_name'] . ' ' . $c['last_name']; ?></strong>
                    </td>
                    <td><?php echo $c['national_id']; ?></td>
                    <td><?php echo $c['phone']; ?></td>
                    <td>
                        <?php 
                        $status = $c['last_status'] ?? 'pending';
                        $colors = ['pending'=>'warning', 'accepted'=>'success', 'rejected'=>'danger'];
                        echo "<span class='badge bg-".$colors[$status]."'>$status</span>";
                        ?>
                    </td>
                    <td><button class="btn btn-sm btn-outline-primary">السجل</button></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-success text-white"><h5>تسجيل طلب جديد</h5></div>
                <div class="modal-body row text-end">
                    <div class="col-md-6 mb-3"><label>رقم التعريف</label><input type="text" name="national_id" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>رقم الهاتف</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>الاسم</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label>اللقب</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="col-md-6 mb-3">
                        <label>الجنس</label>
                        <select name="gender" class="form-select">
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>توجيه للمدير</label>
                        <select name="manager_id" class="form-select">
                            <?php 
                            $ms = mysqli_query($conn, "SELECT id, full_name FROM users WHERE role='manager'");
                            while($m = mysqli_fetch_assoc($ms)) echo "<option value='".$m['id']."'>".$m['full_name']."</option>";
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3"><label>الرسالة / سبب المقابلة</label><textarea name="message" class="form-control"></textarea></div>
                    <div class="col-md-12 mb-3"><label>مرفقات (اختياري - PDF/Word)</label><input type="file" name="attachment" class="form-control"></div>
                </div>
                <div class="modal-footer"><button type="submit" name="save_citizen" class="btn btn-success">حفظ الطلب</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content" id="historyContent"></div></div>
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