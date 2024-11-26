<?php 
require_once MODELS .'GroupsModel.php';
class ManageGroupInsturctorController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG.'dbh.inc.php';
        $this->db = $conn; 
    }
    public function index()
    {
        $this->view('ManageGroupInsturctor');
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
            $dataFetcher= new GroupsModel($this->db,$userID);
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
        
            if (empty($_GET['userID'])||empty($_GET['groupID'])) {
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
            $dataFetcher= new GroupsModel($this->db,$userID);
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