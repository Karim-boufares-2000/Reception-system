<?php
include('db.php');

// التأكد من وجود رقم التعريف في الرابط
if (!isset($_GET['national_id'])) {
    die("<div class='alert alert-danger'>خطأ: لم يتم تحديد المواطن.</div>");
}

$id = mysqli_real_escape_string($conn, $_GET['national_id']);

// 1. جلب معلومات المواطن الشخصية
$citizen_query = mysqli_query($conn, "SELECT * FROM citizens WHERE national_id = '$id'");
$c = mysqli_fetch_assoc($citizen_query);

if (!$c) {
    die("<div class='alert alert-warning'>عذراً، لم يتم العثور على بيانات هذا المواطن.</div>");
}

// 2. جلب جميع طلبات المواطن (من الأحدث إلى الأقدم)
$app_query = mysqli_query($conn, "SELECT a.*, u.full_name as manager_name 
                                 FROM appointments a 
                                 JOIN users u ON a.manager_id = u.id 
                                 WHERE a.citizen_id = '$id' 
                                 ORDER BY a.created_at DESC");
?>

<div class="modal-header bg-primary text-white border-0">
    <h5 class="modal-title">
        <i class="fas fa-history me-2"></i> 
        سجل طلبات المواطن: <?php echo $c['first_name'] . ' ' . $c['last_name']; ?>
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body p-4 text-end">
    <div class="row mb-4 bg-light p-3 rounded shadow-sm border">
        <div class="col-md-4">
            <span class="text-muted d-block small">رقم الهاتف:</span>
            <strong><?php echo $c['phone']; ?></strong>
        </div>
        <div class="col-md-4">
            <span class="text-muted d-block small">رقم التعريف الوطني:</span>
            <strong><?php echo $c['national_id']; ?></strong>
        </div>
        <div class="col-md-4">
            <span class="text-muted d-block small">إجمالي الطلبات:</span>
            <span class="badge bg-primary rounded-pill"><?php echo mysqli_num_rows($app_query); ?> طلبات</span>
        </div>
    </div>

    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-list-ul me-1"></i> تفاصيل جميع الزيارات والطلبات:</h6>
    
    <div class="table-responsive">
        <table class="table table-hover border align-middle">
            <thead class="table-dark">
                <tr>
                    <th>تاريخ الإدخال</th>
                    <th>المدير الموجه إليه</th>
                    <th>سبب المقابلة</th>
                    <th>الحالة</th>
                    <th>رد المدير النهائي</th>
                    <th>المرفقات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($app_query) > 0): ?>
                    <?php while ($a = mysqli_fetch_assoc($app_query)): ?>
                        <tr <?php if($a['status'] == 'pending') echo 'class="table-warning"'; ?>>
                            <td>
                                <span class="small"><?php echo date('Y-m-d', strtotime($a['created_at'])); ?></span><br>
                                <small class="text-muted"><?php echo date('H:i', strtotime($a['created_at'])); ?></small>
                            </td>
                            <td><strong><?php echo $a['manager_name']; ?></strong></td>
                            <td><div style="max-width: 150px;" class="text-truncate" title="<?php echo $a['message']; ?>"><?php echo $a['message']; ?></div></td>
                            <td>
                                <?php 
                                    $status_map = [
                                        'pending' => ['label' => 'قيد الانتظار', 'color' => 'warning'],
                                        'accepted' => ['label' => 'مقبول', 'color' => 'success'],
                                        'rejected' => ['label' => 'مرفوض', 'color' => 'danger']
                                    ];
                                    $curr = $status_map[$a['status']];
                                    echo "<span class='badge bg-" . $curr['color'] . "'>" . $curr['label'] . "</span>";
                                ?>
                            </td>
                            <td class="bg-white">
                                <em class="text-primary"><?php echo $a['manager_response'] ? $a['manager_response'] : '<span class="text-muted">لم يتم الرد بعد</span>'; ?></em>
                            </td>
                            <td>
                                <?php if ($a['files_path']): ?>
                                    <a href="<?php echo $a['files_path']; ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">لا يوجد</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-3">لا توجد طلبات سابقة لهذا المواطن.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
</div>