<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">تعديل بيانات المستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-end">
                    <input type="hidden" name="user_id" id="edit_id">
                    <div class="mb-3">
                        <label>الاسم الكامل</label>
                        <input type="text" name="full_name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>الرتبة</label>
                        <input type="text" name="rank" id="edit_rank" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>اسم المستخدم</label>
                        <input type="text" name="username" id="edit_user" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>كلمة المرور (اتركها فارغة إذا لم ترد التغيير)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>الصلاحية</label>
                        <select name="role" id="edit_role" class="form-select">
                            <option value="admin">admin</option>
                            <option value="manager">manager</option>
                            <option value="receptionist">receptionist</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" name="update_user" class="btn btn-warning">تحديث البيانات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function editUser(user) {
    // تعبئة البيانات في الحقول داخل المودال
    document.getElementById('edit_id').value = user.id;
    document.getElementById('edit_name').value = user.full_name;
    document.getElementById('edit_rank').value = user.rank;
    document.getElementById('edit_user').value = user.username;
    document.getElementById('edit_role').value = user.role;
    
    // إظهار المودال
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}
</script>
</body>
</html>