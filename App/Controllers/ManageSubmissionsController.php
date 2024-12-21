<?php
require_once MODELS . 'Submission.php';
require_once MODELS . 'PlagiarismReport.php';
require_once MODELS . 'Forums.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ManageSubmissionsController extends Controller
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

        if (($id !== null) && ($userType == 1)) {
            $submission = new Submission($this->db);
            $plagiarism = new PlagiarismReport($this->db);
            $forum = new Forums($this->db);

            $submissions = $submission->getAllSubmissions();

            foreach ($submissions as &$sub) {
                $sub['reportID'] = $plagiarism->getReportIdBySubmissionId($sub['submissionID']);
                $sub['forumID'] = $forum->getForumIdBySubmissionId($sub['submissionID']);
            }

            $data["submissions"] = $submissions;

            $this->view('manageSubmissions', $data);
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submissionID'])) {
            $submissionID = $_POST['submissionID'];
            $submission = new Submission($this->db);

            $result = $submission->deleteSubmission($submissionID);
            $submissions = $submission->getAllSubmissions();
            $data["submissions"] = $submissions;
            if ($result) {
                $this->view('manageSubmissions', $data);
            } else {
                $data["deleteError"] = "Couldn't delete user!";
                $this->view('manageSubmissions', $data);
            }
        }
    }
}
