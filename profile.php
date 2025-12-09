<?php
require_once 'includes/session_manager.php';
require_once 'includes/dbh.php';
require_once 'includes/form_helpers.php';
require_once 'includes/profile/profile_model.php';
require_once 'includes/profile/profile_contr.php';
require_once 'includes/profile/profile_view.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$name = getUserField("name", $pdo);
$email = getUserField("email", $pdo);
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
                        showUlLinks('header', $_SESSION["user_role"]);
                    ?>
                </ul>
            </nav>
            <div id="burger" class="burger"><span></span></div>
            <div id="burger-menu" class="burger__menu">
                <nav>
                    <ul class="burger__ul">
                        <li><button id="burger-exitButton" class="burger__exitButton">×</button></li>
                        <?php
                            showUlLinks('burger', $_SESSION["user_role"]);
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <section class="profile">
            <form class="profile__container" action="includes/profile/profile_savingChangedData.php" method="POST" id="profileform">
                <?php
                    echo '<h3>Dobrý den, '.htmlspecialchars($name, ENT_QUOTES, "UTF-8").'!</h3>';
                ?>
                <div class="profile__inputcontainer">
                    <label class="profile__label" for="name">Jméno: </label>
                    <div class="profile__containerForError">
                        <?php
                            $namevalue = getFormData("profile", "name") !== "" ? getFormData("profile", "name") : htmlspecialchars($name, ENT_QUOTES, "UTF-8");
                            echo '<input id="name" name="name" class="profile__input" value="'.$namevalue.'">';
                            generateErrorBlock('profile', 'name');
                        ?>
                    </div>
                </div>
                <div class="profile__inputcontainer">
                    <label class="profile__label" for="email">E-mail: </label>
                    <div class="profile__containerForError">
                        <?php
                            $emailvalue = getFormData("profile", "email") !== "" ? getFormData("profile", "email") : htmlspecialchars($email, ENT_QUOTES, "UTF-8");
                            echo '<input id="email" name="email" type="email" class="profile__input" value="'.$emailvalue.'">';
                            generateErrorBlock('profile', 'email');
                        ?>
                    </div>
                </div>
                <div class="profile__pwdinputscontainer">
                    <div class="profile__inputcontainer">
                        <label for="currentpassword" class="profile__label-smaller">Heslo: </label>
                        <div class="profile__containerForError">
                            <input type="password" id="currentpassword" name="currentpassword" class="profile__input profile__input-darkerborder">
                            <button type="button" id="showPwdButton" class="auth__password-showPwdButton">👁️</button>
                            <?php
                                generateErrorBlock('profile', 'currentpassword');
                            ?>
                        </div>
                    </div>
                    <div class="profile__inputcontainer">
                        <label for="newpassword" class="profile__label-smaller">Nové heslo: </label>
                        <div class="profile__containerForError">
                            <input type="password" id="newpassword" name="newpassword" class="profile__input profile__input-darkerborder">
                            <?php
                                generateErrorBlock('profile', 'newpassword');
                            ?>
                        </div>
                    </div>
                    <div class="profile__inputcontainer">
                        <label for="newpasswordconfirm" class="profile__label-smaller">Potvrďte heslo: </label>
                        <div class="profile__containerForError">
                            <input type="password" id="newpasswordconfirm" name="newpasswordconfirm" class="profile__input profile__input-darkerborder">
                            <?php
                                generateErrorBlock('profile', 'newpasswordconfirm');
                            ?>
                        </div>
                    </div>
                    <button id="deleteAccountBtn" class="profile__deletebutton">Smazat účet</button>
                </div>
                <?php 
                    if (!isset($_SESSION["profile-data"])) {
                        echo '<button id="savebtn" class="profile__button profile__button-notactive" type="submit" disabled>Uložit změny</button>';
                    }
                    else {
                        echo '<button id="savebtn" class="profile__button profile__button-active" type="submit">Uložit změny</button>';
                    }
                ?>
            </form>
        </section>
    </main>
    <footer>
        <p>© 2025 NotScam.com</p>
    </footer>

    <script src="assets/js/UI/burger.js"></script>
    <script type="module" src="assets/js/validation/validation_profile.js"></script>
    <script src="assets/js/UI/deleteAccountBtn.js"></script>
    <script src="assets/js/UI/showPwdButton.js"></script>
    <?php
        if (!isset($_SESSION["profile-data"])) {
            echo '<script src="assets/js/UI/profile_setActiveSaveBtn.js"></script>';
        }
    ?>
</body>
</html>

<?php
    clearValidationSessions("profile");
?>