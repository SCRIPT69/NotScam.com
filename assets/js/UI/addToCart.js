document.getElementById("addToCartBtn")?.addEventListener("click", async (e) => {
    const btn = e.target;
    const productId = e.target.dataset.productId;

    try {
        const response = await fetch("includes/cart/add_to_cart.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ productId })
        });

        const data = await response.json();

        if (data.success) {
            btn.classList.remove("product-detail__button-active");
            btn.classList.add("product-detail__button");
            btn.disabled = true;
        }
    } catch (err) {
        console.error(err);
    }
});