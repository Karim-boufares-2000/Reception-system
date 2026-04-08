<form method="GET" class="row g-2 shadow-sm bg-white p-3 rounded-3 border border-light">
    <!-- حقل البحث -->
    <div class="col-md-5">
        <input type="text" name="search" 
               class="form-control border border-success rounded-pill px-3" 
               placeholder="بحث باسم المواطن أو رقم الهوية..." 
               value="<?php echo htmlspecialchars($search); ?>">
    </div>

    <!-- قائمة الفلاتر حسب الحالة -->
    <div class="col-md-4">
        <select name="status_filter" class="form-select border border-success rounded-pill bg-light px-3">
            <option value="">كل الطلبات</option>
            <option value="pending" <?php echo ($status_filter=='pending') ? 'selected' : ''; ?>>⏳ قيد الانتظار</option>
            <option value="accepted" <?php echo ($status_filter=='accepted') ? 'selected' : ''; ?>>✅ مقبولة</option>
            <option value="rejected" <?php echo ($status_filter=='rejected') ? 'selected' : ''; ?>>❌ مرفوضة</option>
        </select>
    </div>

    <!-- زر التصفية -->
    <div class="col-md-3">
        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold shadow-sm">
            <i class="fas fa-search me-2"></i> تصفية
        </button>
    </div>
</form>

<style>
    /* تحسين التفاعل والألوان */
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25); /* ظل أخضر */
        border-color: #28a745 !important;
    }

    .btn-success:hover {
        background-color: #218838 !important;
        border-color: #1e7e34 !important;
    }

    /* Placeholder عربي مائل قليلًا لجعل القراءة أسهل */
    input::placeholder {
        font-style: italic;
        color: #6c757d;
    }
</style>