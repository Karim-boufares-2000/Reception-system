<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> تسجيل مواطن وطلب جديد</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 row text-end">
                    <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">رقم الهوية الوطنية</label><input type="text" name="national_id" class="form-control border-primary" required></div>
                    <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">رقم الهاتف</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">الاسم</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">اللقب</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold mb-1">الجنس</label>
                        <select name="gender" class="form-select"><option value="male">ذكر</option><option value="female">أنثى</option></select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold mb-1">توجيه للمدير المسؤول</label>
                        <select name="manager_id" class="form-select border-success">
                            <?php 
                            $mgrs = mysqli_query($conn, "SELECT id, full_name FROM users WHERE role='manager'");
                            while($m = mysqli_fetch_assoc($mgrs)) echo "<option value='".$m['id']."'>".$m['full_name']."</option>";
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3"><label class="small fw-bold mb-1">سبب المقابلة / الملاحظات</label><textarea name="message" class="form-control" rows="3"></textarea></div>
                    <div class="col-md-12 mb-0"><label class="small fw-bold mb-1">إرفاق وثائق (PDF, صور)</label><input type="file" name="attachment" class="form-control"></div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" name="save_citizen" class="btn btn-success px-5 fw-bold">حفظ وإرسال الطلب</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden" id="historyContent">
            </div>
    </div>
</div>