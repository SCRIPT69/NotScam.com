export function setError(input, errorMsg) {
    let errorP = input.parentElement.querySelector(".validation-error");
    if (!errorP) return;
    
    errorP.textContent = errorMsg;
    input.classList.add("auth__input-error");

}
export function clearError(input) {
    input.classList.remove("auth__input-error");
    let errorP = input.parentElement.querySelector(".validation-error");
    if (!errorP) return;
    errorP.textContent = "";
}