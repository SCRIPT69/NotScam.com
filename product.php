<?php
require_once __DIR__ . '/includes/session_manager.php';
require_once __DIR__ . '/includes/dbh.php';
require_once __DIR__ . '/includes/products/product_model.php';
require_once __DIR__ . '/includes/products/product_contr.php';
require_once __DIR__ . '/includes/products/product_view.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: ../index.php");
    exit;
}

$id = (int)$_GET['id'];

$product = loadProduct($pdo, $id);
$oldPrice = $product['price'] * 2;
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
                        showProductUlLinks('header');
                    ?>
                </ul>
            </nav>
            <div id="burger" class="burger"><span></span></div>
            <div id="burger-menu" class="burger__menu">
                <nav>
                    <ul class="burger__ul">
                        <li><button id="burger-exitButton" class="burger__exitButton">×</button></li>
                        <?php
                            showProductUlLinks('burger');
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main class="product-detail">
        <a href="javascript:history.back()" class="back-btn">
            ← Zpět
        </a>
        <div class="product-detail__container">
            <div class="product-detail__img">
                <img
                    src="<?= $product['image_path']
                        ? 'uploads/products/' . htmlspecialchars($product['image_path'])
                        : 'assets/img/no-image.png' ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                >
            </div>
    
            <div class="product-detail__info">
                <h1 class="product-detail__title"><?= htmlspecialchars($product['name']) ?></h1>
    
                <div class="product-detail__prices">
                    <span class="product-detail__old">
                        <?= number_format($oldPrice, 0, ',', ' ') ?> Kč
                    </span>
                    <span class="product-detail__price">
                        <?= number_format($product['price'], 0, ',', ' ') ?> Kč
                    </span>
                </div>
    
                <p class="product-detail__description">
                    <?= nl2br(htmlspecialchars($product['description'])) ?>
                </p>

                <?php
                    if (!isset($_SESSION["user_id"]) ||
                        !isset($_SESSION["user_role"]) ||
                        $_SESSION["user_role"] == "admin" ||
                        (isset($_SESSION["cart"]) && isset($_SESSION['cart'][$product["id"]])))
                    {
                        echo '<button id="addToCartBtn" type="button" class="product-detail__button" disabled>Přidat do 🛒</button>';
                    }
                    else {
                        echo '<button id="addToCartBtn" type="button" class="product-detail__button product-detail__button-active" data-product-id="'.$product["id"].'">Přidat do 🛒</button>';
                    }
                ?>
            </div>
        </div>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/UI/burger.js"></script>
    <script src="assets/js/UI/addToCart.js"></script>
</body>
</html>