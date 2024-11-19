<?php
require_once '../Models/AssignmentModel.php';
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
        $assignmentsFetcher = new AssignmentsFetcher($conn, $userID);
        $assignments = $assignmentsFetcher->fetchAll();

        $assignmentsJson = array_map(function($assignment) {
            return $assignment->returnAsJson();
        }, $assignments);

        echo json_encode([
            'success' => true,
            'assignments' => $assignmentsJson
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?>
