const passwordInput = document.getElementById("password");
const passwordConfirmInput = document.getElementById("passwordconfirm");
const btn = document.getElementById("showPwdButton");

function showPasswords() {
    if (passwordInput) passwordInput.type = "text";
    if (passwordConfirmInput) passwordConfirmInput.type = "text";
}

function hidePasswords() {
    if (passwordInput) passwordInput.type = "password";
    if (passwordConfirmInput) passwordConfirmInput.type = "password";
}

btn.addEventListener("mousedown", showPasswords);
btn.addEventListener("mouseup", hidePasswords);
btn.addEventListener("mouseleave", hidePasswords);