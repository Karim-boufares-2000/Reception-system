<?php
include('db.php');
$id = mysqli_real_escape_string($conn, $_GET['national_id']);

$citizen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM citizens WHERE national_id='$id'"));
$apps = mysqli_query($conn, "SELECT a.*, u.full_name FROM appointments a JOIN users u ON a.manager_id=u.id WHERE a.citizen_id='$id' ORDER BY a.created_at DESC");
?>

<div class="modal-header bg-primary text-white">
    <h5 class="modal-title"><i class="fas fa-file-invoice"></i> ملف المواطن: <?php echo $citizen['first_name'].' '.$citizen['last_name']; ?></h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="row text-end mb-4">
        <div class="col-md-4 border-start">
            <h6 class="text-primary fw-bold">البيانات الشخصية</h6>
            <hr>
            <p><strong>الاسم واللقب:</strong> <?php echo $citizen['first_name'].' '.$citizen['last_name']; ?></p>
            <p><strong>رقم الهاتف:</strong> <?php echo $citizen['phone']; ?></p>
            <p><strong>رقم التعريف:</strong> <?php echo $citizen['national_id']; ?></p>
        </div>
        
        <div class="col-md-8">
            <h6 class="text-primary fw-bold">معاينة أحدث وثيقة مرفقة</h6>
            <hr>
            <?php 
            $latest_file = mysqli_fetch_assoc(mysqli_query($conn, "SELECT files_path FROM appointments WHERE citizen_id='$id' AND files_path != '' ORDER BY created_at DESC LIMIT 1"));
            if($latest_file): 
                $file = $latest_file['files_path'];
                $ext = pathinfo($file, PATHINFO_EXTENSION);
            ?>
                <?php if($ext == 'pdf'): ?>
                    <iframe src="<?php echo $file; ?>" width="100%" height="400px" class="rounded border shadow-sm"></iframe>
                <?php elseif(in_array($ext, ['jpg','png','jpeg'])): ?>
                    <img src="<?php echo $file; ?>" class="img-fluid rounded border shadow-sm">
                <?php else: ?>
                    <div class="alert alert-secondary">ملف Word: <a href="<?php echo $file; ?>" class="btn btn-sm btn-dark" download>تحميل لمشاهدة الملف</a></div>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-muted">لا توجد وثائق مرفقة.</p>
            <?php endif; ?>
        </div>
    </div>

    <h6 class="text-primary fw-bold mb-3">سجل جميع طلبات المواطن السابقة</h6>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-center align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>تاريخ الطلب</th>
                    <th>المدير المعالج</th>
                    <th>سبب المقابلة</th>
                    <th>الحالة</th>
                    <th>رد المدير</th>
                </tr>
            </thead>
            <tbody>
                <?php mysqli_data_seek($apps, 0); while($a = mysqli_fetch_assoc($apps)): ?>
                <tr>
                    <td><?php echo date('Y-m-d', strtotime($a['created_at'])); ?></td>
                    <td><?php echo $a['full_name']; ?></td>
                    <td><small><?php echo $a['message']; ?></small></td>
                    <td>
                        <?php 
                        $cl = ($a['status']=='accepted')?'success':(($a['status']=='rejected')?'danger':'warning');
                        echo "<span class='badge bg-$cl'>".$a['status']."</span>";
                        ?>
                    </td>
                    <td><?php echo $a['manager_response'] ?? '---'; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>