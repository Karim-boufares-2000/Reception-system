<tr class="clickable-row" onclick="viewFullProfile('<?php echo $row['national_id']; ?>')">
    <td class="text-end px-4">
        <i class="fas <?php echo ($row['gender'] == 'female' ? 'fa-female text-danger' : 'fa-male text-primary'); ?> me-2"></i>
        <strong><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong>
    </td>
    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['national_id']); ?></span></td>
    <td><small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></small></td>
    <td>
        <?php 
        $badge = ($row['status'] == 'accepted') ? 'success' : (($row['status'] == 'rejected') ? 'danger' : 'warning');
        $text = ($row['status'] == 'accepted') ? 'مقبول' : (($row['status'] == 'rejected') ? 'مرفوض' : 'انتظار');
        echo "<span class='badge bg-$badge status-badge'>$text</span>";
        ?>
    </td>
    <td>
        <?php if($row['status'] == 'pending'): ?>
            <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); openDecisionModal(<?php echo $row['id']; ?>)">
                <i class="fas fa-reply"></i> الرد
            </button>
        <?php else: ?>
            <i class="fas fa-check-double text-success" title="تمت المعالجة"></i>
        <?php endif; ?>
    </td>
</tr>