function toggleBurgerMenu() {
    const burgerMenu = document.getElementById("burger-menu");
    burgerMenu.classList.toggle("active");
}

const burger = document.getElementById("burger");
burger.addEventListener("click", toggleBurgerMenu);
const burgerExitButton = document.getElementById("burger-exitButton");
burgerExitButton.addEventListener("click", toggleBurgerMenu);