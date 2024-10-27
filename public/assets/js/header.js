function openPopup() {
  document.getElementById("popup").style.display = "flex";
}

function closePopup() {
  document.getElementById("popup").style.display = "none";
}

function confirmLogout() {
  window.location.href = "../home";
  closePopup();
}

const navLinks = document.querySelectorAll(".navbar-menu .nav-link");

navLinks.forEach((link) => {
  link.addEventListener("click", function () {
    navLinks.forEach((link) => link.classList.remove("active"));
    this.classList.add("active");
  });
});

function showNotification() {
  const profilePhoto = document.querySelector(".profile-photo");
  profilePhoto.classList.add("active"); 
}

showNotification(); //34an t3ml show ll notification
