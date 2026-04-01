<?php
include('reception_auth.php');

// 1. إعدادات تعدد الصفحات
$limit = 10; 
$page = isset($_GET['page']) ? (int)$GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. إعدادات البحث والفلترة
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status_filter']) ? mysqli_real_escape_string($conn, $_GET['status_filter']) : '';

// 3. بناء الاستعلام
$where = "1=1";
if ($search) $where .= " AND (c.first_name LIKE '%$search%' OR c.last_name LIKE '%$search%' OR c.national_id LIKE '%$search%')";
if ($status_filter) $where .= " AND a.status = '$status_filter'";

// 4. جلب البيانات
$sql = "SELECT c.*, a.status as last_status, a.created_at as request_date
        FROM citizens c 
        JOIN appointments a ON c.national_id = a.citizen_id 
        WHERE $where
        ORDER BY a.created_at DESC 
        LIMIT $limit OFFSET $offset";

$results = mysqli_query($conn, $sql);

// 5. حساب إجمالي الصفحات
$total_q = "SELECT COUNT(*) as total FROM appointments a JOIN citizens c ON a.citizen_id = c.national_id WHERE $where";
$total_rows = mysqli_fetch_assoc(mysqli_query($conn, $total_q))['total'];
$total_pages = ceil($total_rows / $limit);
?>