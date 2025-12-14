<?php if ($totalPages > 1): ?>
<nav class="pagination">

    <?php if ($hasPrevPage): ?>
        <a class="pagination__arrow" href="?page=<?= $currentPage - 1 ?>">←</a>
    <?php endif; ?>

    <?php if ($hasNextPage): ?>
        <a class="pagination__arrow" href="?page=<?= $currentPage + 1 ?>">→</a>
    <?php endif; ?>

</nav>
<?php endif; ?>