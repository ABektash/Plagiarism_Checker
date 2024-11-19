<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/dashboard.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css">
</head>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<body>
    <?php include 'inc/header.php'; ?>


    <main>

        <?php
        // session_start();
        // $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        // Fetch user ID from session
        if ($_SESSION['user']['UserType_id'] == 3):

            $userID = $_SESSION['user']['ID'];
            
            $urlAssignments = "http://localhost/Plagiarism_Checker/App/Controllers/AssignmentDashboardController.php?userID=$userID";
            $responseAssignments = file_get_contents($urlAssignments);
            $assignmentsData = json_decode($responseAssignments, true);
        
            $urlSubmissions = "http://localhost/Plagiarism_Checker/App/Controllers/SubmissionDashboardController.php?userID=$userID";
            $responseSubmissions = file_get_contents($urlSubmissions);
            $submissionsData = json_decode($responseSubmissions, true);
    
            $urlReports = "http://localhost/Plagiarism_Checker/App/Controllers/PlagarismReportDashBoardController.php?userID=$userID";
            $responseReports = file_get_contents($urlReports);
            $reportsData = json_decode($responseReports, true);
        
 
            if ($assignmentsData['success']) {
                $assignments = $assignmentsData['assignments'];
        

                echo '<!-- Student Dashboard -->
                <section id="assignments">
                    <h2>Assignments due</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Submission Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>';
        
                foreach ($assignments as $assignmentJson) {
                    $assignment = json_decode($assignmentJson, true);
                    
                    $title = $assignment['Title'];
                    $dueDate = $assignment['DueDate'] ?? 'Not Set';
                    $status = 'Pending';
                    $submissionLink = 'submit/index'; 
                    
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($title) . '</td>';
                    echo '<td>' . htmlspecialchars($dueDate) . '</td>';
                    echo '<td>' . htmlspecialchars($status) . '</td>';
                    echo '<td><a class="a-link" href="' . $submissionLink . '">View</a></td>';
                    echo '</tr>';
                }
        
                echo '</tbody>
                </table>
                </section>';
            } else {
                echo '<p>No assignments found.</p>';
            }
        
            // Start Submissions Table
            if ($submissionsData['success']) {
                $submissions = $submissionsData['submissions'];
        
                echo '<section id="submissions">
                    <h2>My Submissions</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Submission Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>';
        
              
                foreach ($submissions as $submissionJson) {
                    $submission = json_decode($submissionJson, true);
        
                    
                    $submissionDate = $submission['submissionDate'];
                    $status = $submission['status']; 
                    $assignmentTitle = $submission['assignment']['Title']; 
                    
                    
                    if ($status == 'Pending') {
                        $viewLink = 'viewReport/index'; 
                        $linkStyle = 'color: grey;'; 
                        $onclick = 'onclick="return false;"'; 
                    } else {
                        $viewLink = 'viewReport/index';
                        $linkStyle = ''; 
                        $onclick = ''; 
                    }
        
                   
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($assignmentTitle) . '</td>';
                    echo '<td>' . htmlspecialchars($submissionDate) . '</td>';
                    echo '<td>' . htmlspecialchars($status) . '</td>';
                    echo '<td><a class="a-link" href="' . $viewLink . '" style="' . $linkStyle . '" ' . $onclick . '>View</a></td>';
                    echo '</tr>';
                }
        
                echo '</tbody>
                </table>
                </section>';
            } else {
                echo '<p>No submissions found.</p>';
            }
        
            // Start Plagiarism Reports Table
            if ($reportsData['success']) {
                $reports = $reportsData['plagiarismReports'];
            
                
                echo '<section id="reports">
                    <h2>Plagiarism Reports</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Similarity Score</th>
                                <th>Report Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>';
            
               
                foreach ($reports as $report) { 
                    
                    $assignmentTitle = $report['submission']['assignment']['Title']; 
                    $similarityScore = $report['similarityPercentage'] . '%'; 
                    $reportDate = $report['submission']['submissionDate']; 
                    $viewReportLink = 'viewReport/index';
            
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($assignmentTitle) . '</td>';
                    echo '<td class="similarity-progress">' . htmlspecialchars($similarityScore) . '</td>';
                    echo '<td>' . htmlspecialchars($reportDate) . '</td>';
                    echo '<td><a class="a-link" href="' . $viewReportLink . '">View Report</a></td>';
                    echo '</tr>';
                }
            
                echo '</tbody>
                </table>
                </section>';
            } else {
                echo '<p>No plagiarism reports found.</p>';
            }
        
    ?>
        
            <!-- Student Dashboard -->       
            

            

            <section id="progress">
                <h2>Progress Tracking</h2>
                <p>Track your improvement over time:</p>
                <br>
                <div class="progress-bar">
                    <div class="progress" style="width: 70%;">70%</div>
                </div>
                <p>Last submission: Essay on Climate Change - 12% similarity.</p>
            </section>
        <?php endif; ?>


        <?php

        //if ($user_role == 'instructor'):
        if ($_SESSION['user']['UserType_id'] == 2):
        ?>
            <!-- Instructor Dashboard -->
            <section id="groups">
                <h2>Groups</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Total Students</th>
                            <th>Submissions Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Group A</td>
                            <td>30</td>
                            <td>28</td>
                            <td><a class="a-link" href="<?php url('manageGroupInsturctor/index'); ?>">View</a></td>
                        </tr>
                        <tr>
                            <td>Group B</td>
                            <td>25</td>
                            <td>20</td>
                            <td><a class="a-link" href="<?php url('manageGroupInsturctor/index'); ?>">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section id="submissions">
                <h2>Submissions</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Assignment</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>Essay on Climate Change</td>
                            <td>2024-10-10</td>
                            <td>Pending</td>
                            <td><a class="a-link" href="<?php url('viewEssay/index'); ?>">Review</a></td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>History Assignment</td>
                            <td>2024-10-12</td>
                            <td>Reviewed</td>
                            <td><a class="a-link" href="<?php url('viewEssay/index'); ?>">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section id="reports">
                <h2>Plagiarism Reports</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Assignment</th>
                            <th>Similarity Score</th>
                            <th>Report Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>Essay on Climate Change</td>
                            <td>12%</td>
                            <td>2024-10-11</td>
                            <td><a class="a-link" href="<?php url('viewReportInstructor/index'); ?>">View Report</a></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>

    </main>

    <?php include 'inc/footer.php'; ?>

</body>

</html>