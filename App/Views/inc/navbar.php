<link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/navbar.css">
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <i class='bx bx-menu'></i>
    <a href="#" class="nav-link">Categories</a>
    <form action="#">
        <!-- <div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div> -->
    </form>
    <input type="checkbox" id="switch-mode" hidden>

    <!-- dark mode button -->
    <label for="switch-mode" class="switch-mode"></label>    

    <a href="<?php url('adminProfile/index/' . $_SESSION['user']['ID']); ?>" class="profile">
        <!-- waiting for the session later !!! -->
        <img src="/Plagiarism_Checker/public/assets/images/defaultpic.jpg">
    </a>
</nav>
<script src="/Plagiarism_Checker/public/assets/js/navbar.js"></script>