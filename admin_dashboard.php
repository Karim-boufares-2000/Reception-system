<?php 
include('admin_actions.php'); 
include('admin_header.php'); 
include 'includes/bootstrap.php'; 

// جلب الموظفين مع ترتيب الأحدث أولاً
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
?>
<?php include 'includes/icons.php'; ?>

<div class="container mt-4">
    <div class="row mb-4 align-items-center bg-white p-3 shadow-sm rounded-4 border-end border-primary border-5">
        <div class="col-md-6 text-end">
            <h4 class="mb-0 fw-bold text-dark">
                <i class="fas fa-users-cog text-primary me-2"></i> إدارة حسابات الموظفين
            </h4>
        </div>
        <div class="col-md-6 text-start">
            <button class="btn btn-primary px-4 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus-circle me-1"></i> إضافة موظف جديد
            </button>
        </div>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show text-end" role="alert">
            تمت إضافة الموظف بنجاح ✅
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th class="text-end px-4">الموظف</th>
                        <th>اسم المستخدم</th>
                        <th>الصلاحية</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($u = mysqli_fetch_assoc($users)): ?>
                    <tr <?php echo ($u['status'] == 'inactive') ? 'style="background-color: #f8f9fa; opacity: 0.8;"' : ''; ?>>
                        <td class="text-end px-4">
                            <div class="fw-bold"><?php echo htmlspecialchars($u['full_name']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($u['rank']); ?></small>
                        </td>
                        <td><code class="text-primary fw-bold"><?php echo htmlspecialchars($u['username']); ?></code></td>
                        <td>
                            <?php 
                                $role_badge = ($u['role'] == 'admin') ? 'bg-danger' : (($u['role'] == 'manager') ? 'bg-primary' : 'bg-info');
                                echo "<span class='badge $role_badge rounded-pill px-3 shadow-sm'>".$u['role']."</span>";
                            ?>
                        </td>
                        <td>
                            <?php if($u['status'] == 'active'): ?>
                                <span class="text-success small fw-bold">
                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i> متصل/نشط
                                </span>
                            <?php else: ?>
                                <span class="text-danger small fw-bold">
                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i> معطل
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($u['id'] != 1): ?>
                                <button class="btn btn-sm btn-outline-warning mx-1 border-0" 
                                        onclick='editUser(<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, "UTF-8"); ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>

                                <?php if($u['status'] == 'active'): ?>
                                    <a href="admin_actions.php?toggle_status=<?php echo $u['id']; ?>&current=active" class="btn btn-sm btn-success px-3 rounded-pill">
                                        <i class="fas fa-user-check"></i> نشط
                                    </a>
                                <?php else: ?>
                                    <a href="admin_actions.php?toggle_status=<?php echo $u['id']; ?>&current=inactive" class="btn btn-sm btn-secondary px-3 rounded-pill">
                                        <i class="fas fa-user-slash"></i> معطل
                                    </a>
                                <?php endif; ?>

                            <?php else: ?>
                                <span class="badge bg-dark text-warning py-2 px-4 rounded-pill shadow-sm">
                                    <i class="fas fa-crown me-1"></i> مدير النظام الأساسي
                                </span>
                            <?php endif; ?>
                        </td>

                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- نافذة إضافة موظف -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i> إضافة موظف جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="admin_actions.php">
                <div class="modal-body p-4 text-end">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">الاسم الكامل</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">الرتبة / التخصص</label>
                        <input type="text" name="rank" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">اسم المستخدم</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">صلاحية الدخول</label>
                        <select name="role" class="form-select">
                            <option value="manager">مدير قسم</option>
                            <option value="receptionist">موظف استقبال</option>
                            <option value="admin">مسؤول نظام</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4">
                    <button type="submit" name="add_user" class="btn btn-primary w-100 py-2 rounded-3 shadow">حفظ البيانات</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include('admin_footer.php'); ?> 