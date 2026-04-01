<div class="table-responsive">
    <table class="table table-hover align-middle mb-0 text-center">
        <thead class="table-dark">
            <tr>
                <th class="text-end px-4">المواطن</th>
                <th>رقم الهوية</th>
                <th>تاريخ الإدخال</th>
                <th>الحالة</th>
                <th>إجراء</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($results)): ?>
            <tr class="clickable-row" onclick="viewHistory('<?php echo $row['national_id']; ?>')">
                <td class="text-end px-4">
                    <i class="fas <?php echo ($row['gender']=='female'?'fa-female text-danger':'fa-male text-primary'); ?> me-2"></i>
                    <strong><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong>
                </td>
                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['national_id']); ?></span></td>
                <td><small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($row['request_date'])); ?></small></td>
                <td>
                    <?php 
                    $badge = ($row['last_status'] == 'accepted') ? 'success' : (($row['last_status'] == 'rejected') ? 'danger' : 'warning');
                    $text = ($row['last_status'] == 'accepted') ? 'مقبول' : (($row['last_status'] == 'rejected') ? 'مرفوض' : 'انتظار');
                    echo "<span class='badge bg-$badge px-3'>$text</span>";
                    ?>
                </td>
                <td><button class="btn btn-sm btn-link"><i class="fas fa-chevron-left text-primary"></i></button></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>