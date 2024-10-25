<link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="navbar">
    <h1 class="navheader">PLAGIARISM DETECTION</h1>

    <ul class="navbar-menu">
        <?php if (isset($_SESSION['user']['UserType_id']) && $_SESSION['user']['UserType_id'] == 2): ?>
            <a href="<?php url('dashboard/index'); ?>" class="nav-link">Dashboard</a>
            <a href="<?php url('manageAssignments/index'); ?>" class="nav-link">Manage Assignments</a>
        <?php endif; ?>
    </ul>

    <div class="navbar-right">
        <?php if (!isset($_SESSION['user'])): ?>
            <button onclick="window.location.href='<?php url('login/index'); ?>'">Login</button>
            <button onclick="window.location.href='<?php url('signup/index'); ?>'">Sign Up</button>
        <?php else: ?>
            <div class="profile-photo">
                <img src="/Plagiarism_Checker/public/assets/images/defaultpic.jpg" alt="Profile Photo" onclick="window.location.href='<?php url('profile/index'); ?>'">
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    const navLinks = document.querySelectorAll('.navbar-menu .nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function () {
            navLinks.forEach(link => link.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
