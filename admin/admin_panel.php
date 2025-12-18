<?php
require_once __DIR__ . '/../includes/dbh.php';
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/UI/form_helpers.php';
require_once __DIR__ . '/../includes/products/product_model.php';
if ($_SESSION["user_role"] != "admin") {
    header("Location: ../index.php");
    exit;
}
$products = getAllProducts($pdo);
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
            <form id="productform" class="admin_panel__container" action="product/product_create.php" method="POST" enctype="multipart/form-data">

                <h3>Přidat nový produkt</h3>

                <div class="admin_panel__inputcontainer">
                    <label for="name" class="admin_panel__label">Název produktu*:</label>
                    <div class="admin_panel__containerForError">
                        <input id="name" name="name" class="admin_panel__input <?= inputErrorClass('product', 'name') ?>" value="<?= getFormData('product', 'name');?>" required>
                        <?php
                            generateErrorBlock('product', 'name');
                        ?>
                    </div>
                </div>

                <div class="admin_panel__inputcontainer">
                    <label for="description" class="admin_panel__label">Popis*:</label>
                    <div class="admin_panel__containerForError">
                        <textarea id="description" name="description" class="admin_panel__textarea <?= inputErrorClass('product', 'description') ?>" required><?= getFormData('product', 'description');?></textarea>
                        <?php
                            generateErrorBlock('product', 'description');
                        ?>
                    </div>
                </div>

                <div class="admin_panel__inputcontainer">
                    <label for="price" class="admin_panel__label">Cena (CZK)*:</label>
                    <div class="admin_panel__containerForError">
                        <input id="price" name="price" type="number" step="0.01" class="admin_panel__input admin_panel__input-price <?= inputErrorClass('product', 'price') ?>" value="<?= getFormData('product', 'price');?>" required>
                        <?php
                            generateErrorBlock('product', 'price');
                        ?>
                    </div>
                </div>

                <div class="admin_panel__inputcontainer">
                    <label for="image" class="admin_panel__label">Obrázek produktu:</label>
                    <div class="admin_panel__containerForError">
                        <input id="image" name="image" type="file" class="admin_panel__input admin_panel__input-file <?= inputErrorClass('product', 'image') ?>">
                        <?php
                            generateErrorBlock('product', 'image');
                        ?>
                    </div>
                </div>
                <?php
                    if (isset($_GET["success"])) {
                        echo '<div class="admin_panel__success">Produkt byl úspěšně přidán!</div>';
                    }
                ?>

                <button id="savebtn" class="admin_panel__button" type="submit">
                    Přidat produkt
                </button>

            </form>
        </section>
        <section class="admin_panel">
            <div class="admin_panel__container">
                <h3>Seznam produktů</h3>

                <?php foreach ($products as $product): ?>
                    <div class="admin-product__container">
                        <img
                            src="<?= $product['image_path']
                                ? '../uploads/products/' . htmlspecialchars($product['image_path'])
                                : '../assets/img/no-image.png'
                            ?>"
                            class="admin-product__img"
                            alt="product image"
                        >
                        <div class="admin-product__infocontainer">
                            <div>
                                <strong class="admin-product__title"><?= htmlspecialchars($product['name']) ?></strong>
                                — <?= number_format($product['price'], 0, ',', ' ') ?> Kč
                            </div>
    
                            <div class="admin_panel__actions">
                                <a class="admin-product__btn" href="editProduct.php?id=<?= $product['id'] ?>">✏️ Upravit</a>
    
                                <form class="deleteproductform" method="post" action="product/product_delete.php">
                                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                    <button class="admin-product__btn" type="submit">🗑️ Smazat</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="admin_panel">
            <div class="admin_panel__container">
                <h3>Správa uživatelů</h3>
                <form method="post" action="role/user_role_update.php">
                    <div class="admin_panel__inputcontainer">
                        <label class="admin_panel__label">E-mail uživatele:</label>
                        <div class="admin_panel__containerForError">
                            <input type="email" name="email" class="admin_panel__input" required>
                            <?php
                                generateErrorBlock('role', 'email');
                            ?>
                        </div>
                    </div>

                    <div class="admin_panel__inputcontainer">
                        <label class="admin_panel__label">Role:</label>
                        <div class="admin_panel__containerForError">
                            <select name="newRole" class="admin_panel__input">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <?php
                                generateErrorBlock('role', 'newRole');
                            ?>
                        </div>
                    </div>

                    <button class="admin_panel__button" type="submit">
                        Změnit roli
                    </button>
                    <?php
                        if (isset($_GET["role_success"])) {
                            echo '<div class="admin_panel__success">Role uživatele byla úspěšně změněna!</div>';
                        }
                    ?>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="../assets/js/UI/burger.js"></script>
    <script src="../assets/js/UI/confirmDeletingProduct.js"></script>
    <script type="module" src="../assets/js/validation/validation_product.js"></script>
</body>
</html>

<?php
    clearValidationSessions("product");
    clearValidationSessions("role");
?>