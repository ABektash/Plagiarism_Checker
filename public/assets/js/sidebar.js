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

function confirmLogout() {
    return confirm("Are you sure you want to logout?");
}
// document.addEventListener("DOMContentLoaded", function () {
//     const pathSegments = window.location.pathname.split("/"); 

//     const indexPosition = pathSegments.lastIndexOf("index");

//     const pageName = (indexPosition > 0) ? pathSegments[indexPosition - 1] : null; 

//     const menuItems = document.querySelectorAll('.side-menu.top li');
//     menuItems.forEach(item => {
//         const dataPage = item.getAttribute('data-page');
        
//         if (dataPage === pageName) {
//             item.classList.add('active');
//         } else {
//             item.classList.remove('active');
//         }
//     });
// });
