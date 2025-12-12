<?php
declare(strict_types=1);

function showUlLinks(string $ulName, string $role): void {
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