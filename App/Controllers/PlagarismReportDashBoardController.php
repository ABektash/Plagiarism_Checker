<?php
require_once '../Models/PlagiarismReportModel.php';
require_once '../Models/SubmissionModel.php';
require_once '../Config/dbh.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $errors = [];

    if (empty($_GET['userID'])) {
        $errors['userIDError'] = "User ID is required.";
    } elseif (!is_numeric($_GET['userID'])) {
        $errors['userIDError'] = "User ID must be numeric.";
    }

    if (!empty($errors)) {
        echo json_encode(['errors' => $errors]);
        exit;
    }

    $userID = intval($_GET['userID']);

    try {
        $submissionFetcher = new SubmissionFetcher($conn, $userID);
        $submissions = $submissionFetcher->fetchAll();
        $submissionIDs = array_map(function($submission) {
            return $submission->ID;
        }, $submissions);

        $reports = [];
        if (!empty($submissionIDs)) {
            $plagiarismReportsFetcher = new PlagiarismReportsFetcher($conn,$userID);
            $reports = $plagiarismReportsFetcher->fetchBySubmissionIDs($submissionIDs);
        }
        $reportsJson = array_map(function($report) {
            return json_decode($report->returnAsJson());
        }, $reports);

        echo json_encode([
            'success' => true,
            'plagiarismReports' => $reportsJson
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?>


