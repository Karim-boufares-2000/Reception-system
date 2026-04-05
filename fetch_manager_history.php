<?php
include('db.php');
$id = mysqli_real_escape_string($conn, $_GET['national_id']);

$citizen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM citizens WHERE national_id='$id'"));
$apps = mysqli_query($conn, "SELECT a.*, u.full_name FROM appointments a JOIN users u ON a.manager_id=u.id WHERE a.citizen_id='$id' ORDER BY a.created_at DESC");
?>

<div class="modal-header border-0 bg-white py-3 shadow-sm">
    <div class="d-flex align-items-center text-end">
        <div class="bg-primary bg-opacity-10 p-2 rounded-circle ms-3">
            <i class="fas fa-user-shield text-primary fa-lg"></i>
        </div>
        <h5 class="modal-title fw-bold text-dark mb-0">
            ملف المواطن: <span class="text-primary"><?php echo $citizen['first_name'].' '.$citizen['last_name']; ?></span>
        </h5>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body bg-light p-4 text-end">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body py-3 px-4">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <small class="text-muted d-block small">رقم الهاتف:</small>
                    <span class="fw-bold text-primary"><?php echo $citizen['phone']; ?></span>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block small">رقم التعريف:</small>
                    <code class="fw-bold text-dark"><?php echo $citizen['national_id']; ?></code>
                </div>
                <div class="col-md-4 text-start">
                    <span class="badge bg-primary rounded-pill px-3"><?php echo mysqli_num_rows($apps); ?> طلبات سابقة</span>
                </div>
            </div>
        </div>
    </div>

    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-list-ul text-primary me-2"></i> سجل المواعيد والوثائق المرفقة:</h6>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="bg-dark text-white">
                    <tr class="small">
                        <th class="py-3">التاريخ</th>
                        <th>المدير</th>
                        <th class="text-end">السبب</th>
                        <th>الحالة</th>
                        <th>الوثيقة المرفقة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($apps) > 0): ?>
                        <?php while($a = mysqli_fetch_assoc($apps)): 
                            $file = $a['files_path'];
                            $ext = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : '';
                        ?>
                        <tr>
                            <td class="small text-muted"><?php echo date('Y-m-d', strtotime($a['created_at'])); ?></td>
                            <td class="fw-bold small"><?php echo $a['full_name']; ?></td>
                            <td class="text-end small" style="max-width: 250px;">
                                <div class="text-truncate" title="<?php echo htmlspecialchars($a['message']); ?>">
                                    <?php echo htmlspecialchars($a['message']); ?>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    $cl = ($a['status']=='accepted')?'success':(($a['status']=='rejected')?'danger':'warning text-dark');
                                    $lbl = ($a['status']=='accepted')?'مقبول':(($a['status']=='rejected')?'مرفوض':'انتظار');
                                ?>
                                <span class="badge bg-<?php echo $cl; ?> rounded-pill shadow-sm" style="font-size: 10px;"><?php echo $lbl; ?></span>
                            </td>
                            <td>
                                <?php if($file): ?>
                                    <a href="<?php echo $file; ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm fw-bold">
                                        <i class="fas fa-eye me-1"></i> معاينة الوثيقة
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small italic opacity-50">لا يوجد مرفق</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="py-5 text-muted small">لا توجد طلبات سابقة.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal-footer border-0 bg-white">
    <button type="button" class="btn btn-secondary px-4 rounded-pill shadow-sm fw-bold" data-bs-dismiss="modal">إغلاق الملف</button>
</div>