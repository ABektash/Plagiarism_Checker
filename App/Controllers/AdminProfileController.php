<?php
require_once MODELS . 'User.php';
require_once MODELS . 'Submission.php';
require_once MODELS . 'Groups.php';
require_once MODELS . 'Forums.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class AdminProfileController extends Controller
{
    private $db;
    public function __construct()
    {
        require_once CONFIG . 'DatabaseConnection.php';
        $db_instance = DatabaseConnection::getInstance();
        $conn = $db_instance->getConnection();
        $this->db = $conn;
    }

    public function index($id)
    {
        $sid = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($sid !== null) && ($userType == 1)) {

            $user = new User($this->db);
            $userDetails = $user->getUserById($id);




            if ($userDetails) {
                $group = new Groups($this->db);
                $userDetails["groupsCount"] = $group->getUserGroupCountByUserID($id);

                if ($userDetails["UserType_id"] == 2) {

                    $submission = new Submission($this->db);
                    $forum = new Forums($this->db);
                    $forumsData = $forum->getForumsData($id);
                    $assignments = $submission->getAssignmentsByUserID($id);
                    $userDetails["numberOfAssignments"] = count($assignments);
                    $userDetails["assignments"] = $assignments;
                    $userDetails["forumsData"] = $forumsData;
                } elseif ($userDetails["UserType_id"] == 3) {

                    $submission = new Submission($this->db);
                    $forum = new Forums($this->db);
                    $forumsData = $forum->getForumsData($id);
                    $submissions = $submission->getSubmissionsByUserId($id);
                    $userDetails["numberOfAssignments"] = count($submissions);
                    $userDetails["submissions"] = $submissions;
                    $userDetails["forumsData"] = $forumsData;
                }

                $this->view('adminProfile', $userDetails);
            } else {

                $data = [
                    "error_code" => 404,
                    "error_message" => "We're sorry, but the page you're looking for doesn't exist, deleted or may have been moved.",
                    "page_To_direct" => "manageUsers",
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
}
