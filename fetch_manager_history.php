<?php
include('db.php');
$id = mysqli_real_escape_string($conn, $_GET['national_id']);

$citizen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM citizens WHERE national_id='$id'"));
$apps = mysqli_query($conn, "SELECT a.*, u.full_name FROM appointments a JOIN users u ON a.manager_id=u.id WHERE a.citizen_id='$id' ORDER BY a.created_at DESC");
?>

<div class="modal-header border-0 bg-white py-3 shadow-sm">
    <div class="d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
            <i class="fas fa-user-shield text-primary fa-lg"></i>
        </div>
        <h5 class="modal-title fw-bold text-dark mb-0">
            ملف المواطن: <span class="text-primary"><?php echo $citizen['first_name'].' '.$citizen['last_name']; ?></span>
        </h5>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body bg-light p-4">
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-2"></i> البيانات الشخصية
                    </h6>
                    <div class="small">
                        <p class="mb-2 text-muted">الاسم الكامل:</p>
                        <p class="fw-bold mb-3"><?php echo $citizen['first_name'].' '.$citizen['last_name']; ?></p>
                        
                        <p class="mb-2 text-muted">رقم الهاتف:</p>
                        <p class="fw-bold mb-3 text-primary"><i class="fas fa-phone-alt me-1"></i> <?php echo $citizen['phone']; ?></p>
                        
                        <p class="mb-2 text-muted">رقم التعريف الوطني:</p>
                        <p class="fw-bold mb-0"><code><?php echo $citizen['national_id']; ?></code></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="fas fa-file-alt text-primary me-2"></i> معاينة أحدث وثيقة مرفقة
                    </h6>
                    <div class="bg-light rounded-3 overflow-hidden d-flex align-items-center justify-content-center" style="min-height: 350px;">
                        <?php 
                        $latest_file = mysqli_fetch_assoc(mysqli_query($conn, "SELECT files_path FROM appointments WHERE citizen_id='$id' AND files_path != '' ORDER BY created_at DESC LIMIT 1"));
                        if($latest_file): 
                            $file = $latest_file['files_path'];
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        ?>
                            <?php if($ext == 'pdf'): ?>
                                <embed src="<?php echo $file; ?>" type="application/pdf" width="100%" height="400px" class="rounded">
                            <?php elseif(in_array($ext, ['jpg','png','jpeg','webp'])): ?>
                                <img src="<?php echo $file; ?>" class="img-fluid shadow-sm rounded" style="max-height: 400px; object-fit: contain;">
                            <?php else: ?>
                                <div class="text-center p-5">
                                    <i class="fas fa-file-word fa-4x text-muted mb-3"></i>
                                    <p class="text-muted">ملف مستندات</p>
                                    <a href="<?php echo $file; ?>" class="btn btn-primary rounded-pill px-4" download>تحميل الملف</a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fas fa-folder-open fa-3x mb-2 opacity-25"></i>
                                <p>لا توجد وثائق مرفقة</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fas fa-history text-primary me-2"></i> سجل المواعيد والطلبات السابقة
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="bg-light">
                    <tr class="small text-uppercase text-muted">
                        <th class="py-3">تاريخ الطلب</th>
                        <th>المدير المعالج</th>
                        <th class="text-end">السبب</th>
                        <th>الحالة</th>
                        <th>الرد</th>
                    </tr>
                </thead>
                <tbody>
                    <?php mysqli_data_seek($apps, 0); while($a = mysqli_fetch_assoc($apps)): ?>
                    <tr class="small">
                        <td class="text-muted"><?php echo date('Y-m-d', strtotime($a['created_at'])); ?></td>
                        <td class="fw-bold text-dark"><?php echo $a['full_name']; ?></td>
<td style="max-width:300px;">
    <div style="max-height:100px; overflow:auto; white-space:normal;">
        <small><?php echo nl2br(htmlspecialchars($a['message'])); ?></small>
    </div>
</td>                        <td>
                            <?php 
                            $status_arr = [
                                'accepted' => ['bg-success', 'مقبول'],
                                'rejected' => ['bg-danger', 'مرفوض'],
                                'pending'  => ['bg-warning text-dark', 'انتظار']
                            ];
                            $curr = $status_arr[$a['status']] ?? ['bg-secondary', $a['status']];
                            ?>
                            <span class="badge <?php echo $curr[0]; ?> rounded-pill px-3"><?php echo $curr[1]; ?></span>
                        </td>
                        <td class="text-muted italic small"><?php echo $a['manager_response'] ?? '<span class="opacity-50">لا يوجد رد</span>'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-footer border-0 bg-white">
    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إغلاق</button>
</div>