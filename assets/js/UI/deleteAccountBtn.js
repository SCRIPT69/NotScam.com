document.getElementById("deleteAccountBtn").addEventListener("click", async () => {
    if (!confirm("Opravdu chcete smazat svůj účet? Tato akce je nevratná!")) {
        return;
    }
    if (!confirm("Jste jistý? TATO AKCE JE NEVRATNÁ!")) {
        return;
    }

    try {
        const response = await fetch("includes/profile/delete_account.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ delete: true })
        });

        const data = await response.json();

        if (data.success) {
            // Účet byl smazán - odhlásíme uživatele
            window.location.href = "index.php";
        }
        else {
            alert("Nepodařilo se smazat účet!");
        }

    } catch (err) {
        console.error("AJAX delete error:", err);
    }
});