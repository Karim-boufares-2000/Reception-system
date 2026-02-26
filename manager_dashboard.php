<?php
include('db.php');
session_start();
if ($_SESSION['role'] != 'manager') header('Location: login.php');

$manager_id = $_SESSION['user_id'];
$query = "SELECT a.*, c.first_name, c.last_name, c.photo_path 
          FROM appointments a 
          JOIN citizens c ON a.citizen_id = c.national_id 
          WHERE a.manager_id = '$manager_id' AND a.status = 'pending'";
$requests = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <title>لوحة المدير</title>
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">مرحباً حضرة المدير: <?php echo $_SESSION['full_name']; ?></span>
        <a href="logout.php" class="btn btn-danger btn-sm">خروج</a>
    </div>
</nav>

<div class="container">
    <h4>طلبات المقابلة الجديدة</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>المواطن</th>
                <th>الرسالة</th>
                <th>سجل المواطن</th>
                <th>القرار</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($requests)): ?>
            <tr>
                <td>
                    <img src="<?php echo $row['photo_path']; ?>" width="50" class="rounded-circle">
                    <?php echo $row['first_name'] . ' ' . $row['last_name']; ?>
                </td>
                <td><?php echo $row['message']; ?></td>
                <td>
                    <?php 
                        $c_id = $row['citizen_id'];
                        $history_q = mysqli_query($conn, "SELECT status, manager_id FROM appointments WHERE citizen_id='$c_id' AND id != '".$row['id']."'");
                        while($h = mysqli_fetch_assoc($history_q)) {
                            $color = ($h['status'] == 'accepted') ? 'success' : 'danger';
                            echo "<span class='badge bg-$color'>تمت معالجته مسبقاً</span> ";
                        }
                    ?>
                </td>
                <td>
                    <form action="update_status.php" method="POST" class="d-flex gap-2">
                        <input type="hidden" name="app_id" value="<?php echo $row['id']; ?>">
                        <select name="status" class="form-select form-select-sm">
                            <option value="accepted">قبول</option>
                            <option value="rejected">رفض</option>
                        </select>
                        <input type="text" name="response" placeholder="ردك.." class="form-control form-control-sm">
                        <button type="submit" class="btn btn-primary btn-sm">حفظ</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>