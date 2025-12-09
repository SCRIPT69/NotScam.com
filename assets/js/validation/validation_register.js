import { setError, clearError } from "./errorRenderer.js";
import { 
    checkNameForErrors,
    checkEmailFormatForErrors,
    checkIfEmailExistsError,
    checkPasswordForErrors,
    checkPasswordConfirmNotEqualsError,
    checkCardNumberForErrors,
    checkCardExpirationDateForErrors,
    checkCardCVVForErrors,
    checkCheckboxAgreeError
} from "./validators.js";

async function validateRegisterForm(event) {
    event.preventDefault();

    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const passwordConfirmInput = document.getElementById("passwordconfirm");
    const cardNumberInput = document.getElementById("cardnumber");
    const cardExpirationInput = document.getElementById("cardexpiration");
    const cardCVVInput = document.getElementById("cardcvv");
    const checkboxAgree = document.getElementById("checkboxagree");

    let hasErrors = false;
    const inputs = [nameInput, emailInput, passwordInput, passwordConfirmInput, cardNumberInput, cardExpirationInput, cardCVVInput, checkboxAgree];
    inputs.forEach(input => clearError(input));

    let errorMsg;
    if ((errorMsg = checkNameForErrors(nameInput.value))) {
        setError(nameInput, errorMsg);
        hasErrors = true;
    }
    let emailErrorMsg;
    if ((emailErrorMsg = await checkEmailRegisterForErrors(emailInput.value))) {
        setError(emailInput, emailErrorMsg);
        hasErrors = true;
    }
    if ((errorMsg = checkPasswordForErrors(passwordInput.value))) {
        setError(passwordInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = checkPasswordConfirmNotEqualsError(passwordInput.value, passwordConfirmInput.value))) {
        setError(passwordConfirmInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = checkCardNumberForErrors(cardNumberInput.value))) {
        setError(cardNumberInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = checkCardExpirationDateForErrors(cardExpirationInput.value))) {
        setError(cardExpirationInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = checkCardCVVForErrors(cardCVVInput.value))) {
        setError(cardCVVInput, errorMsg);
        hasErrors = true;
    }
    if ((errorMsg = checkCheckboxAgreeError(checkboxAgree.checked))) {
        setError(checkboxAgree, errorMsg);
        hasErrors = true;
    }

    if (!hasErrors) {
        event.target.submit();
    }
}
document.getElementById("registerform").addEventListener("submit", validateRegisterForm);


async function checkEmailRegisterForErrors(email) {
    let error = checkEmailFormatForErrors(email);
    if (error) return error;

    error = await checkIfEmailExistsError(email)
    if (error) return error;

    return false;
}