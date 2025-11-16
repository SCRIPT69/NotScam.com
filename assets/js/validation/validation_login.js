import { setError, clearError } from "./errorRenderer.js";

function validateLoginForm(event) {
    event.preventDefault();
    let hasErrors = false;

    const inputs = [];
    const emailInput = document.getElementById("email");
    inputs.push(emailInput);
    const passwordInput = document.getElementById("password");
    inputs.push(passwordInput);

    //checking if any of the inputs are empty
    for (let input of inputs) {
        let value = input.value.trim();
        if (value == "") {
            hasErrors = true;
            setError(input, "Vyplňte pole!");
        }
        else {
            clearError(input);
        }
    }

    if (hasErrors) {
        event.preventDefault();
    }
}
document.getElementById("loginform").addEventListener("submit", validateLoginForm);