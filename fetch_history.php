<?php
include('db.php');
$id = $_GET['national_id'];
$c = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM citizens WHERE national_id='$id'"));
$apps = mysqli_query($conn, "SELECT a.*, u.full_name FROM appointments a JOIN users u ON a.manager_id=u.id WHERE a.citizen_id='$id' ORDER BY a.created_at DESC");
?>
<div class="modal-header bg-dark text-white"><h5>سجل المواطن: <?php echo $c['first_name']; ?></h5></div>
<div class="modal-body text-end">
    <p>رقم الهاتف: <?php echo $c['phone']; ?> | رقم التعريف: <?php echo $c['national_id']; ?></p>
    <table class="table table-sm">
        <thead><tr><th>التاريخ</th><th>المدير</th><th>الحالة</th><th>المرفق</th></tr></thead>
        <tbody>
            <?php while($a = mysqli_fetch_assoc($apps)): ?>
            <tr>
                <td><?php echo date('Y-m-d', strtotime($a['created_at'])); ?></td>
                <td><?php echo $a['full_name']; ?></td>
                <td><?php echo $a['status']; ?></td>
                <td>
                    <?php if($a['files_path']): ?>
                        <a href="<?php echo $a['files_path']; ?>" target="_blank">تحميل</a>
                    <?php else: ?> - <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>