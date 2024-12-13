<?php
require_once MODELS . 'Assignments.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ManageAssignmentsAdminController extends Controller
{
    private $db;
    private $assignmentsModel;

    public function __construct()
    {
        require_once CONFIG . 'DatabaseConnection.php';
        $db_instance = DatabaseConnection::getInstance();
        $conn = $db_instance->getConnection();
        $this->db = $conn;
        $this->assignmentsModel = new Assignments($this->db);
    }

    public function index()
    {
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 1)) {

            $assignments = $this->assignmentsModel->getAssignments();
            $this->view('manageAssignmentsAdmin', ['assignments' => $assignments]);
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }

    public function addAssignment()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $title = $_POST['assignment-title'];
            $description = $_POST['assignment-description'];
            $dueDate = $_POST['due-date'];
            $groupID = $_POST['groupID'];

            if ($this->assignmentsModel->addAssignment($title, $description, $dueDate,  $groupID)) {
                echo "<script>
                window.location.href = '" . redirect('manageAssignmentsAdmin') . "';
                </script>";
            } else {
                $assignments = $this->assignmentsModel->getAssignments();
                $data['assignments'] = $assignments;
                $data['insertError'] = true;
                $this->view('manageAssignmentsAdmin', $data);
            }
        }
    }

    public function editAssignment($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = $_POST['assignment-title'];
            $description = $_POST['assignment-description'];
            $dueDate = $_POST['due-date'];
            $groupID = $_POST['groupID'];


            if ($this->assignmentsModel->editAssignment($id, $title, $description, $dueDate, $groupID)) {
                echo "<script>
                    window.location.href = '" . redirect('manageAssignmentsAdmin') . "';
                    </script>";
            } else {
                $assignments = $this->assignmentsModel->getAssignments();
                $data['assignments'] = $assignments;
                $data['insertError'] = true;
                $this->view('manageAssignmentsAdmin', $data);
            }
        } else {
            $assignment = $this->assignmentsModel->getAssignmentById($id);
            $this->view('editAssignment', ['assignment' => $assignment]);
        }
    }

    public function deleteAssignment($id)
    {
        if ($this->assignmentsModel->deleteAssignment($id)) {
            echo "<script>
                window.location.href = '" . redirect('manageAssignmentsAdmin') . "';
                </script>";
        } else {
            $assignments = $this->assignmentsModel->getAssignments();
            $data['assignments'] = $assignments;
            $data['insertError'] = true;
            $this->view('manageAssignmentsAdmin', $data);
        }
    }
}
