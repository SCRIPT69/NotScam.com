export function setError(input, errorMsg) {
    let errorP = input.parentElement.querySelector(".validation-error");
    if (!errorP) return;
    
    errorP.textContent = errorMsg;
    input.classList.add("register__input-error");

}
export function clearError(input) {
    input.classList.remove("register__input-error");
    let errorP = input.parentElement.querySelector(".validation-error");
    if (!errorP) return;
    errorP.textContent = "";
}