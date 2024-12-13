<?php
require_once MODELS . 'Groups.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ManageGroupInsturctorController extends Controller
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

        if (($id !== null) && ($userType == 2)) {

            $this->view('ManageGroupInsturctor');
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }

    public function getGroups()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $errors = [];

            if (empty($_GET['userID'])) {
                $errors['userIDError'] = "User ID is required.";
            } elseif (!is_numeric($_GET['userID'])) {
                $errors['userIDError'] = "User ID must be numeric.";
            }

            if (!empty($errors)) {
                echo json_encode(['errors' => $errors]);
                exit;
            }

            $userID = intval($_GET['userID']);
            $dataFetcher = new Groups($this->db);
            try {

                echo $dataFetcher->getGroupsAsJson();
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    public function getMembers()
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $errors = [];

            if (empty($_GET['userID']) || empty($_GET['groupID'])) {
                $errors['userIDError'] = "User ID is required.";
                $errors['groupIDError'] = "Group ID is required.";
            } elseif (!is_numeric($_GET['userID'])) {
                $errors['userIDError'] = "User ID must be numeric.";
                $errors['groupIDError'] = "Group ID must be numeric.";
            }

            if (!empty($errors)) {
                echo json_encode(['errors' => $errors]);
                exit;
            }

            $userID = intval($_GET['userID']);
            $groupID = intval($_GET['groupID']);
            $dataFetcher = new Groups($this->db);
            try {

                echo $dataFetcher->getGroupMembersAsJson($groupID);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
