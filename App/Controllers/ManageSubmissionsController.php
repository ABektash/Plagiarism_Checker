<?php
require_once MODELS . 'Submission.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ManageSubmissionsController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index()
    {
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 1)) {
            $submission = new Submission($this->db);
            $submissions = $submission->getAllSubmissions();
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
