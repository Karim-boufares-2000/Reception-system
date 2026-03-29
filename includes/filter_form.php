<form method="GET" class="row g-2 shadow-sm bg-white p-2 rounded-3 border">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control border-0" placeholder="بحث باسم المواطن أو رقم الهوية..." value="<?php echo $search; ?>">
    </div>
    <div class="col-md-4">
        <select name="status_filter" class="form-select border-0 bg-light">
            <option value="">كل الطلبات</option>
            <option value="pending" <?php echo ($status_filter=='pending') ? 'selected' : ''; ?>>قيد الانتظار ⏳</option>
            <option value="accepted" <?php echo ($status_filter=='accepted') ? 'selected' : ''; ?>>المقبولة ✅</option>
            <option value="rejected" <?php echo ($status_filter=='rejected') ? 'selected' : ''; ?>>المرفوضة ❌</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-dark w-100"><i class="fas fa-search"></i> تصفية</button>
    </div>
</form>