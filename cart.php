<?php
require_once __DIR__ . '/includes/session_manager.php';
require_once __DIR__ . '/includes/dbh.php';
require_once __DIR__ . '/includes/products/product_model.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "user") {
    header("Location: index.php");
    exit;
}

$cartIds = array_keys($_SESSION['cart'] ?? []);
$products = [];

if (!empty($cartIds)) {
    $products = getProductsByIds($pdo, $cartIds);

    $totalPrice = 0;
    foreach ($products as $product) {
        $totalPrice += $product['price'];
    }
}
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
                    <li><a class="header__ul__link" href="index.php">Hlavní stránka</a></li>
                    <li><a class="header__ul__link" href="profile.php">Můj profil</a></li>
                    <li><a class="header__ul__link_chosen" href="cart.php">🛒Košík</a></li>
                    <li><a class="header__ul__link" href="logout.php">Odhlásit se</a></li>
                </ul>
            </nav>
            <div id="burger" class="burger"><span></span></div>
            <div id="burger-menu" class="burger__menu">
                <nav>
                    <ul class="burger__ul">
                        <li><button id="burger-exitButton" class="burger__exitButton">×</button></li>
                        <li><a class="burger__ul__link" href="index.php">Hlavní stránka</a></li>
                        <li><a class="burger__ul__link" href="profile.php">Můj profil</a></li>
                        <li><a class="burger__ul__link_chosen" href="cart.php">🛒Košík</a></li>
                        <li><a class="burger__ul__link" href="logout.php">Odhlásit se</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main class="cart">
        <div class="cart__container">
            <h1 class="cart__title">🛒 Košík</h1>
            <?php if (empty($products)): ?>
                <p class="cart__empty">Košík je prázdný</p>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <article class="cart-item">
                        <img
                            src="<?= $product['image_path']
                                ? 'uploads/products/' . htmlspecialchars($product['image_path'])
                                : 'assets/img/no-image.png' ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>"
                        >
                        <div class="cart-item__info">
                            <h3 class="cart-item__name"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="cart-item__price"><?= number_format($product['price'], 0, ',', ' ') ?> Kč</p>
    
                            <form method="post" action="includes/cart/remove_from_cart.php">
                                <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                <button class="cart-item__removebtn" type="submit">Odebrat 🗑️</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
                <button class="cart__paybtn" type="button">
                    Zaplatit celkem <?= number_format($totalPrice, 0, ',', ' ') ?> Kč
                </button>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/UI/burger.js"></script>
</body>
</html>