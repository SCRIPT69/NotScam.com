<?php
require_once __DIR__ . '/includes/session_manager.php';
require_once __DIR__ . '/includes/dbh.php';
require_once __DIR__ . '/includes/products/product_model.php';
require_once __DIR__ . '/includes/products/product_contr.php';
require_once __DIR__ . '/includes/pages/home_contr.php';
require_once __DIR__ . '/includes/UI/nav_helpers.php';
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NotScam.com – nejlepší online platforma pro osobní růst a finanční svobodu. To určitě není podvod.">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/print.css" media="print">
    <link rel="icon" href="assets/img/iconlogo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    <title>NotScam.com</title>
</head>
<body>
    <header>
        <div class="header__container">
            <a href="index.php" class="logo">
                <img class="logo__icon" src="assets/img/iconlogo.png" alt="icon logo">
                <h1 class="logo__text">NotScam<span class="logo__smallerpart">.com</span></h1>
            </a>
            <nav>
                <ul class="header__ul">
                    <?php
                        showMainPageUlLinks('header');
                    ?>
                </ul>
            </nav>
            <div id="burger" class="burger"><span></span></div>
            <div id="burger-menu" class="burger__menu">
                <nav>
                    <ul class="burger__ul">
                        <li><button id="burger-exitButton" class="burger__exitButton">×</button></li>
                        <?php
                            showMainPageUlLinks('burger');
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="banner">
            <h2 class="banner__text">Změň svůj<br>život už dnes!</h2>
            <h2 class="banner__secondtext">100%</h2>
        </section>
        <div class="products__container">
            <section>
                <h3 class="products__title">Zboží</h3>
                <div class="pagination-sort">
                    <a href="?sort=old" 
                    class="pagination-sort__btn <?= $sort === 'old' ? 'active' : '' ?>">
                        Nejstarší
                    </a>
                    <a href="?sort=new" 
                    class="pagination-sort__btn <?= $sort === 'new' ? 'active' : '' ?>">
                        Nejnovější
                    </a>
                </div>

                <div class="products">
                    <?php foreach ($products as $product): ?>
                        <article class="product-card">
                            <div class="product-img">
                                <img
                                    src="<?= $product['image_path']
                                        ? 'uploads/products/' . htmlspecialchars($product['image_path'])
                                        : 'assets/img/no-image.png' ?>"
                                    alt="<?= htmlspecialchars($product['name']) ?>"
                                >
                            </div>
                            <h3>
                                <a class="product-text" href="product.php?id=<?= $product['id'] ?>">
                                    <?= htmlspecialchars($product['name']) ?>
                                </a>
                            </h3>
                            <?php
                                $oldPrice = $product['price'] * 2;
                            ?>
                            <div class="product-prevpriceblock">
                                <h3 class="product-prevprice">
                                    <?= number_format($oldPrice, 0, ',', ' ') ?> Kč
                                </h3>
                                <h3>-50%</h3>
                            </div>
                            <h3 class="product-price">
                                <?= number_format($product['price'], 0, ',', ' ') ?> Kč
                            </h3>
                            <a href="product.php?id=<?= $product['id'] ?>" class="product-button">
                                Zobrazit
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php require __DIR__ . '/includes/UI/pagination.php'; ?>
        </div>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/UI/burger.js"></script>
</body>
</html>