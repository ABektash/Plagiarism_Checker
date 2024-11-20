<?php 

require_once MODELS . 'Groups.php';

class ManageGroupsController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    /**
     * Default method to load the manageGroups view with initial data.
     */
    public function index()
    {
        $groupsModel = new Groups($this->db);

        // Fetch initial group data (default group ID = 1)
        $groupID = 1;
        $users = $groupsModel->getStudentsByGroup($groupID);

        // Pass data to the view
        $data = [
            'users' => $users,
            'defaultGroupID' => $groupID,
        ];

        $this->view('manageGroups', $data);
    }

    /**
     * Method to handle fetching students by group ID dynamically.
     */
    public function getStudentsByGroup($groupID)
    {
        if (!is_numeric($groupID)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid group ID']);
            return;
        }

        $groupsModel = new Groups($this->db);

        // Fetch students for the selected group
        $users = $groupsModel->getStudentsByGroup((int)$groupID);

        // Return data as JSON for AJAX requests
        header('Content-Type: application/json');
        echo json_encode($users);
    }
}
