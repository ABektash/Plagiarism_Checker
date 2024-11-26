<?php 

require_once MODELS.'User.php'; 
require_once MODELS . 'Submission.php';
require_once MODELS . 'Groups.php';
require_once MODELS . 'Forums.php';

class AdminProfileController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG.'dbh.inc.php';
        $this->db = $conn; 
    }


    public function index($id)
    { 
        $user = new User($this->db);
        $userDetails = $user->getUserById($id);

        $group = new Groups($this->db);
        $userDetails["groupsCount"] = $group->getUserGroupCountByUserID($id);

        
        if ($userDetails){
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
            $this->view('404Page');
        }
    }
    
}