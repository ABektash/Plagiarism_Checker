<?php
require_once MODELS . 'Submission.php';
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
        $submission = new Submission($this->db);
        $submissions = $submission->getAllSubmissions();
        $data["submissions"] = $submissions;

        $this->view('manageSubmissions', $data);
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
