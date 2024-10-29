<link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
            <a href="<?php url('manageGroupInsturctor/index'); ?>" class="nav-link">My Groups</a>
        <?php endif; ?>
    </ul>

    <div class="navbar-right">
        <?php if (!isset($_SESSION['user'])): ?>
            <button onclick="window.location.href='<?php url('login/index'); ?>'">Login</button>
            <button onclick="window.location.href='<?php url('signup/index'); ?>'">Sign Up</button>
        <?php else: ?>

            <div class="profile-photo">
                <img src="/Plagiarism_Checker/public/assets/images/defaultpic.jpg" alt="Profile Photo" onclick="window.location.href='<?php url('profile/index'); ?>'">
                <span class="notification-dot"></span>
            </div>


            <div class="logout-icon">
                <i class="fa-solid fa-right-from-bracket fa-xl" onclick="openPopup()"></i>
            </div>


            <div id="popup" class="popup">
                <div class="popup-content">
                    <p>Are you sure you want to logout?</p>
                    <button onclick="window.location.href='<?php url('logout/index'); ?>'">Yes</button>
                    <button onclick="closePopup()">No</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="/Plagiarism_Checker/public\assets\js\header.js"></script>