<?php
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/dbh.php';
require_once __DIR__ . '/../includes/products/product_model.php';
require_once __DIR__ . '/../includes/UI/form_helpers.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: admin_panel.php");
    exit;
}

$productId = (int)$_GET['id'];
$product = getProductById($pdo, $productId);

if (!$product) {
    header("Location: admin_panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NotScam.com – nejlepší online platforma pro osobní růst a finanční svobodu. To určitě není podvod.">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/print.css" media="print">
    <link rel="icon" href="../assets/img/iconlogo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    <title>NotScam.com</title>
</head>
<body>
    <header>
        <div class="header__container">
            <a href="index.php" class="logo">
                <img class="logo__icon" src="../assets/img/iconlogo.png" alt="icon logo">
                <h1 class="logo__text">NotScam<span class="logo__smallerpart">.com</span></h1>
            </a>
            <nav>
                <ul class="header__ul">
                    <li><a class="header__ul__link" href="../index.php">Hlavní stránka</a></li>
                    <li><a class="header__ul__link" href="../profile.php">Můj profil</a></li>
                    <li><a class="header__ul__link_chosen" href="admin_panel.php">Admin panel</a></li>
                    <li><a class="header__ul__link" href="../logout.php">Odhlásit se</a></li>
                </ul>
            </nav>
            <div id="burger" class="burger"><span></span></div>
            <div id="burger-menu" class="burger__menu">
                <nav>
                    <ul class="burger__ul">
                        <li><button id="burger-exitButton" class="burger__exitButton">×</button></li>
                        <li><a class="burger__ul__link" href="../index.php">Hlavní stránka</a></li>
                        <li><a class="burger__ul__link" href="../profile.php">Můj profil</a></li>
                        <li><a class="burger__ul__link_chosen" href="admin_panel.php">Admin panel</a></li>
                        <li><a class="burger__ul__link" href="../logout.php">Odhlásit se</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="admin_panel">
            <a href="admin_panel.php" class="back-btn">
                ← Zpět
            </a>
            <form id="productform" class="admin_panel__container admin_panel__editcontainer"
                action="product/product_update.php"
                method="POST"
                enctype="multipart/form-data">

                <h3>Upravit produkt</h3>

                <input type="hidden" name="id" value="<?= $product['id'] ?>">

                <div class="admin_panel__inputcontainer">
                    <label class="admin_panel__label">Název produktu*:</label>
                    <div class="admin_panel__containerForError">
                        <input
                            name="name"
                            class="admin_panel__input <?= inputErrorClass('product', 'name') ?>"
                            value="<?= getFormData('product', 'name') ? getFormData('product', 'name') : htmlspecialchars($product['name']) ?>"
                            required
                        >
                        <?php
                            generateErrorBlock('product', 'name');
                        ?>
                    </div>
                </div>
                <div class="admin_panel__inputcontainer">
                    <label class="admin_panel__label">Popis*:</label>
                    <div class="admin_panel__containerForError">
                        <textarea
                            name="description"
                            class="admin_panel__textarea <?= inputErrorClass('product', 'description') ?>"
                            required
                        ><?= getFormData('product', 'description') ? getFormData('product', 'description') : htmlspecialchars($product['description']) ?>
                        </textarea>
                        <?php
                            generateErrorBlock('product', 'description');
                        ?>
                    </div>
                </div>
                <div class="admin_panel__inputcontainer">
                    <label class="admin_panel__label">Cena (CZK)*:</label>
                    <div class="admin_panel__containerForError">
                        <input
                            name="price"
                            type="number"
                            step="0.01"
                            class="admin_panel__input admin_panel__input-price <?= inputErrorClass('product', 'price') ?>"
                            value="<?= getFormData('product', 'price') ? getFormData('product', 'price') : htmlspecialchars($product['price']) ?>"
                            required
                        >
                        <?php
                            generateErrorBlock('product', 'price');
                        ?>
                    </div>
                </div>

                <?php if ($product['image_path']): ?>
                    <div class="admin_panel__inputcontainer">
                        <label class="admin_panel__label">Aktuální obrázek:</label>
                        <img
                            src="<?= $product['image_path']
                                ? '../uploads/products/' . htmlspecialchars($product['image_path'])
                                : '../assets/img/no-image.png'
                            ?>"
                            class="admin-product__actualimg"
                            alt="product image"
                        >
                    </div>
                <?php endif; ?>
                <div class="admin_panel__inputcontainer">
                    <label class="admin_panel__label">Nový obrázek:</label>
                    <div class="admin_panel__containerForError">
                        <input type="file" name="image" class="admin_panel__input admin_panel__input-file <?= inputErrorClass('product', 'image') ?>">
                        <?php
                            generateErrorBlock('product', 'image');
                        ?>
                    </div>
                </div>

                <button class="admin_panel__button" type="submit">
                    Uložit změny
                </button>

            </form>
        </section>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="../assets/js/UI/burger.js"></script>
    <script type="module" src="../assets/js/validation/validation_product.js"></script>
</body>
</html>

<?php
    clearValidationSessions("product");
?>