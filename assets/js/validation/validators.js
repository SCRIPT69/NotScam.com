export function checkIfEmpty(value) {
    // kontrola, zda je input prázdný
    if (value == "") {
        return "Vyplňte pole!";
    }
    return false;
}

export function checkLengthLimit(value, maxLength) {
    // kontrola, zda je input value příliš dlouhé
    if (value.length > maxLength) {
        return "Překročena maximální délka!";
    }
    return false;
}

export function runBasicValueChecks(value, maxLength) {
    let error;
    if ((error = checkIfEmpty(value))) return error;
    if ((error = checkLengthLimit(value, maxLength))) return error;
    return false;
}

export function checkNameForErrors(name) {
    let error = runBasicValueChecks(name, 40);
    if (error) return error;

    // validuje jméno: povoluje pouze písmena (včetně české diakritiky)
    // a maximálně jednu mezeru mezi částmi jména. Žádné mezery na začátku nebo konci,
    // žádná čísla, speciální znaky ani dvojité mezery.
    if (!/^[A-Za-zÀ-ž]+(?: [A-Za-zÀ-ž]+)*$/.test(name) || name.trim().length < 2) {
        return "Zadejte své pravé jméno!";
    }
    return false;
}

export function checkEmailFormatForErrors(email) {
    let error = runBasicValueChecks(email, 100);
    if (error) return error;

    // validuje e-mail: kontroluje, zda obsahuje část před '@', samotné '@',
    // část domény a koncovku (např. .cz, .com). Nepovoluje mezery ani více '@'.
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        return "Zadejte platný e-mail!";
    }
    return false;
}

// AJAX dotaz na server – vrátí chybovou zprávu, pokud e-mail už existuje v databázi
export async function checkIfEmailExistsError(email) {
    let value = email.trim();
    try {
        const response = await fetch("includes/register/email_check.php?email=" + encodeURIComponent(value));
        const data = await response.json();
        if (data.exists) {
            return "E-mail se již používá!";
        }
        return false;
    } 
    catch (err) {
        console.error("AJAX chyba:", err);
        return false;
    }
}

export function checkPasswordForErrors(password) {
    let error = runBasicValueChecks(password, 100);
    if (error) return error;

    // validuje heslo: musí mít min. 9 znaků,
    // obsahovat malé písmeno, velké písmeno,
    // číslici a speciální znak.
    if (password.length < 9) {
        return "Heslo musí mít alespoň 9 znaků!";
    }
    else if (!/[a-z]/.test(password) || !/[A-Z]/.test(password) || !/\d/.test(password) || !/[^A-Za-z0-9]/.test(password)) {
        return "Heslo musí obsahovat malé písmeno, velké písmeno, a číslici a specialní znak!";
    }
    return false;
}

export function checkPasswordConfirmNotEqualsError(password, passwordConfirm) {
    let error = runBasicValueChecks(passwordConfirm, 100);
    if (error) return error;
    
    // zkontrolujme, jestli se hesla shodují.
    if (password != passwordConfirm) {
        return "Hesla se neshodují!";
    }
    return false;
}

export function checkCardNumberForErrors(cardNumber) {
    let error = runBasicValueChecks(cardNumber, 19);
    if (error) return error;
    
    // validuje card number: musí mít 16 znaků,
    // musí obsahovat pouze čísla
    let value = cardNumber.replace(/\s+/g, ""); // odstraníme všechny mezery
    if (value.length != 16 || !/^\d+$/.test(value)) {
        return "Zadejte platné číslo karty!";
    }
    return false;
}

export function checkCardExpirationDateForErrors(cardExpiration) {
    let error = runBasicValueChecks(cardExpiration, 5);
    if (error) return error;
    
    let dateStr = cardExpiration;

    // zkontrolujeme základní formát (1–2 číslice) / (2 číslice)
    const regex = /^(\d{1,2})\/(\d{2})$/;
    const match = dateStr.match(regex);
    if (!match) {
        return "Zadejte správnou platnost karty!";
    }

    const month = Number(match[1]);
    const year = Number(match[2]); // poslední dvě cifry

    // měsíc může být pouze 1–12
    if (month < 1 || month > 12) {
        return "Zadejte správnou platnost karty!";
    }

    // získáme aktuální rok (YY) a měsíc (1–12)
    const now = new Date();
    const currentYear = Number(now.getFullYear().toString().slice(-2)); // YY
    const currentMonth = now.getMonth() + 1; // 1–12

    // rok nesmí být menší než aktuální
    if (year < currentYear) {
        return "Karta už není platná!";
    }
    // pokud je rok stejný, měsíc nesmí být menší
    if (year === currentYear && month < currentMonth) {
        return "Karta už není platná!";
    }
    
    return false;
}

export function checkCardCVVForErrors(cardCVV) {
    let error = checkIfEmpty(cardCVV);
    if (error) return error;
    
    // validuje card number: musí mít 3 znaky,
    // musí obsahovat pouze čísla
    if (cardCVV.length != 3 || !/^\d+$/.test(cardCVV)) {
        return "Zadejte správné CVC/CVV!";
    }
    return false;
}

export function checkCheckboxAgreeError(isChecked) {
    if (!isChecked) {
        return "Musíte souhlasit s podmínkami!";
    }
    return false;
}

export function checkPriceForErrors(price) {
    // odstranění mezer
    let value = price.trim();

    // základní kontroly (prázdné, dlouhé)
    let error = runBasicValueChecks(value, 20);
    if (error) return error;

    // musí být číslo
    if (isNaN(value)) {
        return "Zadejte platnou cenu!";
    }

    // musí být > 0
    if (Number(value) <= 0) {
        return "Zadejte platnou cenu!";
    }

    // povolit max 2 desetinná místa
    if (!/^\d+(\.\d{1,2})?$/.test(value)) {
        return "Cena může mít maximálně 2 desetinná místa!";
    }

    return false;
}