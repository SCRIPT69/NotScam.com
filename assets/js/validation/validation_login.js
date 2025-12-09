import { setError, clearError } from "./errorRenderer.js";
import { checkEmailFormatForErrors, runBasicValueChecks } from "./validators.js";

function validateLoginForm(event) {
    let hasErrors = false;

    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    const inputs = [emailInput, passwordInput];
    inputs.forEach(input => clearError(input));

    let errorMsg;
    if ((errorMsg = checkEmailFormatForErrors(emailInput.value))) {
        setError(emailInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = runBasicValueChecks(passwordInput.value, 100))) {
        setError(passwordInput, errorMsg);
        hasErrors = true;
    }

    if (hasErrors) {
        event.preventDefault();
    }
}
document.getElementById("loginform").addEventListener("submit", validateLoginForm);