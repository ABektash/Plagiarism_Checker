<link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">

<div class="navbar">
    <h1 class="navheader">PLAGIARISM DETECTION</h1>
    <ul class="navbar-menu">
        <a href="dashboard" class="nav-link">Dashboard</a>
        <a href="manageAssignments" class="nav-link">Manage Assignments</a>
        <a href="StudentManagement" class="nav-link">Manage Students</a>
        <a href="#footer" class="nav-link">Discussion forum</a>
    </ul>
    <div class="navbar-right">
        <button onclick="window.location.href='<?php url('login/index'); ?>'">Login</button>
        <button onclick="window.location.href='<?php url('signup/index'); ?>'">Sign Up</button>
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
