<?php require_once('profile_logic.php'); ?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بياناتي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Cairo', sans-serif; }
        .card { border-radius: 15px; border: none; }
        .card-header { border-radius: 15px 15px 0 0 !important; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <?php if($success_msg): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= $success_msg ?></div>
            <?php endif; ?>

            <?php if($error_msg): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= $error_msg ?></div>
            <?php endif; ?>

            <div class="card shadow-lg">
                <div class="card-header bg-dark text-white p-3 text-center">
                    <h5 class="mb-0">إعدادات الملف الشخصي</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">الاسم الكامل</label>
                            <input type="text" name="full_name" class="form-control" 
                                   value="<?= htmlspecialchars($user_data['full_name']) ?>" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">كلمة المرور الجديدة</label>
                            <input type="password" name="password" class="form-control" 
                                   placeholder="اتركها كما هي أو أدخل كلمة سر جديدة" required>
                            <small class="text-muted">نصيحة: استخدم مزيجاً من الحروف والأرقام.</small>
                        </div>

                        <button type="submit" name="update_profile" class="btn btn-primary w-100 py-2">
                            حفظ التغييرات
                        </button>
                        
                        <hr>
                        
                        <a href="admin_dashboard.php" class="btn btn-link w-100 text-decoration-none text-muted small">
                            <i class="fas fa-arrow-right"></i> العودة للوحة التحكم
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>