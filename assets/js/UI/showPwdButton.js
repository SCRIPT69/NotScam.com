const passwordInputs = document.querySelectorAll('input[type="password"]');
const btn = document.getElementById("showPwdButton");

function showPasswords() {
    for (const input of passwordInputs) {
        input.type = "text";
    }
}

function hidePasswords() {
    for (const input of passwordInputs) {
        input.type = "password";
    }
}

btn.addEventListener("mousedown", showPasswords);
btn.addEventListener("mouseup", hidePasswords);
btn.addEventListener("mouseleave", hidePasswords);