<?php

/**
 * Vygeneruje HTML blok s chybovou zprávou pro konkrétní pole formuláře.
 *
 * @param string $formName Název formuláře (např. 'register', 'login').
 * @param string $key Název pole, pro které se má chyba zobrazit.
 * 
 * @return void
 */
function generateErrorBlock(string $formName, string $key) {
    if (isset($_SESSION[$formName."-errors"]) && array_key_exists($key, $_SESSION[$formName."-errors"])) {
        echo '<p class="validation-error">'.htmlspecialchars($_SESSION[$formName."-errors"][$key], ENT_QUOTES, "UTF-8").'</p>';
    }
    else {
        echo '<p class="validation-error"></p>';
    }
}

/**
 * Vrátí hodnotu dříve vyplněného pole formuláře uloženou v session.
 * Používá se pro opětovné předvyplnění formuláře při chybové validaci.
 *
 * @param string $formName Název formuláře (např. 'register', 'login').
 * @param string $key Název pole formuláře.
 *
 * @return string Hodnota pole nebo prázdný string.
 */
function getFormData(string $formName, string $key) {
    if (isset($_SESSION[$formName."-data"]) && array_key_exists($key, $_SESSION[$formName."-data"])) {
        return htmlspecialchars($_SESSION[$formName."-data"][$key], ENT_QUOTES, "UTF-8");
    }
    else {
        return "";
    }
}

/**
 * Vymaže validační chyby a dříve vyplněná data formuláře ze session.
 *
 * Používá se po zobrazení formuláře, aby se odstranily staré hodnoty
 * a chyby z předchozího odeslání.
 *
 * @param string $formName Název formuláře (např. 'register', 'profile').
 *
 * @return void
 */
function clearValidationSessions(string $formName) {
    unset($_SESSION["$formName-errors"]);
    unset($_SESSION["$formName-data"]);
}