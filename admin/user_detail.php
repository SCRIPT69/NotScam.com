<?php
require_once __DIR__ . '/../includes/session_manager.php';
require_once __DIR__ . '/../includes/dbh.php';
require_once __DIR__ . '/../includes/roles/role_model.php';
require_once __DIR__ . '/../includes/roles/role_contr.php';
require_once __DIR__ . '/../includes/UI/form_helpers.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: admin_panel.php");
    exit;
}

$userId = (int) $_GET['id'];
$user = getUserById($pdo, $userId);

if (!$user) {
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
            <a href="../index.php" class="logo">
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
            <a href="admin_panel.php" class="back-btn">← Zpět</a>
            <form class="admin_panel__container"
                method="post"
                action="role/user_role_update.php">
                <h2>Detail uživatele</h2>

                <input type="hidden" name="userId" value="<?= $user['id'] ?>">

                <div class="admin_panel__inputcontainer">
                    <label for="email" class="admin_panel__label">E-mail:</label>
                    <input id="email" class="admin_panel__input" name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                </div>

                <div class="admin_panel__inputcontainer">
                    <label for="name" class="admin_panel__label">Jméno:</label>
                    <input id="name" class="admin_panel__input" name="name" type="text" value="<?= htmlspecialchars($user['name']) ?>" disabled>
                </div>

                <div class="admin_panel__inputcontainer">
                    <label for="newRole" class="admin_panel__label">Role:</label>
                    <div>
                        <select id="newRole" name="newRole" class="admin_panel__input admin_panel__input-price">
                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>
                                User
                            </option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
                                Admin
                            </option>
                        </select>
                        <?php generateErrorBlock('role', 'newRole'); ?>
                        
                        <?php if (isset($_GET['role_success'])): ?>
                            <div class="admin_panel__success">
                                Role uživatele byla úspěšně změněna.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ((int)$user['id'] === (int)$_SESSION['user_id']): ?>
                    <p class="admin_panel__warning">
                        Nemůžete změnit vlastní roli.
                    </p>
                <?php else: ?>
                    <button class="admin_panel__button" type="submit">
                        Uložit změny
                    </button>
                <?php endif; ?>
            </form>
        </section>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>
</body>
</html>

<?php
    clearValidationSessions('role');
?>