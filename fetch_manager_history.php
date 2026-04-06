<?php include('fetch_manager_history_logic.php'); ?>

<?php if ($error): ?>
    <div class='alert alert-danger text-center p-4 m-3'><?= $error ?></div>
    <?php exit; ?>
<?php endif; ?>

<div class="modal-header bg-dark text-white border-0 shadow-sm">
    <div class="d-flex align-items-center">
        <div class="bg-primary rounded-circle p-2 ms-3 text-white">
            <i class="fas fa-folder-open"></i>
        </div>
        <h5 class="modal-title fw-bold">الأرشيف الكامل: <?= htmlspecialchars($citizen['first_name'] . ' ' . $citizen['last_name']); ?></h5>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-4 bg-light text-end">
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-end">
        <div class="card-body py-2 px-4">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <small class="text-muted d-block">رقم الهوية:</small>
                    <span class="fw-bold"><?= $citizen['national_id']; ?></span>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">رقم الهاتف:</small>
                    <span class="fw-bold text-primary"><?= $citizen['phone']; ?></span>
                </div>
                <div class="col-md-4 text-start">
                    <span class="badge bg-primary rounded-pill px-3"><?= count($appointments); ?> طلبات سابقة</span>
                </div>
            </div>
        </div>
    </div>

    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-list-ul text-primary me-2"></i> سجل الطلبات والوثائق:</h6>
    
    <div class="accordion shadow-sm rounded-4 overflow-hidden text-end" id="historyAccordion">
        <?php if (!empty($appointments)): ?>
            <?php foreach ($appointments as $index => $app): ?>
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $index; ?>">
                            <div class="d-flex justify-content-between w-100 align-items-center pe-3">
                                <span class="fw-bold small text-dark"><i class="far fa-calendar-alt me-2"></i> <?= date('Y-m-d', strtotime($app['created_at'])); ?></span>
                                <span class="text-muted small px-3">المدير: <?= htmlspecialchars($app['manager_name']); ?></span>
                                
                                <?php 
                                    $status_map = [
                                        'accepted' => ['class' => 'success', 'label' => 'مقبول'],
                                        'rejected' => ['class' => 'danger', 'label' => 'مرفوض'],
                                        'pending'  => ['class' => 'warning text-dark', 'label' => 'انتظار']
                                    ];
                                    $status = $status_map[$app['status']] ?? $status_map['pending'];
                                ?>
                                <span class="badge bg-<?= $status['class']; ?> rounded-pill px-3 shadow-sm" style="font-size: 10px;"><?= $status['label']; ?></span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse_<?= $index; ?>" class="accordion-collapse collapse" data-bs-parent="#historyAccordion">
                        <div class="accordion-body bg-white p-4">
                            <div class="row g-4">
                                <div class="col-md-5 border-start">
                                    <label class="fw-bold text-primary small d-block mb-2">سبب الطلب:</label>
                                    <div class="p-3 bg-light rounded-3 mb-3 small"><?= nl2br(htmlspecialchars($app['message'])); ?></div>
                                    
                                    <label class="fw-bold text-success small d-block mb-2">رد المدير:</label>
                                    <div class="p-3 bg-white border rounded-3 small italic shadow-sm">
                                        <?= $app['manager_response'] ? nl2br(htmlspecialchars($app['manager_response'])) : 'لم يتم الرد بعد.'; ?>
                                    </div>
                                </div>

                                <div class="col-md-7 text-center border-end">
                                    <label class="fw-bold text-secondary small d-block mb-2 text-end">المرفقات:</label>
                                    <?php if ($app['files_path']): 
                                        $ext = strtolower(pathinfo($app['files_path'], PATHINFO_EXTENSION));
                                    ?>
                                        <div class="rounded border bg-light overflow-hidden shadow-sm" style="height: 350px;">
                                            <?php if($ext == 'pdf'): ?>
                                                <embed src="<?= $app['files_path']; ?>#toolbar=0" type="application/pdf" width="100%" height="100%">
                                            <?php elseif(in_array($ext, ['jpg','png','jpeg','webp'])): ?>
                                                <img src="<?= $app['files_path']; ?>" class="img-fluid h-100 object-fit-contain p-2">
                                            <?php else: ?>
                                                <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-muted">
                                                    <i class="fas fa-file-download fa-3x mb-2 text-primary"></i>
                                                    <p>ملف <?= strtoupper($ext); ?> لا يدعم المعاينة</p>
                                                    <a href="<?= $app['files_path']; ?>" class="btn btn-primary btn-sm rounded-pill px-4" download>تحميل المستند</a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-light border-dashed py-5 text-muted small text-center">
                                            <i class="fas fa-file-excel d-block fa-3x mb-2 opacity-25"></i>
                                            لا توجد مرفقات.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 text-muted bg-white rounded-4 border shadow-sm">لا يوجد سجل طلبات لهذا المواطن.</div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-footer border-0 bg-white shadow-sm">
    <button type="button" class="btn btn-secondary px-4 py-2 rounded-3 fw-bold shadow-sm" data-bs-dismiss="modal">إغلاق الأرشيف</button>
</div>