<?php 
include('admin_actions.php'); // استدعاء ملف العمليات
include('admin_header.php');        // استدعاء الهيدر والتصميم
$users = mysqli_query($conn, "SELECT * FROM users");
?>

<div class="container">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm p-4">
                <h5 class="mb-4 border-bottom pb-2 text-primary"><i class="fas fa-user-plus me-2"></i> إضافة عضو جديد</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small">الاسم الكامل</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">الرتبة الوظيفية</label>
                        <input type="text" name="rank" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">اسم المستخدم</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small">الصلاحية</label>
                        <select name="role" class="form-select">
                            <option value="manager">مدير</option>
                            <option value="receptionist">موظف استقبال</option>
                            <option value="admin">مسؤول نظام</option>
                        </select>
                    </div>
                    <button type="submit" name="add_user" class="btn btn-primary w-100 py-2"><i class="fas fa-save me-1"></i> حفظ البيانات</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm p-4 text-center">
                <h5 class="mb-4 border-bottom pb-2 text-dark"><i class="fas fa-users me-2"></i> قائمة الموظفين</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الموظف</th>
                                <th>اسم المستخدم</th>
                                <th>الصلاحية</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($u = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td><div class="fw-bold"><?php echo $u['full_name']; ?></div></td>
                                <td><code><?php echo $u['username']; ?></code></td>
                                <td>
                                    <?php 
                                    $role_class = ($u['role'] == 'admin') ? 'bg-danger' : (($u['role'] == 'manager') ? 'bg-primary' : 'bg-info');
                                    echo "<span class='badge role-badge $role_class'>".$u['role']."</span>";
                                    ?>
                                </td>
                              <td class="text-center">
    <button class="btn btn-sm btn-outline-warning" onclick='editUser(<?php echo json_encode($u); ?>)'>
        <i class="fas fa-edit"></i>
    </button>

    <?php if ($u['status'] == 'active'): ?>
        <a href="admin_actions.php?toggle_status=<?php echo $u['id']; ?>&current=active" 
           class="btn btn-sm btn-success" title="تعطيل الحساب">
            <i class="fas fa-user-check"></i> نشط
        </a>
    <?php else: ?>
        <a href="admin_actions.php?toggle_status=<?php echo $u['id']; ?>&current=inactive" 
           class="btn btn-sm btn-secondary" title="تفعيل الحساب">
            <i class="fas fa-user-slash"></i> غير نشط
        </a>
    <?php endif; ?>
</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('admin_footer.php'); ?>