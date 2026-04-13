<?php include('db.php'); ?>
<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة مواطن جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>تسجيل مواطن جديد للمقابلة</h4>
        </div>
        <div class="card-body">
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>رقم بطاقة التعريف (ID)</label>
                        <input type="text" name="national_id" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>الاسم</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>اللقب</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>الجنس</label>
                        <select name="gender" class="form-select" id="genderSelect">
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>تحميل صورة (اختياري)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>سبب المقابلة (الرسالة)</label>
                        <textarea name="message" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>رفع وثائق (PDF/Word)</label>
                        <input type="file" name="document" class="form-control">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>توجيه الطلب إلى المدير:</label>
                        <select name="manager_id" class="form-select">
                            <?php 
                            $res = mysqli_query($conn, "SELECT id, full_name FROM users WHERE role='manager'");
                            while($row = mysqli_fetch_assoc($res)) {
                                echo "<option value='".$row['id']."'>".$row['full_name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <button type="submit" name="save_citizen" class="btn btn-success w-100">حفظ وإرسال الطلب</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>