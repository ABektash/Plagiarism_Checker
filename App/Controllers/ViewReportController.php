<?php
require_once MODELS . 'plagiarismReport.php';
require_once MODELS . 'Submission.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ViewReportController extends Controller
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

        if (($id !== null)) {

            $this->view('viewReport');
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }

    public function saveReport()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            error_log("Raw Data: " . json_encode($data));

            $submissionID = $data['submissionID'] ?? null;
            $responseAPI = $data['responseAPI'] ?? null;
            $feedback = $data['feedback'] ?? null;
            $similarityPercentage = $this->calculateSimilarity($responseAPI);

            if (!$submissionID || !$responseAPI) {
                echo json_encode(['success' => false, 'message' => 'Missing submissionID or responseAPI.']);
                return;
            }

            $plagiarismReportModel = new PlagiarismReport($this->db);
            $submissionModel = new Submission($this->db);

            $result = $plagiarismReportModel->saveReport($submissionID, $responseAPI, $feedback, $similarityPercentage, null);

            if ($result) {
                $submissionModel->updateStatus($submissionID, 'Pending');
                echo json_encode(['success' => true, 'message' => 'Report saved successfully and submission status updated to Pending.']);
            } else {
                error_log("Failed to save report for submissionID: $submissionID");
                echo json_encode(['success' => false, 'message' => 'Failed to save the report.']);
            }
        } catch (Exception $e) {
            error_log('Exception in saveReport: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
        }
    }


    private function calculateSimilarity($responseAPI)
    {
        $apiResponse = json_decode($responseAPI, true);
        $similarityScore = $apiResponse['originalityai']['plagia_score'] ?? 0;

        return $similarityScore * 100;
    }
}
