<?php
include('db.php');
session_start();

// التحقق من الصلاحية
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// 1. إضافة مستخدم جديد
if (isset($_POST['add_user'])) {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $rank = mysqli_real_escape_string($conn, $_POST['rank']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password']; 
    $role = $_POST['role'];
    
    mysqli_query($conn, "INSERT INTO users (full_name, rank, username, password, role) VALUES ('$name', '$rank', '$user', '$pass', '$role')");
    header("Location: admin_dashboard.php?success=1");
}

// 2. تحديث مستخدم موجود
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $rank = mysqli_real_escape_string($conn, $_POST['rank']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $role = $_POST['role'];
    
    $sql = "UPDATE users SET full_name='$name', rank='$rank', username='$user', role='$role' WHERE id='$id'";
    
    // تحديث كلمة المرور فقط إذا تم إدخال واحدة جديدة
    if (!empty($_POST['password'])) {
        $pass = $_POST['password'];
        $sql = "UPDATE users SET full_name='$name', rank='$rank', username='$user', role='$role', password='$pass' WHERE id='$id'";
    }

    mysqli_query($conn, $sql);
    header("Location: admin_dashboard.php?updated=1");
}

// 3. حذف مستخدم
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    header("Location: admin_dashboard.php?deleted=1");
}

$users = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة النظام - لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: #2c3e50; color: white; }
        .card { border: none; border-radius: 15px; }
        .table { vertical-align: middle; }
        .btn-action { border-radius: 8px; transition: 0.3s; }
        .role-badge { font-size: 0.8rem; padding: 5px 10px; border-radius: 20px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg shadow mb-4">
    <div class="container">
        <a class="navbar-brand text-white" href="#"><i class="fas fa-user-shield me-2"></i> لوحة تحكم المسؤول</a>
        <div class="d-flex text-white">
            <span>مرحباً، <?php echo $_SESSION['full_name']; ?></span>
            <a href="logout.php" class="btn btn-sm btn-danger ms-3"><i class="fas fa-sign-out-alt"></i> خروج</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm p-4">
                <h5 class="mb-4 border-bottom pb-2 text-primary"><i class="fas fa-user-plus me-2"></i> إضافة عضو جديد</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label small">الاسم الكامل</label>
                        <input type="text" name="full_name" class="form-control" placeholder="مثال: أحمد محمد" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">الرتبة الوظيفية</label>
                        <input type="text" name="rank" class="form-control" placeholder="مثال: رئيس قسم">
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
            <div class="card shadow-sm p-4">
                <h5 class="mb-4 border-bottom pb-2 text-dark"><i class="fas fa-users me-2"></i> قائمة الموظفين والمدراء</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-light">
                                <th>الموظف</th>
                                <th>اسم المستخدم</th>
                                <th>الرتبة</th>
                                <th>الصلاحية</th>
                                <th class="text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($u = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo $u['full_name']; ?></div>
                                </td>
                                <td><code class="text-secondary"><?php echo $u['username']; ?></code></td>
                                <td><?php echo $u['rank']; ?></td>
                                <td>
                                    <?php 
                                        $role_class = ($u['role'] == 'admin') ? 'bg-danger' : (($u['role'] == 'manager') ? 'bg-primary' : 'bg-info');
                                        echo "<span class='badge role-badge $role_class'>".$u['role']."</span>";
                                    ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-outline-warning btn-sm btn-action me-1" 
                                            onclick="editUser(<?php echo htmlspecialchars(json_encode($u)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?delete=<?php echo $u['id']; ?>" class="btn btn-outline-danger btn-sm btn-action" 
                                       onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                        <i class="fas fa-trash"></i>
                                    </a>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">تعديل بيانات المستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
    document.getElementById('edit_id').value = user.id;
    document.getElementById('edit_name').value = user.full_name;
    document.getElementById('edit_rank').value = user.rank;
    document.getElementById('edit_user').value = user.username;
    document.getElementById('edit_role').value = user.role;
    
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}
</script>
</body>
</html>