document.addEventListener("DOMContentLoaded", function () {
    const path = window.location.pathname.split("/").pop(); 

    const menuItems = document.querySelectorAll('.side-menu.top li');
    menuItems.forEach(item => {
        if (item.getAttribute('data-page') === path) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
});