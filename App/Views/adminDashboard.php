<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
	<link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/adminDashboard.css">

	<title>Plagiarism Detection</title>
</head>

<body>
	<?php include 'inc/sidebar.php'; ?>

	<section id="content">
		<?php include 'inc/navbar.php'; ?>

		<main>
			<div class="head-title">
				<h1>Dashboard</h1>
			</div>

			<div class="analytics-section">
				<h2 class="dashboard-h2">Analytics</h2>
				<div class="analytics-cards">

					<div class="card">
						<i class='bx bx-error'></i>
						<h3>Plagiarism Incidents</h3>
						<p><strong>120</strong> incidents reported this month</p>
						<canvas id="plagiarismChart"></canvas>
					</div>

					<div class="card">
						<i class='bx bx-user'></i>
						<h3>User Engagement</h3>
						<p><strong>450</strong> active users</p>
						<canvas id="engagementChart"></canvas>
					</div>

					<div class="card">
						<i class='bx bx-file'></i>
						<h3>Submissions</h3>
						<p><strong>300</strong> new submissions this week</p>
						<canvas id="submissionsChart"></canvas>
					</div>
				</div>

			</div>

			<div class="user-management-section">
				<h2 class="dashboard-h2">User Management</h2>
				<div class="user-management-cards">

					<div class="card">
						<i class='bx bx-group'></i>
						<h3>Groups</h3>
						<p><strong>6</strong> total groups</p>
						<a href="<?php url('manageGroups'); ?>" class="btn">Manage Students</a>
					</div>

					<div class="card">
						<i class='bx bx-chalkboard'></i>
						<h3>Instructors</h3>
						<p><strong>50</strong> total instructors</p>
						<a href="<?php url('manageUsers'); ?>" class="btn">Manage Instructors</a>
					</div>

					<div class="card">
						<i class='bx bx-group'></i>
						<h3>Students</h3>
						<p><strong>200</strong> total students</p>
						<a href="<?php url('manageUsers'); ?>" class="btn">Manage Students</a>
					</div>


				</div>
			</div>

			<div class="submission-management-section">
				<h2 class="dashboard-h2">Submission Management</h2>
				<div class="submission-cards">

					<div class="card">
						<i class='bx bx-task'></i>
						<h3>Assignments</h3>
						<p><strong>23</strong> active assignments</p>
						<a href="<?php url('manageAssignmentsAdmin'); ?>" class="btn">View Assignments</a>
					</div>

					<div class="card">
						<i class='bx bx-upload'></i>
						<h3>New Submissions</h3>
						<p><strong>120</strong> pending reviews</p>
						<a href="<?php url('submissionsManagement'); ?>" class="btn">View Submissions</a>
					</div>

					<div class="card">
						<i class='bx bx-chart'></i>
						<h3>Reports Generated</h3>
						<p><strong>180</strong> plagiarism reports this week</p>
						<a href=" <?php url('submissionsManagement'); ?>" class="btn">View Reports</a>
					</div>

					
				</div>
			</div>


		</main>

	</section>

</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/Plagiarism_Checker/public\assets\js\adminDashboard.js"></script>

</html>