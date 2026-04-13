<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة المديرية | الرئيسية</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>

        body{
            font-family: 'Cairo', sans-serif;
            background:#f5f7fa;
        }

        /* Navbar */
        .navbar{
            backdrop-filter: blur(10px);
        }

        .navbar-brand{
            font-weight:700;
            font-size:22px;
        }

        /* Hero Section */

        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.65),rgba(0,0,0,0.65)),url('image/wilaya.jpg');
            background-size: cover;
            background-position:center;
            height: 80vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero-section h1{
            text-shadow:0 3px 10px rgba(0,0,0,0.4);
        }

        .hero-section p{
            font-size:20px;
        }

        /* Cards */

        .card{
            border-radius:15px;
            transition:all 0.3s ease;
            background:white;
        }

        .card:hover{
            transform:translateY(-10px);
            box-shadow:0 15px 35px rgba(0,0,0,0.15);
        }

        .card h3{
            color:#0d6efd;
            font-weight:700;
        }

        .card p{
            color:#555;
        }

        /* Section spacing */

        .features{
            margin-top:-80px;
        }

        /* Footer */

        footer{
            font-size:15px;
            letter-spacing:1px;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand" href="#"> الاستقبال الرقمية</a>
        <a href="login.php" class="btn btn-outline-light">دخول </a>
        
    </div>
</nav>

<div class="hero-section">
    <div class="container">
        <h1 class="display-3 fw-bold">أهلاً بكم في بوابة المديرية</h1>
        <p class="lead">نظام إلكتروني متكامل لإدارة طلبات المقابلات وتسهيل التواصل مع المواطنين بكل شفافية.</p>
        <hr class="my-4 border-light">
        <p>السرعة، الدقة، والخدمة المتميزة.</p>
    </div>
</div>

<div class="container my-5 text-center features">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h3>سهولة التسجيل</h3>
                <p>يتم تسجيل بيانات المواطن ورفع وثائقه في ثوانٍ معدودة عبر مكتب الاستقبال.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h3>متابعة فورية</h3>
                <p>يمكن للموظفين والمدراء متابعة حالة الطلب وتاريخ المواطن بشكل لحظي.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h3>تنظيم المقابلات</h3>
                <p>توزيع ذكي للطلبات على المدراء المختصين مع إشعارات القبول والرفض.</p>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>جميع الحقوق محفوظة &copy; 2026 - مديرية الاستقبال</p>
</footer>

</body>
</html>