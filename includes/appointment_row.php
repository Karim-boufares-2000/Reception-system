<tr class="clickable-row shadow-sm" 
    onclick="viewFullProfile('<?php echo $row['national_id']; ?>')" 
    style="cursor:pointer; transition: background-color 0.2s;">
    <!-- اسم المواطن مع أيقونة الجنس -->
    <td>
        <i class="fas <?php echo ($row['gender'] == 'female' ? 'fa-female text-danger' : 'fa-male text-success'); ?> me-2"></i>
        <strong class="text-dark"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></strong>
    </td>

    <!-- رقم الهوية -->
    <td>
        <span class="badge bg-light text-dark border border-success px-2 py-1 rounded-pill">
            <?php echo htmlspecialchars($row['national_id']); ?>
        </span>
    </td>

    <!-- تاريخ الطلب -->
    <td><small class="text-muted"><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></small></td>

    <!-- حالة الطلب -->
    <td>
        <?php 
            $status_map = [
                'accepted' => ['class'=>'success', 'label'=>'مقبول'],
                'rejected' => ['class'=>'danger', 'label'=>'مرفوض'],
                'pending'  => ['class'=>'warning text-dark', 'label'=>'انتظار']
            ];
            $status = $status_map[$row['status']] ?? $status_map['pending'];
        ?>
        <span class="badge bg-<?= $status['class']; ?> status-badge px-3 py-1 rounded-pill shadow-sm">
            <?= $status['label']; ?>
        </span>
    </td>

    <!-- إجراء الرد -->
    <td>
        <?php if($row['status'] == 'pending'): ?>
            <button class="btn btn-sm btn-success rounded-pill px-3 fw-bold" 
                    onclick="event.stopPropagation(); openDecisionModal(<?php echo $row['id']; ?>)">
                <i class="fas fa-reply me-1"></i> الرد
            </button>
        <?php else: ?>
            <i class="fas fa-check-double text-success" title="تمت المعالجة"></i>
        <?php endif; ?>
    </td>
</tr>

<style>
    /* تأثير عند المرور على الصف */
    .clickable-row:hover {
        background-color: #e6f4ea; /* أخضر فاتح */
    }

    /* تحسين شكل البادجات */
    .status-badge {
        font-size: 0.85rem;
    }

    /* زر الرد */
    .btn-success:hover {
        background-color: #218838 !important;
        border-color: #1e7e34 !important;
    }
</style>