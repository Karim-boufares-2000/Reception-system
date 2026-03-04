<?php
include('db.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'manager') {
    header('Location: login.php');
    exit();
}

$manager_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// جلب الطلبات الموجهة للمدير الحالي
$sql = "SELECT a.*, c.first_name, c.last_name, c.gender, c.national_id 
        FROM appointments a 
        JOIN citizens c ON a.citizen_id = c.national_id 
        WHERE a.manager_id = '$manager_id' 
        AND (c.first_name LIKE '%$search%' OR c.national_id LIKE '%$search%')
        ORDER BY a.created_at DESC";

$results = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .clickable-row { cursor: pointer; transition: 0.2s; }
        .clickable-row:hover { background-color: #eef6ff !important; }
        .status-badge { width: 100px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <span class="navbar-brand"><i class="fas fa-user-tie"></i> إدارة المقابلات | المدير: <?php echo $_SESSION['full_name']; ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">خروج</a>
    </div>
</nav>

<div class="container">
    <div class="card shadow-sm border-0 p-4">
        <h4 class="mb-4 text-primary border-bottom pb-2">طلبات المقابلة الواردة</h4>
        
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>المواطن</th>
                    <th>رقم التعريف</th>
                    <th>تاريخ الطلب</th>
                    <th>الحالة</th>
                    <th>إجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($results)): ?>
                <tr class="clickable-row" onclick="viewFullProfile('<?php echo $row['national_id']; ?>')">
                    <td class="text-end">
                        <i class="fas <?php echo ($row['gender']=='female'?'fa-female text-danger':'fa-male text-primary'); ?> me-2"></i>
                        <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?php echo $row['national_id']; ?></span></td>
                    <td><?php echo date('Y-m-d', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php 
                        $colors = ['pending'=>'warning', 'accepted'=>'success', 'rejected'=>'danger'];
                        echo "<span class='badge bg-".$colors[$row['status']]." status-badge'>".$row['status']."</span>";
                        ?>
                    </td>
                    <td>
                        <?php if($row['status'] == 'pending'): ?>
                        <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); openDecisionModal(<?php echo $row['id']; ?>)">
                            <i class="fas fa-gavel"></i> رد
                        </button>
                        <?php else: ?>
                            <i class="fas fa-check-circle text-success"></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow-lg border-0" id="profileContent">
            </div>
    </div>
</div>

<div class="modal fade" id="decisionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="update_status.php" method="POST">
                <div class="modal-header bg-primary text-white"><h5>اتخاذ قرار بشأن المقابلة</h5></div>
                <div class="modal-body text-end">
                    <input type="hidden" name="app_id" id="modal_app_id">
                    <div class="mb-3">
                        <label>الحالة</label>
                        <select name="status" class="form-select" required>
                            <option value="accepted">قبول الطلب</option>
                            <option value="rejected">رفض الطلب</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>رسالة الرد (الموعد أو سبب الرفض)</label>
                        <textarea name="response" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">حفظ القرار</button>
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