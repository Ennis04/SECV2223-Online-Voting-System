document.addEventListener("DOMContentLoaded", function () {
    const menuBar = document.getElementById("menuBar");
    const menu = document.querySelector(".menu");

    menuBar.addEventListener("click", () => {
        menu.classList.toggle("show");
        menuBar.classList.toggle("rotate");
    });

    document.addEventListener("click", (event) => {
        if (!menu.contains(event.target) && !menuBar.contains(event.target)) {
            menu.classList.remove("show");
            menuBar.classList.remove("rotate");
        }
    });

    // Set current year in footer
    document.querySelectorAll("#current-year").forEach(el => {
        el.textContent = new Date().getFullYear();
    });
});
