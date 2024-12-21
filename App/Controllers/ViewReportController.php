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

        if (($id !== null) && ($userType == 1 || $userType == 2 || $userType == 3)) {
            $reportID = $_GET['reportID'] ?? null;

            if ($reportID !== null) {
                $plagiarismReport = new plagiarismReport($this->db);
                $data = $plagiarismReport->getReportData($reportID);

                if ($data) {
                    $this->view('viewReport', $data);
                } else {
                    $data = [
                        "error_code" => 404,
                        "error_message" => "Report not found or could not be retrieved.",
                        "page_To_direct" => "home",
                    ];
                    $this->view('errorPage', $data);
                }
            } else {
                $data = [
                    "error_code" => 400,
                    "error_message" => "Invalid or missing report ID.",
                    "page_To_direct" => "home",
                ];
                $this->view('errorPage', $data);
            }
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

    public function FinalizeReport()
    {
        try {
            header('Content-Type: application/json');

            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['reportID'], $input['feedback'], $input['grade'])) {
                throw new Exception('Invalid input.');
            }

            $reportID = $input['reportID'];
            $feedback = $input['feedback'];
            $grade = $input['grade'];

            if ($grade < 0 || $grade > 100) {
                throw new Exception('Grade must be between 0 and 100.');
            }

            $plagiarismReportModel = new PlagiarismReport($this->db);
            $submissionModel = new Submission($this->db);

            if (!$plagiarismReportModel->updateReportFeedback($reportID, $feedback)) {
                throw new Exception('Failed to update feedback.');
            }

            if (!$plagiarismReportModel->updateReportGrade($reportID, $grade)) {
                throw new Exception('Failed to update grade.');
            }

            $submissionID = $plagiarismReportModel->getSubmissionIDofReport($reportID);
            if (!$submissionID) {
                throw new Exception('Failed to fetch submission ID.');
            }

            if (!$submissionModel->updateStatus($submissionID, 'Reviewed')) {
                throw new Exception('Failed to update submission status.');
            }

            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Report finalized successfully.'
            ]);
        } catch (Exception $e) {
            error_log('Error in finalize_report: ' . $e->getMessage());
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
