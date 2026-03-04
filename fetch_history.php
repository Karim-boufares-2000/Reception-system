<?php
include('db.php');
$id = mysqli_real_escape_string($conn, $_GET['national_id']);
$citizen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM citizens WHERE national_id='$id'"));
$apps = mysqli_query($conn, "SELECT a.*, u.full_name FROM appointments a JOIN users u ON a.manager_id=u.id WHERE a.citizen_id='$id' ORDER BY a.created_at DESC LIMIT 1");
$latest = mysqli_fetch_assoc($apps);
?>

<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">ملف المواطن: <?php echo $citizen['first_name'].' '.$citizen['last_name']; ?></h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="p-4 mb-4 rounded shadow-sm border-start border-4 <?php echo ($latest['status']=='accepted')?'border-success bg-success-subtle':'border-danger bg-danger-subtle'; ?>">
        <h6 class="fw-bold"><i class="fas fa-comment-dots"></i> رد المدير المعالج (<?php echo $latest['full_name']; ?>):</h6>
        <p class="fs-5 mt-2">
            <?php echo (!empty($latest['manager_response'])) ? $latest['manager_response'] : "لم يتم الرد على الطلب بعد، الحالة الحالية: <b>" . $latest['status'] . "</b>"; ?>
        </p>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="p-3 border rounded">
                <small class="text-muted d-block">رقم الهاتف:</small>
                <strong><?php echo $citizen['phone']; ?></strong>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-3 border rounded">
                <small class="text-muted d-block">المرفقات:</small>
                <?php if($latest['files_path']): ?>
                    <a href="<?php echo $latest['files_path']; ?>" target="_blank" class="btn btn-sm btn-link p-0 text-decoration-none"><i class="fas fa-file-pdf"></i> عرض المرفق المرفوع</a>
                <?php else: ?>
                    <span class="text-muted">لا توجد مرفقات</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>