<?php
declare(strict_types=1);

/**
 * Vykreslí navigační položky pro profile.php
 *
 * - Podle role uživatele přidá odkaz na košík (user) nebo admin panel (admin).
 *
 * @param string $ulName Prefix CSS tříd menu (např. "header", "burger").
 * @param string $role User role.
 *
 * @return void
 */
function showProfileUlLinks(string $ulName, string $role): void {
    echo '<li><a class="'.$ulName.'__ul__link" href="index.php">Hlavní stránka</a></li>';
    echo '<li><a class="'.$ulName.'__ul__link_chosen" href="profile.php">Můj profil</a></li>';
    if ($role == "user") {
        echo '<li><a class="'.$ulName.'__ul__link" href="cart.php">🛒Košík</a></li>';
    }
    else if ($role == "admin") {
        echo '<li><a class="'.$ulName.'__ul__link" href="admin/admin_panel.php">Admin panel</a></li>';
    }
    echo '<li><a class="'.$ulName.'__ul__link" href="logout.php">Odhlásit se</a></li>';
}