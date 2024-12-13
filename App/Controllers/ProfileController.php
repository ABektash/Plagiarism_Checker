<?php
require_once MODELS . 'Submission.php';
require_once MODELS . 'Groups.php';
require_once MODELS . 'Forums.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ProfileController extends Controller
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

        if (($id !== null) && ($userType == 2 || $userType == 3 || $userType == 4)) {
            $group = new Groups($this->db);
            $data["groupsCount"] = $group->getUserGroupCountByUserID($_SESSION['user']['ID']);

            if ($userType == 2) {
                $submission = new Submission($this->db);
                $forum = new Forums($this->db);
                $forumsData = $forum->getForumsData($_SESSION['user']['ID']);
                $assignments = $submission->getAssignmentsByUserID($_SESSION['user']['ID']);
                $data["numberOfAssignments"] = count($assignments);
                $data["assignments"] = $assignments;
                $data["forumsData"] = $forumsData;
            } elseif ($userType == 3) {
                $submission = new Submission($this->db);
                $forum = new Forums($this->db);
                $forumsData = $forum->getForumsData($_SESSION['user']['ID']);
                $submissions = $submission->getSubmissionsByUserId($_SESSION['user']['ID']);
                $data["numberOfAssignments"] = count($submissions);
                $data["submissions"] = $submissions;
                $data["forumsData"] = $forumsData;
            }

            $this->view('profile', $data);
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }
}
