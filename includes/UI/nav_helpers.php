<?php
declare(strict_types=1);

/**
 * Vykreslí navigační položky hlavního menu podle stavu přihlášení uživatele.
 *
 * - Pro nepřihlášeného uživatele zobrazí odkazy na přihlášení a registraci.
 * - Pro přihlášeného uživatele zobrazí odkaz na profil a odhlášení.
 * - Podle role uživatele přidá odkaz na košík (user) nebo admin panel (admin).
 * - Odkaz na hlavní stránku je vždy označen jako aktivní.
 *
 * @param string $ulName Prefix CSS tříd menu (např. "header", "burger").
 *
 * @return void
 */
function showMainPageUlLinks(string $ulName): void {
    echo '<li><a class="'.$ulName.'__ul__link_chosen" href="index.php">Hlavní stránka</a></li>';
    if (!isset($_SESSION["user_id"])) {
        echo '<li><a class="'.$ulName.'__ul__link" href="login.php">Přihlášení</a></li>';
        echo '<li><a class="'.$ulName.'__ul__link" href="register.php">Registrace</a></li>';
    }
    else {
        echo '<li><a class="'.$ulName.'__ul__link" href="profile.php">Můj profil</a></li>';
        if ($_SESSION["user_role"] == "user") {
            echo '<li><a class="'.$ulName.'__ul__link" href="cart.php">🛒Košík</a></li>';
        }
        else if ($_SESSION["user_role"] == "admin") {
            echo '<li><a class="'.$ulName.'__ul__link" href="admin/admin_panel.php">Admin panel</a></li>';
        }

        echo '<li><a class="'.$ulName.'__ul__link" href="logout.php">Odhlásit se</a></li>';
    }
}