import { setError, clearError } from "./errorRenderer.js";

function validateRegisterForm(event) {
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
    for (let input of inputs) {
        clearError(input);
    }

    if (checkNameForErrors(nameInput)) {
        hasErrors = true;
    }
    if (checkEmailForErrors(emailInput)) {
        hasErrors = true;
    }
    if (checkPasswordForErrors(passwordInput)) {
        hasErrors = true;
    }
    if (checkPasswordConfirmNotEqualsError(passwordInput, passwordConfirmInput)) {
        hasErrors = true;
    }
    if (checkCardNumberForErrors(cardNumberInput)) {
        hasErrors = true;
    }
    if (checkCardExpirationDateForErrors(cardExpirationInput)) {
        hasErrors = true;
    }
    if (checkCardCVVForErrors(cardCVVInput)) {
        hasErrors = true;
    }
    if (checkCheckboxAgreeForErrors(checkboxAgree)) {
        hasErrors = true;
    }

    if (hasErrors) {
        event.preventDefault();
    }
}
document.getElementById("registerform").addEventListener("submit", validateRegisterForm);


function checkIfEmpty(input) {
    // kontrola, zda je input prázdný
    if (input.value == "") {
        setError(input, "Vyplňte pole!");
        return true;
    }
    return false;
}

function checkLengthLimit(input, maxLength) {
    // kontrola, zda je input value příliš dlouhé
    if (input.value.length > maxLength) {
        setError(input, "Překročena maximální délka!");
        return true;
    }
    return false;
}

function checkNameForErrors(nameInput) {
    if (checkIfEmpty(nameInput)) return true;
    if (checkLengthLimit(nameInput, 40)) return true;

    // validuje jméno: povoluje pouze písmena (včetně české diakritiky)
    // a maximálně jednu mezeru mezi částmi jména. Žádné mezery na začátku nebo konci,
    // žádná čísla, speciální znaky ani dvojité mezery.
    if (!/^[A-Za-zÀ-ž]+(?: [A-Za-zÀ-ž]+)*$/.test(nameInput.value) || nameInput.value.trim().length < 2) {
        setError(nameInput, "Zadejte své pravé jméno!");
        return true;
    }
    return false;
}

function checkEmailForErrors(emailInput) {
    if (checkIfEmpty(emailInput)) return true;
    if (checkLengthLimit(emailInput, 100)) return true;

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
    if (checkLengthLimit(passwordInput, 100)) return true;

    // validuje heslo: musí mít min. 9 znaků,
    // obsahovat malé písmeno, velké písmeno,
    // číslici a speciální znak.
    let value = passwordInput.value;
    if (value.length < 9) {
        setError(passwordInput, "Heslo musí mít alespoň 9 znaků!");
        return true;
    }
    else if (!/[a-z]/.test(value) || !/[A-Z]/.test(value) || !/\d/.test(value) || !/[^A-Za-z0-9]/.test(value)) {
        setError(passwordInput, "Heslo musí obsahovat malé písmeno, velké písmeno, a číslici a specialní znak!");
        return true;
    }
    return false;
}

function checkPasswordConfirmNotEqualsError(passwordInput, passwordConfirmInput) {
    if (checkIfEmpty(passwordConfirmInput)) return true;
    if (checkLengthLimit(passwordConfirmInput, 100)) return true;
    
    // zkontrolujme, jestli se hesla shodují.
    if (passwordInput.value != passwordConfirmInput.value) {
        setError(passwordConfirmInput, "Hesla se neshodují!");
        return true;
    }
    return false;
}

function checkCardNumberForErrors(cardNumberInput) {
    if (checkIfEmpty(cardNumberInput)) return true;
    if (checkLengthLimit(cardNumberInput, 19)) return true;
    
    // validuje card number: musí mít 16 znaků,
    // musí obsahovat pouze čísla
    let value = cardNumberInput.value.replace(/\s+/g, ""); // odstraníme všechny mezery
    if (value.length != 16 || !/^\d+$/.test(value)) {
        setError(cardNumberInput, "Zadejte platné číslo karty!");
        return true;
    }
    return false;
}

function checkCardExpirationDateForErrors(cardExpirationInput) {
    if (checkIfEmpty(cardExpirationInput)) return true;
    if (checkLengthLimit(cardExpirationInput, 5)) return true;
    
    let dateStr = cardExpirationInput.value;

    // zkontrolujeme základní formát (1–2 číslice) / (2 číslice)
    const regex = /^(\d{1,2})\/(\d{2})$/;
    const match = dateStr.match(regex);
    if (!match) {
        setError(cardExpirationInput, "Zadejte správnou platnost karty!");
        return true;
    }

    const month = Number(match[1]);
    const year = Number(match[2]); // poslední dvě cifry

    // měsíc může být pouze 1–12
    if (month < 1 || month > 12) {
        setError(cardExpirationInput, "Zadejte správnou platnost karty!");
        return true;
    }

    // získáme aktuální rok (YY) a měsíc (1–12)
    const now = new Date();
    const currentYear = Number(now.getFullYear().toString().slice(-2)); // YY
    const currentMonth = now.getMonth() + 1; // 1–12

    // rok nesmí být menší než aktuální
    if (year < currentYear) {
        setError(cardExpirationInput, "Karta už není platná!");
        return true;
    }
    // pokud je rok stejný, měsíc nesmí být menší
    if (year === currentYear && month < currentMonth) {
        setError(cardExpirationInput, "Karta už není platná!");
        return true;
    }
    
    return false;
}

function checkCardCVVForErrors(cardCVVInput) {
    if (checkIfEmpty(cardCVVInput)) return true;
    
    // validuje card number: musí mít 3 znaky,
    // musí obsahovat pouze čísla
    let value = cardCVVInput.value;
    if (value.length != 3 || !/^\d+$/.test(value)) {
        setError(cardCVVInput, "Zadejte správné CVC/CVV!");
        return true;
    }
    return false;
}

function checkCheckboxAgreeForErrors(checkboxAgree) {
    if (!checkboxAgree.checked) {
        setError(checkboxAgree, "Musíte souhlasit s podmínkami!");
        return true;
    }
    return false;
}