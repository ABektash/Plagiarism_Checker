<?php
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

        $submissionModel = new SubmissionFetcher($conn, $userID);
        $submissions = $submissionModel->fetchAll();

        $submissionsJson = array_map(function($submission) {
            return $submission->returnAsJson();
        }, $submissions);

        echo json_encode([
            'success' => true,
            'submissions' => $submissionsJson
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?>
