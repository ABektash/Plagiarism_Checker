<?php
require_once MODELS . 'Submission.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class SubmitAssignmentController extends Controller
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
        $id = $_GET['assignmentID'] ?? null;

        if (!$id) {
            $this->view('SubmitAssignment', ['error' => 'No assignment ID provided.']);
            return;
        }

        $assignmentModel = new Assignments($this->db);
        $submissionModel = new Submission($this->db);
        $alreadySubmitted = $submissionModel->alreadySubmitted($_SESSION["user"]["ID"], $id);
        $assignment = $assignmentModel->getAssignmentById($id);

        if ($assignment) {
            $this->view('SubmitAssignment', ['assignment' => $assignment, 'alreadySubmitted' => $alreadySubmitted]);
        } else {
            $this->view('SubmitAssignment', ['error' => 'No assignment data found.']);
        }
    }

    public function submitAssignment()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            $assignmentID = $data['assignmentID'] ?? null;
            $extractedText = $data['extractedText'] ?? '';

            if (!$assignmentID) {
                echo json_encode(['success' => false, 'message' => 'No assignment ID provided.']);
                return;
            }

            if (empty($extractedText)) {
                echo json_encode(['success' => false, 'message' => 'No text extracted from the file.']);
                return;
            }

            $submissionData = json_encode(['text' => $extractedText]);

            $submissionModel = new Submission($this->db);
            $userID = $_SESSION['user']['ID'] ?? null;

            $submissionID = $submissionModel->createSubmission($assignmentID, $userID, $submissionData);

            if ($submissionID) {
                echo json_encode(['success' => true, 'message' => 'Assignment submitted successfully.', 'submissionID' => $submissionID]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save the submission.']);
            }
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
        }
    }
}
