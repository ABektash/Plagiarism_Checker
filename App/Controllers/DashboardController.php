<?php 
require_once MODELS .'AssignmentModel.php';
require_once MODELS .'SubmissionModel.php';
require_once MODELS .'PlagiarismReportModel.php';
require_once MODELS .'GroupsModel.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class DashboardController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG.'dbh.inc.php';
        $this->db = $conn; 
    }
    public function index()
    {
        $this->view('dashboard');
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
                $assignmentsFetcher = new AssignmentsFetcher($this->db, $userID);
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
        
                $submissionModel = new SubmissionFetcher($this->db, $userID);
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
                $submissionFetcher = new SubmissionFetcher($this->db, $userID);
                $submissions = $submissionFetcher->fetchAll();
                $submissionIDs = array_map(function($submission) {
                    return $submission->ID;
                }, $submissions);
        
                $reports = [];
                if (!empty($submissionIDs)) {
                    $plagiarismReportsFetcher = new PlagiarismReportsFetcher($this->db,$userID);
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
            $dataFetcher= new GroupsModel($this->db,$userID);
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
        $dataFetcher= new GroupsModel($this->db,$userID);
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