import { setError, clearError } from "./errorRenderer.js";
import { checkPriceForErrors, runBasicValueChecks } from "./validators.js";

function validateProductForm(event) {
    let hasErrors = false;

    const nameInput = document.getElementById("name");
    const descriptionInput = document.getElementById("description");
    const priceInput = document.getElementById("price");

    const inputs = [nameInput, descriptionInput, priceInput];
    inputs.forEach(input => clearError(input));

    let errorMsg;
    if ((errorMsg = runBasicValueChecks(nameInput.value, 255))) {
        setError(nameInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = runBasicValueChecks(descriptionInput.value, 1000))) {
        setError(descriptionInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = checkPriceForErrors(priceInput.value))) {
        setError(priceInput, errorMsg);
        hasErrors = true;
    }

    if (hasErrors) {
        event.preventDefault();
    }
}
document.getElementById("productform").addEventListener("submit", validateProductForm);