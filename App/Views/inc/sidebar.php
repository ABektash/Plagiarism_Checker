<link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/sidebar.css">
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<section id="sidebar">
    <a href="<?php url('adminDashboard'); ?>" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">Plagiarism Detection</span>
    </a>
    <ul class="side-menu top">
        <?php if (isset($_SESSION['pages'])): ?>
            <?php if (in_array('Admin Dashboard', $_SESSION['pages'])): ?>
                <li data-page="adminDashboard">
                    <a href="<?php url('adminDashboard'); ?>">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (in_array('Manage Users', $_SESSION['pages'])): ?>
                <li data-page="manageUsers">
                    <a href="<?php url('manageUsers'); ?>">
                        <i class='bx bxs-shopping-bag-alt'></i>
                        <span class="text">Manage Users</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (in_array('Manage Groups', $_SESSION['pages'])): ?>
                <li data-page="manageGroups">
                    <a href="<?php url('manageGroups'); ?>">
                        <i class='bx bxs-group'></i>
                        <span class="text">Manage Groups</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (in_array('Manage Assignments', $_SESSION['pages'])): ?>
                <li data-page="manageAssignmentsAdmin">
                    <a href="<?php url('manageAssignmentsAdmin'); ?>">
                        <i class='bx bxs-doughnut-chart'></i>
                        <span class="text">Manage Assignments</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (in_array('Manage Submissions', $_SESSION['pages'])): ?>
                <li data-page="manageSubmissions">
                    <a href="<?php url('manageSubmissions'); ?>">
                        <i class='bx bxs-file'></i>
                        <span class="text">Manage Submissions</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (in_array('Manage Permissions', $_SESSION['pages'])): ?>
                <li data-page="managePermissions">
                    <a href="<?php url('managePermissions'); ?>">
                        <i class='bx bxs-message-dots'></i>
                        <span class="text">Manage Permissions</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>

    <ul class="side-menu">
        <li>
            <a href="<?php url('logout'); ?>" class="logout" onclick="return confirmLogout()">
            <i class='bx bxs-log-out-circle' style="font-size: 1.25rem;"></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>

<script src="/Plagiarism_Checker/public\assets\js\sidebar.js"></script>