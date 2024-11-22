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
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index()
    {
        $group = new Groups($this->db);
        $data["groupsCount"] = $group->getUserGroupCountByUserID($_SESSION['user']['ID']);

        if ($_SESSION['user']['UserType_id'] == 2) {
            $submission = new Submission($this->db);
            $forum = new Forums($this->db);
            $forumsData = $forum->getForumsData($_SESSION['user']['ID']);
            $assignments = $submission->getAssignmentsByUserID($_SESSION['user']['ID']);
            $data["numberOfAssignments"] = count($assignments);
            $data["assignments"] = $assignments;
            $data["forumsData"] = $forumsData;
        } elseif ($_SESSION['user']['UserType_id'] == 3) {
            $submission = new Submission($this->db);
            $forum = new Forums($this->db);
            $forumsData = $forum->getForumsData($_SESSION['user']['ID']);
            $submissions = $submission->getSubmissionsByUserId($_SESSION['user']['ID']);
            $data["numberOfAssignments"] = count($submissions);
            $data["submissions"] = $submissions;
            $data["forumsData"] = $forumsData;
        }
        

        $this->view('profile', $data);
    }
    
}