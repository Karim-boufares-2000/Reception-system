<?php include 'includes/bootstrap.php'; ?>
<?php include 'includes/icons.php'; ?>
<form method="GET" class="row g-2 shadow-sm bg-white p-3 rounded-3 border">
    <!-- حقل البحث -->
    <div class="col-md-5">
        <div class="input-group">
            <span class="input-group-text bg-white border-0"><i class="fas fa-search text-success"></i></span>
            <input type="text" name="search" class="form-control border-0" 
                   placeholder="ابحث بالاسم، اللقب أو رقم التعريف..." 
                   value="<?php echo htmlspecialchars($search); ?>">
        </div>
    </div>

    <!-- قائمة الحالة -->
    <div class="col-md-4">
        <select name="status_filter" class="form-select border-0" style="background-color:#e9f7ef;">
            <option value="">كل الحالات</option>
            <option value="pending" <?php if($status_filter=='pending') echo 'selected'; ?>>قيد الانتظار ⏳</option>
            <option value="accepted" <?php if($status_filter=='accepted') echo 'selected'; ?>>مقبول ✅</option>
            <option value="rejected" <?php if($status_filter=='rejected') echo 'selected'; ?>>مرفوض ❌</option>
        </select>
    </div>

    <!-- زر التصفية -->
    <div class="col-md-3">
        <button type="submit" class="btn btn-success w-100 fw-bold">
            <i class="fas fa-filter me-1"></i> تصفية النتائج
        </button>
    </div>
</form>