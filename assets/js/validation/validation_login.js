import { setError, clearError } from "./errorRenderer.js";

function validateLoginForm(event) {
    let hasErrors = false;


    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    const inputs = [emailInput, passwordInput];
    for (let input of inputs) {
        clearError(input);
    }

    if (checkEmailForErrors(emailInput)) {
        hasErrors = true;
    }
    if (checkPasswordForErrors(passwordInput)) {
        hasErrors = true;
    }

    if (hasErrors) {
        event.preventDefault();
    }
}
document.getElementById("loginform").addEventListener("submit", validateLoginForm);


function checkIfEmpty(input) {
    // kontrola, zda je input prázdný
    if (input.value == "") {
        setError(input, "Vyplňte pole!");
        return true;
    }
    return false;
}

function checkEmailForErrors(emailInput) {
    if (checkIfEmpty(emailInput)) return true;

    // validuje e-mail: kontroluje, zda obsahuje část před '@', samotné '@',
    // část domény a koncovku (např. .cz, .com). Nepovoluje mezery ani více '@'.
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
        setError(emailInput, "Zadejte platný e-mail!");
        return true;
    }
    return false;
}

function checkPasswordForErrors(passwordInput) {
    if (checkIfEmpty(passwordInput)) return true;
    return false;
}