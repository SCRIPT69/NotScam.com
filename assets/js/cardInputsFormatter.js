function addSpacesForCardNumber(event) {
    if (event.inputType === "deleteContentBackward") return;

    let value = event.target.value.replace(/\s+/g, ""); // odstraníme všechny mezery
    if (value.length % 4 == 0 && value.length < 16) {
        event.target.value = event.target.value + " ";
    }
}

document.getElementById("cardnumber").addEventListener("input", addSpacesForCardNumber);

function addBackSlashCardExpiration(event) {
    if (event.inputType === "deleteContentBackward") return;

    let value = event.target.value;
    if (value.length == 2 && value[1] != "/") {
        event.target.value = value + "/";
    }
}
document.getElementById("cardexpiration").addEventListener("input", addBackSlashCardExpiration);