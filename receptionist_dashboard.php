<?php
include('db.php');
session_start();
if ($_SESSION['role'] != 'receptionist') header('Location: login.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// بناء الاستعلام بناءً على البحث والفلترة
$sql = "SELECT a.*, c.first_name, c.last_name, c.national_id 
        FROM appointments a 
        JOIN citizens c ON a.citizen_id = c.national_id WHERE 1=1";

if ($search) {
    $sql .= " AND (c.first_name LIKE '%$search%' OR c.last_name LIKE '%$search%' OR c.national_id LIKE '%$search%')";
}
if ($filter) {
    $sql .= " AND a.status = '$filter'";
}

$results = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <title>لوحة الاستقبال</title>
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة المواطنين والطلبات</h2>
        <a href="add_citizen.php" class="btn btn-success">إضافة مواطن جديد +</a>
    </div>

    <form class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو رقم التعريف..." value="<?php echo $search; ?>">
        </div>
        <div class="col-md-3">
            <select name="filter" class="form-select">
                <option value="">كل الحالات</option>
                <option value="pending" <?php if($filter=='pending') echo 'selected'; ?>>قيد الانتظار</option>
                <option value="accepted" <?php if($filter=='accepted') echo 'selected'; ?>>مقبول</option>
                <option value="rejected" <?php if($filter=='rejected') echo 'selected'; ?>>مرفوض</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">بحث / تصفية</button>
        </div>
    </form>

    <table class="table table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>رقم الهوية</th>
                <th>الاسم الكامل</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($results)): ?>
            <tr>
                <td><?php echo $row['national_id']; ?></td>
                <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                <td>
                    <?php 
                        $status_ar = ['pending'=>'قيد الانتظار', 'accepted'=>'مقبول', 'rejected'=>'مرفوض'];
                        $color = ['pending'=>'warning', 'accepted'=>'success', 'rejected'=>'danger'];
                        echo "<span class='badge bg-".$color[$row['status']]."'>".$status_ar[$row['status']]."</span>";
                    ?>
                </td>
                <td>
                    <a href="edit_citizen.php?id=<?php echo $row['national_id']; ?>" class="btn btn-info btn-sm text-white">تعديل</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>