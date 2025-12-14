<?php if ($totalPages > 1): ?>
<nav class="pagination">
    <?php if ($hasPrevPage): ?>
        <a href="?page=<?= $currentPage - 1 ?>&sort=<?= $sort ?>" class="pagination__arrow">←</a>
    <?php endif; ?>

    <?php if ($hasNextPage): ?>
        <a href="?page=<?= $currentPage + 1 ?>&sort=<?= $sort ?>" class="pagination__arrow">→</a>
    <?php endif; ?>
</nav>
<?php endif; ?>