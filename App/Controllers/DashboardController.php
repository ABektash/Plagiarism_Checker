<?php
require_once MODELS . 'Assignments.php';
require_once MODELS . 'Submission.php';
require_once MODELS . 'PlagiarismReport.php';
require_once MODELS . 'Groups.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class DashboardController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'DatabaseConnection.php';
        $db_instance = DatabaseConnection::getInstance();
        $conn = $db_instance->getConnection();
        $this->db = $conn;
    }
    public function index()
    {
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 2 || $userType == 3)) {

            $this->view('dashboard');
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }
    public function getAssignments()
    {
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
                $assignmentsFetcher = new Assignments($this->db);
                $assignments = $assignmentsFetcher->fetchAll();

                $assignmentsJson = array_map(function ($assignment) {
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
    }
    public function getSubmissions()
    {
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

                $submissionModel = new Submission($this->db);
                $submissions = $submissionModel->fetchAll();

                $submissionsJson = array_map(function ($submission) {
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
    }
    public function getReports()
    {
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
                $submissionFetcher = new Submission($this->db);
                $submissions = $submissionFetcher->fetchAll();
                $submissionIDs = array_map(function ($submission) {
                    return $submission->ID;
                }, $submissions);

                $reports = [];
                if (!empty($submissionIDs)) {
                    $plagiarismReportsFetcher = new PlagiarismReport($this->db, null, $userID);
                    $reports = $plagiarismReportsFetcher->fetchBySubmissionIDs($submissionIDs);
                }
                $reportsJson = array_map(function ($report) {
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
    }

    public function getInstructorData()
    {
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
            $dataFetcher = new Groups($this->db);
            try {

                echo $dataFetcher->returnAsJson();
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    public function getGroupsAndCount()
    {
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
            $dataFetcher = new Groups($this->db);
            try {

                echo $dataFetcher->getGroupsAndCountAsJson();
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
