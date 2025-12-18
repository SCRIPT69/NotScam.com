const forms = document.getElementsByClassName("deleteproductform");

Array.from(forms).forEach(form => {
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        if (confirm("Opravdu chcete smazat produkt? Tato akce je nevratná!")) {
            form.submit();
        }
    });
});