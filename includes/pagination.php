<nav class="mt-4">
    <ul class="pagination justify-content-center">
        <?php for($i=1; $i<=$total_pages; $i++): ?>
            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                <a class="page-link shadow-sm mx-1 rounded" 
                   href="?page=<?php echo $i; ?>&status_filter=<?php echo $status_filter; ?>&search=<?php echo $search; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>