const saveBtn = document.getElementById("savebtn");
const inputs = [document.getElementById("name"), document.getElementById("email"), document.getElementById("currentpassword"), document.getElementById("newpassword"), document.getElementById("newpasswordconfirm")]

const originalValues = {};
inputs.forEach(input => {
    originalValues[input.id] = input.value.trim() || '';
});

function checkIfFormIsDirty() {
    // 1. Pokud je změněné jméno / email → formulář je špinavý
    for (const input of [inputs[0], inputs[1]]) {
        if (input.value.trim() !== originalValues[input.id]) {
            return true;
        }
    }

    // 2. Pokud uživatel píše do jakéhokoliv heslového pole → špinavé
    if (
        inputs[2].value.trim().length > 0 ||
        inputs[3].value.trim().length > 0 ||
        inputs[4].value.trim().length > 0
    ) {
        return true;
    }

    return false;
}

function setActiveSaveBtn(e) {
    if (checkIfFormIsDirty()) {
        // Aktivace tlačítka:
        saveBtn.disabled = false;
        saveBtn.classList.remove("profile__button-notactive");
        saveBtn.classList.add("profile__button-active");
    }
    else {
        // Deaktivace tlačítka:
        saveBtn.disabled = true;
        saveBtn.classList.remove("profile__button-active");
        saveBtn.classList.add("profile__button-notactive");
    }
}

for (const input of inputs) {
    input.addEventListener("input", setActiveSaveBtn);
}