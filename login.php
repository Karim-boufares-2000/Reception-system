<?php require_once('login_logic.php'); ?>
<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - نظام الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('image/wilaya.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            border-radius: 15px;
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
        }

        .btn-login {
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
        }

        /* ألوان إضافية */
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-outline-secondary {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-secondary:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 10px;
        }

        h3.fw-bold {
            color: #155724;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card login-card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-lock fa-3x text-success mb-2"></i>
                        <h3 class="fw-bold">دخول الموظفين</h3>
                    </div>

                    <?php if($error): ?>
                        <div class="alert alert-danger py-2 small text-center"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">اسم المستخدم</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="أدخل اسم المستخدم" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-key text-muted"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" name="login" class="btn btn-primary btn-login w-100 py-2 mb-3">
                            دخول <i class="fas fa-sign-in-alt ms-1"></i>
                        </button>

                        <a href="index.php" class="btn btn-outline-secondary w-100 py-2 btn-login">
                            <i class="fas fa-arrow-right me-1"></i> العودة للرئيسية
                        </a>
                    </form>
                </div>
            </div>
            <p class="text-center mt-3 text-white small">جميع الحقوق محفوظة &copy; 2026</p>
        </div>
    </div>
</div>
</body>
</html>