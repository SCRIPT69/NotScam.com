<?php
declare(strict_types=1);

require_once __DIR__ . '/../dbh.php';
require_once __DIR__ . '/../products/product_model.php';

/**
 * Připraví data pro hlavní stránku (produkty + stránkování).
 */

$perPage = 9;

$currentPage = isset($_GET['page']) && ctype_digit($_GET['page'])
    ? (int)$_GET['page']
    : 1;

// řazení
$sort = ($_GET['sort'] ?? 'old') === 'old' ? 'old' : 'new';

$totalProducts = getProductsCount($pdo);
$totalPages = (int)ceil($totalProducts / $perPage);

$currentPage = max($currentPage, 1);
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;


$products = getProductsPaginated($pdo, $perPage, $offset, $sort);

$hasPrevPage = $currentPage > 1;
$hasNextPage = $currentPage < $totalPages;