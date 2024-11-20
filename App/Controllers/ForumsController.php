<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once MODELS . 'Forums.php';
class ForumsController extends Controller
{
    private $db;
    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index()
    {
        // $id = $_SESSION['user']['ID'];
        // $forum = new Forums($this->db);

        // $allForums = $forum->getAllForums($id);

        // if ($allForums) {
        //     $data = [
        //         "allForums" => $allForums,
        //     ];

            $this->view('forums');//, $data
        // } else {
        //     $this->view('404Page');
        // }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forumID'])) {
            $forumID = $_POST['forumID'];
            $forum = new Forums($this->db);

            $result = $forum->delete($forumID);

            if ($result) {
                $id = $_SESSION['user']['ID'];
                $forum = new Forums($this->db);
                $forumData = $forum->getForumById($id);

                $data = [
                    "forum" => $forumData,
                    "messages" => null
                ];

                $this->view('manageSubmissions', $data);
            } else {
                $data["deleteError"] = "Couldn't delete the forum!";
                $this->view('forums', $data);
            }
        }
    }

    public function submit()
    {
        if (isset($_POST['submitCreateMessage'])) {
            $message = new Forums_Messages($this->db);

            if ($message->create($_POST['forumID'], $_POST['senderID'], $_POST['messagetext'])) {

                $messages = $message->getAllMessages($_POST['forumID']);
                $data = ['messages' => $messages];
                $this->view('forums', $data);

            } else {
                $data["sendingError"] = "Couldn't send the message!";
                $this->view('forums', $data);
            }
        } else if (isset($_POST['submitGetForum'])) {
            $forum = new Forums($this->db);
            $message = new Forums_Messages($this->db);


            if ($forumData = $forum->getForumById($_POST['forumID'])) {

                $studentName = $forumData['StudentFirstName'] . ' ' . $forumData['StudentLastName'];
                $instructorName = $forumData['InstructorFirstName'] . ' ' . $forumData['InstructorLastName'];
                $messages = $message->getAllMessages($_POST['forumID']);

                $data = [
                    "forum" => $forumData,
                    "studentName" => $studentName,
                    "instructorName" => $instructorName,
                    "messages" => $messages
                ];                
                $this->view('forums',$data);

            } else {
                $data["gettingForumError"] = "Couldn't get the chat!";
                $this->view('forums', $data);
            }

        } else if (isset($_POST['submitCreateForum'])) {
            $forum = new Forums($this->db);

            if ($forum->create($_POST['submissionID'], $_POST['instructorID'], $_POST['studentID'])) {
                $this->view('forums');
            } else {
                $data["creatingForumError"] = "Couldn't create the chat!";
                $this->view('forums', $data);
            }
        }
    }
}
