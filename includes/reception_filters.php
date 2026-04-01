<form method="GET" class="row g-2 shadow-sm bg-white p-2 rounded-3 border">
    <div class="col-md-5">
        <div class="input-group">
            <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
            <input type="text" name="search" class="form-control border-0" placeholder="ابحث بالاسم، اللقب أو رقم التعريف..." value="<?php echo $search; ?>">
        </div>
    </div>
    <div class="col-md-4">
        <select name="status_filter" class="form-select border-0 bg-light">
            <option value="">كل الحالات</option>
            <option value="pending" <?php if($status_filter=='pending') echo 'selected'; ?>>قيد الانتظار ⏳</option>
            <option value="accepted" <?php if($status_filter=='accepted') echo 'selected'; ?>>مقبول ✅</option>
            <option value="rejected" <?php if($status_filter=='rejected') echo 'selected'; ?>>مرفوض ❌</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100 fw-bold">تصفية النتائج</button>
    </div>
</form>