import { setError, clearError } from "./errorRenderer.js";
import {
    checkNameForErrors,
    checkEmailFormatForErrors,
    checkIfEmpty,
    checkPasswordForErrors,
    checkPasswordConfirmNotEqualsError
} from "./validators.js";

function validateProfileForm(event) {
    let hasErrors = false;

    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const currentPasswordInput = document.getElementById("currentpassword");
    const newPasswordInput = document.getElementById("newpassword");
    const newPasswordConfirmInput = document.getElementById("newpasswordconfirm");

    const inputs = [nameInput, emailInput, currentPasswordInput, newPasswordInput, newPasswordConfirmInput];
    inputs.forEach(input => clearError(input));

    let errorMsg;
    if ((errorMsg = checkNameForErrors(nameInput.value))) {
        setError(nameInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = checkEmailFormatForErrors(emailInput.value))) {
        setError(emailInput, errorMsg);
        hasErrors = true;
    }
    
    const anyPasswordFilled =
        currentPasswordInput.value !== "" ||
        newPasswordInput.value !== "" ||
        newPasswordConfirmInput.value !== "";
    if (anyPasswordFilled) {
        // Aktuální heslo musí být vyplněné
        if ((errorMsg = checkIfEmpty(currentPasswordInput.value))) {
            setError(currentPasswordInput, errorMsg);
            hasErrors = true;
        }
        // Validace nového hesla
        if ((errorMsg = checkPasswordForErrors(newPasswordInput.value))) {
            setError(newPasswordInput, errorMsg);
            hasErrors = true;
        }
        // Kontrola shody hesel
        if ((errorMsg = checkPasswordConfirmNotEqualsError(newPasswordInput.value, newPasswordConfirmInput.value))) {
            setError(newPasswordConfirmInput, errorMsg);
            hasErrors = true;
        }
    }

    if (hasErrors) {
        event.preventDefault();
    }
}
document.getElementById("profileform").addEventListener("submit", validateProfileForm);