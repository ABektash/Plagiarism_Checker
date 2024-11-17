<?php
require_once MODELS . 'Forums.php';
class ForumsController extends Controller
{
    private $db;
    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index($id)
    {
        $forum = new Forums($this->db);
        $message = new Forums_Messages($this->db);

        $forumData = $forum->getForumById($id);

        if ($forumData) {

            $studentName = $forumData['StudentFirstName'] . ' ' . $forumData['StudentLastName'];
            $instructorName = $forumData['InstructorFirstName'] . ' ' . $forumData['InstructorLastName'];

            $messages = $message->getAllMessages($id);

            $data = [
                "forum" => $forumData,
                "studentName" => $studentName,
                "instructorName" => $instructorName,
                "messages" => $messages
            ];

            $this->view('forums', $data);
        } else {
            $this->view('404Page');
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forumID'])) {
            $forumID = $_POST['forumID'];
            $forum = new Forums($this->db);

            $result = $forum->delete($forumID);

            $data["messages"] = null;
            if ($result) {
                $this->view('manageSubmissions', $data);
            } else {
                $data["deleteError"] = "Couldn't delete user!";
                $this->view('forums', $data);
            }
        }
    }

    public function submit()
    {
        if (isset($_POST['submitforum'])) {
            $forum = new Forums($this->db);
            if ($forum->create($_POST['submissionID'], $_POST['instructorID'], $_POST['StudentID'])) {
                $this->view('forums');
            }
        }
        if (isset($_POST['submitmessage'])) {
            $message = new Forums_Messages($this->db);
            if ($message->create($_POST['forumID'], $_POST['senderID'], $_POST['messagetext'])) {
                $this->view('forums');
                $messages = $message->getAllMessages($_POST['forumID']);
                $data = ['allmessages' => $messages];

                $this->view('forums', $data);
            }
        }
    }
}
