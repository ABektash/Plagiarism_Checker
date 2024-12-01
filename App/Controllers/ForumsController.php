<?php
require_once MODELS . 'Forums.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 2 || $userType == 3)) {
            $forum = new Forums($this->db);
            $message = new Forums_Messages($this->db);

            $allForums = $forum->getAllForums($id);
            $messages = [];

            if (!empty($allForums) && isset($_GET['forumID'])) {
                $forumID = $_GET['forumID'];
                $messages = $message->getAllMessages($forumID);
            }

            $data = [
                "allForums" => $allForums,
                "messages" => $messages,
            ];

            $this->view('forums', $data);
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
                $latestMessage = end($messages);
                $data = [
                    'success' => true,
                    'messages' => $messages,
                    'latestMessage' => $latestMessage,
                ];
                header('Content-Type: application/json');
                echo json_encode($data);
            } else {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => "Couldn't send the message!"]);
            }
            exit;
        } elseif (isset($_GET['submitGetForum'])) {
            $forum = new Forums($this->db);
            $message = new Forums_Messages($this->db);

            $forumData = $forum->getForumById($_GET['forumID']);
            $messages = $message->getAllMessages($_GET['forumID']);

            if ($forumData) {
                $studentName = $forumData['StudentFirstName'] . ' ' . $forumData['StudentLastName'];
                $instructorName = $forumData['InstructorFirstName'] . ' ' . $forumData['InstructorLastName'];

                $data = [
                    "forum" => $forumData,
                    "studentName" => $studentName,
                    "instructorName" => $instructorName,
                    "messages" => $messages,
                    "UserID" => $_SESSION['user']['ID'],
                    "UserType_id" => $_SESSION['user']['UserType_id'],
                ];

                header('Content-Type: application/json');
                echo json_encode($data);
            } else {
                header('Content-Type: application/json');
                echo json_encode(["error" => "Couldn't get the chat!"]);
            }
            exit;
        } elseif (isset($_POST['submitCreateForum'])) {
            $forum = new Forums($this->db);

            if ($forum->create($_POST['submissionID'], $_POST['instructorID'], $_POST['studentID'])) {
                $this->view('forums');
            } else {
                $data["creatingForumError"] = "Couldn't create the chat!";
                $this->view('forums', $data);
            }
            exit;
        }
    }
}
