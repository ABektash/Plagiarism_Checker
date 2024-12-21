<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

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

<body>
    <?php include 'inc/header.php'; ?>


    <main>

        <?php
        if ($_SESSION['user']['UserType_id'] == 3):

            $userID = $_SESSION['user']['ID'];

            $urlAssignments = redirect("dashboard/getAssignments") . "?userID=" . $userID;
            $responseAssignments = file_get_contents($urlAssignments);
            $assignmentsData = json_decode($responseAssignments, true);

            $urlSubmissions = redirect("dashboard/getSubmissions") . "?userID=" . $userID;
            $responseSubmissions = file_get_contents($urlSubmissions);
            $submissionsData = json_decode($responseSubmissions, true);

            $urlReports = redirect("dashboard/getReports") . "?userID=" . $userID;
            $responseReports = file_get_contents($urlReports);
            $reportsData = json_decode($responseReports, true);


            if ($assignmentsData['success']) {
                $assignments = $assignmentsData['assignments'];
                echo "<script>console.log(" . json_encode($assignments) . ");</script>";



                $renderedAssignments = [];

                echo '<section id="assignments">
                        <h2>Assignments due</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Assignment</th>
                                    <th>Submission Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>';

                foreach ($assignments as $assignmentJson) {
                    $assignment = json_decode($assignmentJson, true);

                    if (in_array($assignment['ID'], $renderedAssignments)) {
                        continue;
                    }
                    $renderedAssignments[] = $assignment['ID'];

                    $title = $assignment['Title'];
                    $dueDate = $assignment['DueDate'] ?? 'Not Set';
                    $submissionLink = redirect('submit/index');

                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($title) . '</td>';
                    echo '<td>' . htmlspecialchars($dueDate) . '</td>';
                    echo '<td><a class="a-link" href="' . redirect("SubmitAssignment/index?assignmentID=" . $assignment["ID"]) . '">View</a></td>';
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
                            </tr>
                        </thead>
                        <tbody>';


                foreach ($submissions as $submissionJson) {
                    $submission = json_decode($submissionJson, true);


                    $submissionDate = $submission['submissionDate'];
                    $status = $submission['status'];
                    $assignmentTitle = $submission['assignment']['Title'];

                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($assignmentTitle) . '</td>';
                    echo '<td>' . htmlspecialchars($submissionDate) . '</td>';
                    echo '<td>' . htmlspecialchars($status) . '</td>';
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

                        foreach ($reports as $index => $report) {

                            $submissionJson = $submissions[$index] ?? null;
                            $submission = $submissionJson ? json_decode($submissionJson, true) : null;
                        
                            $status = $submission['status'] ?? 'Unknown';
                            $viewLink = 'viewReport/index';
                            $linkStyle = ($status == 'Pending') ? 'color: grey;' : '';
                            $onclick = ($status == 'Pending') ? 'onclick="return false;"' : '';
                        
                            $assignmentTitle = $report['submission']['assignment']['Title'] ?? 'Unknown';
                            $similarityScore = isset($report['similarityPercentage']) ? $report['similarityPercentage'] . '%' : 'N/A';
                            $reportDate = $report['submission']['submissionDate'] ?? 'N/A';
                            $reportID = $report['ID'] ?? '';
                        
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($assignmentTitle, ENT_QUOTES) . '</td>';
                            echo '<td class="similarity-progress">' . htmlspecialchars($similarityScore, ENT_QUOTES) . '</td>';
                            echo '<td>' . htmlspecialchars($reportDate, ENT_QUOTES) . '</td>';
                            echo '<td><a class="a-link" href="' . htmlspecialchars($viewLink . "?reportID=" . $reportID, ENT_QUOTES) . '" style="' . $linkStyle . '" ' . $onclick . '>View</a></td>';
                            echo '</tr>';
                        }

                echo '</tbody>
                </table>
                </section>';
            } else {
                echo '<p>No plagiarism reports found.</p>';
            }

        ?>

        <?php endif; ?>


        <?php
        if ($_SESSION['user']['UserType_id'] == 2):
            $userID = $_SESSION['user']['ID'];

            $jsonResponse = file_get_contents(redirect("Dashboard/getGroupsAndCount") . "?userID=" . $userID);

            if ($jsonResponse === false) {
                echo "Error fetching data.";
                exit;
            }

            $groupsData = json_decode($jsonResponse, true);
            echo '
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
            <tbody>';


            if (is_array($groupsData)) {
                foreach ($groupsData as $group) {
                    echo '
            <tr>
                <td>Group ' . htmlspecialchars($group['GroupID']) . '</td>
                <td>' . htmlspecialchars($group['MemberCount']) . '</td>
                <td>' . htmlspecialchars($group['SubmissionCount']) . '</td>
                <td><a class="a-link" href="' . redirect("/manageGroupInsturctor") . '">View</a></td>
            </tr>';
                }
            } else {
                echo '<tr><td colspan="4">No groups found.</td></tr>';
            }

            echo '
            </tbody>
        </table>
    </section>';


            $response = file_get_contents(redirect("Dashboard/getInstructorData") . "?userID=" . $userID);
            $data = json_decode($response, true);

            // Submissions section
            echo '<section id="submissions">';
            echo '<h2>Submissions</h2>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Student</th>';
            echo '<th>Assignment</th>';
            echo '<th>Submission Date</th>';
            echo '<th>Status</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($data['submissions'] as $submission) {
                $report = null;
                foreach ($data['plagiarism_reports'] as $rep) {
                    if ($rep['submissionID'] === $submission['ID']) {
                        $report = $rep;
                        break;
                    }
                }
            
                echo '<tr>';
                echo '<td>' . htmlspecialchars($submission['studentName'] ?? 'Unknown') . '</td>';
                echo '<td>' . htmlspecialchars($submission['assignment']['Title'] ?? 'No Title') . '</td>';
                echo '<td>' . htmlspecialchars($submission['submissionDate'] ?? 'No Date') . '</td>';
                echo '<td>' . htmlspecialchars($submission['status'] ?? 'Unknown') . '</td>';
            
                if ($report) {
                    echo '<td><a class="a-link" href="' . redirect("viewreport/index?reportID=" . $report['ID']) . '">View</a></td>';
                } else {
                    echo '<td>No Report</td>';
                }
            
                echo '</tr>';
            }
            

            echo '</tbody>';
            echo '</table>';
            echo '</section>';

            // Plagiarism Reports section
            echo '<section id="reports">';
            echo '<h2>Plagiarism Reports</h2>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Student</th>';
            echo '<th>Assignment</th>';
            echo '<th>Similarity Score</th>';
            echo '<th>Report Date</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($data['plagiarism_reports'] as $report) {

                echo '<tr>';
                echo '<td>' . htmlspecialchars($report['submission']['studentName']) . '</td>';
                echo '<td>' . htmlspecialchars($report['submission']['assignment']['Title']) . '</td>';
                echo '<td>' . htmlspecialchars($report['similarityPercentage']) . '%</td>';
                echo '<td>' . htmlspecialchars($report['submission']['submissionDate']) . '</td>';
                echo '<td><a class="a-link" href="' . redirect("viewreport/index?reportID=" . $report['ID']) . '">View</a></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</section>';
        ?>


        <?php endif; ?>

    </main>

    <?php include 'inc/footer.php'; ?>

</body>

</html>