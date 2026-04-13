<?php 
require_once('fetch_history_logic.php'); 

// التعامل مع حالات الخطأ قبل عرض المحتوى
if (isset($error_message)) {
    echo "<div class='alert alert-danger m-3 text-center'>$error_message</div>";
    exit;
}
?>
<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>

<div class="modal-header bg-dark text-white border-0 shadow-sm" >
    <div class="d-flex align-items-center">
        <div class="bg-primary rounded-circle p-2 ms-3 text-white">
            <i class="fas fa-folder-open"></i>
        </div>
        <h5 class="modal-title fw-bold">الأرشيف الكامل: <?= $c['first_name'] . ' ' . $c['last_name']; ?></h5>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4 bg-light">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body py-2 px-4">
            <div class="row align-items-center">
                <div class="col-md-4 text-start">
                    <small class="text-muted d-block">رقم الهوية:</small>
                    <span class="fw-bold"><?= $c['national_id']; ?></span>
                </div>
                <div class="col-md-4 text-start">
                    <small class="text-muted d-block">رقم الهاتف:</small>
                    <span class="fw-bold text-primary"><?= $c['phone']; ?></span>
                </div>
                <div class="col-md-4 text-start">
                    <span class="badge bg-primary rounded-pill px-3"><?= $total_requests; ?> طلبات سابقة</span>
                </div>
            </div>
        </div>
    </div>

    <h6 class="fw-bold text-dark mb-3">
        <i class="fas fa-list-ul text-primary me-1"></i> اضغط على أي طلب لعرض التفاصيل:
    </h6>
    
    <div class="accordion shadow-sm rounded-4 overflow-hidden" id="historyAccordion" >
        <?php if ($total_requests > 0): $i = 0; ?>
            <?php while ($a = mysqli_fetch_assoc($app_query)): $i++; ?>
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $i; ?>">
                            <div class="d-flex justify-content-between w-100 align-items-center pe-3">
                                <span class="fw-bold small text-dark">
                                    <i class="far fa-calendar-alt me-2"></i> <?= date('Y-m-d', strtotime($a['created_at'])); ?>
                                </span>
                                <span class="text-muted small px-3">المدير: <?= $a['manager_name']; ?></span>
                                
                                <?php 
                                    $cl = ($a['status']=='accepted') ? 'success' : (($a['status']=='rejected') ? 'danger' : 'warning text-dark');
                                    $lbl = ($a['status']=='accepted') ? 'مقبول' : (($a['status']=='rejected') ? 'مرفوض' : 'انتظار');
                                ?>
                                <span class="badge bg-<?= $cl; ?> rounded-pill px-3" style="font-size: 10px;"><?= $lbl; ?></span>
                            </div>
                        </button>
                    </h2>
                    
                    <div id="collapse_<?= $i; ?>" class="accordion-collapse collapse" data-bs-parent="#historyAccordion">
                        <div class="accordion-body bg-white p-4">
                            <div class="row g-4 text-start">
                                <div class="col-md-5 border-start">
                                    <label class="fw-bold text-primary small d-block mb-2">سبب الطلب:</label>
                                    <div class="p-3 bg-light rounded-3 mb-3 small"><?= nl2br(htmlspecialchars($a['message'])); ?></div>
                                    
                                    <label class="fw-bold text-success small d-block mb-2">رد المدير:</label>
                                    <div class="p-3 bg-white border rounded-3 small italic shadow-sm">
                                        <?= $a['manager_response'] ? nl2br(htmlspecialchars($a['manager_response'])) : 'لا يوجد رد مسجل.'; ?>
                                    </div>
                                </div>

                                <div class="col-md-7 text-center">
                                    <label class="fw-bold text-secondary small d-block mb-2 text-start">الوثيقة المرفقة:</label>
                                    <?php if ($a['files_path']): 
                                        $filePath = $a['files_path'];
                                        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                    ?>
                                        <div class="rounded border bg-light overflow-hidden shadow-sm" style="height: 350px;">
                                            <?php if($ext == 'pdf'): ?>
                                                <embed src="<?= $filePath; ?>#toolbar=0" type="application/pdf" width="100%" height="100%">
                                            <?php elseif(in_array($ext, ['jpg','png','jpeg','webp'])): ?>
                                                <img src="<?= $filePath; ?>" class="img-fluid h-100 object-fit-contain p-2">
                                            <?php else: ?>
                                                <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-muted">
                                                    <i class="fas fa-file-download fa-3x mb-2"></i>
                                                    <a href="<?= $filePath; ?>" class="btn btn-primary btn-sm rounded-pill px-4" download>تحميل الملف</a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-light border-dashed py-5 text-muted small">
                                            <i class="fas fa-times-circle d-block fa-2x mb-2 opacity-25"></i>
                                            لا توجد وثائق مرفقة.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center py-5 text-muted bg-white rounded-4 border">لا توجد طلبات سابقة لهذا المواطن.</div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-footer border-0 bg-white">
    <button type="button" class="btn btn-secondary px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">إغلاق الأرشيف</button>
</div>