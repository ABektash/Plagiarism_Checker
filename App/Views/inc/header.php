<link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="navbar">
<h1 class="navheader" onclick="window.location.href='<?php url('home/index'); ?>'">PLAGIARISM DETECTION</h1>

<ul class="navbar-menu">
    <?php if (isset($_SESSION['pages'])): ?>
        <?php if (in_array('Dashboard', $_SESSION['pages'])): ?>
            <a href="<?php url('dashboard/index'); ?>" class="nav-link">Dashboard</a>
        <?php endif; ?>

        <?php if (in_array('Manage Assignments', $_SESSION['pages'])): ?>
            <a href="<?php url('manageAssignments/index'); ?>" class="nav-link">Manage Assignments</a>
        <?php endif; ?>

        <?php if (in_array('My Groups', $_SESSION['pages'])): ?>
            <a href="<?php url('manageGroupInsturctor/index'); ?>" class="nav-link">My Groups</a>
        <?php endif; ?>
    <?php endif; ?>
</ul>

    <div class="navbar-right">
        <?php if (!isset($_SESSION['user'])): ?>
            <button onclick="window.location.href='<?php url('login/index'); ?>'">Login</button>
            <button onclick="window.location.href='<?php url('signup/index'); ?>'">Sign Up</button>
        <?php else: ?>

            <div class="profile-photo">
                <!-- <img src="/Plagiarism_Checker/public/assets/images/defaultpic.jpg" alt="Profile Photo" onclick="window.location.href='<?php url('profile/index'); ?>'"> -->
                <i alt="Profile Photo" class="fa-solid fa-user fa-xl" id="profile-icon" onclick="window.location.href='<?php url('profile/index'); ?>'"></i> 
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