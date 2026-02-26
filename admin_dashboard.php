<?php
include('db.php');
session_start();
if ($_SESSION['role'] != 'admin') header('Location: login.php');

// إضافة مستخدم جديد
if (isset($_POST['add_user'])) {
    $name = $_POST['full_name'];
    $rank = $_POST['rank'];
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $role = $_POST['role'];
    
    mysqli_query($conn, "INSERT INTO users (full_name, rank, username, password, role) VALUES ('$name', '$rank', '$user', '$pass', '$role')");
}

$users = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <title>إدارة النظام</title>
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5>إضافة موظف/مدير جديد</h5>
                <form method="POST">
                    <input type="text" name="full_name" placeholder="الاسم الكامل" class="form-control mb-2" required>
                    <input type="text" name="rank" placeholder="الرتبة" class="form-control mb-2">
                    <input type="text" name="username" placeholder="اسم المستخدم" class="form-control mb-2" required>
                    <input type="password" name="password" placeholder="كلمة المرور" class="form-control mb-2" required>
                    <select name="role" class="form-select mb-2">
                        <option value="manager">مدير</option>
                        <option value="receptionist">موظف استقبال</option>
                    </select>
                    <button type="submit" name="add_user" class="btn btn-success w-100">حفظ</button>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <table class="table bg-white shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>الاسم</th>
                        <th>الرتبة</th>
                        <th>الصلاحية</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($u = mysqli_fetch_assoc($users)): ?>
                    <tr>
                        <td><?php echo $u['full_name']; ?></td>
                        <td><?php echo $u['rank']; ?></td>
                        <td><?php echo $u['role']; ?></td>
                        <td>
                            <a href="delete_user.php?id=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm">حذف</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>