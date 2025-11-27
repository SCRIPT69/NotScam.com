<?php

/**
 * Vygeneruje HTML blok s chybou pro dané pole ve formuláři registrace.
 *
 * @param string $key Klíč pole (name, email, password...).
 * @return void
 */
function generateErrorBlock(string $key) {
    if (isset($_SESSION['register-errors']) && array_key_exists($key, $_SESSION['register-errors'])) {
        echo '<p class="validation-error">'.htmlspecialchars($_SESSION["register-errors"][$key], ENT_QUOTES, "UTF-8").'</p>';
    }
    else {
        echo '<p class="validation-error"></p>';
    }
}

/**
 * Vrátí předvyplněnou hodnotu pole registrace ze session.
 *
 * @param string $key Klíč pole.
 * @return string Hodnota připravená k výpisu v HTML.
 */
function getRegisterData(string $key) {
    if (isset($_SESSION['register-data']) && array_key_exists($key, $_SESSION['register-data'])) {
        return htmlspecialchars($_SESSION['register-data'][$key], ENT_QUOTES, "UTF-8");
    }
    else {
        return "";
    }
}