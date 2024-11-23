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
    
        $groupID = 1;
        $students = $groupsModel->getStudentsByGroup($groupID);
        $instructors = $groupsModel->getInstructorsByGroup($groupID);
        $availableGroups = $groupsModel->getAvailableGroups(); // Fetch all available groups
    
        // Pass data to the view
        $data = [
            'students' => $students,
            'instructors' => $instructors,
            'defaultGroupID' => $groupID,
            'availableGroups' => $availableGroups, // Add groups to the data
        ];
    
        $this->view('manageGroups', $data);
    }

    public function getAvailableGroups()
    {
        $groupsModel = new Groups($this->db);

        // Fetch available groups
        $groups = $groupsModel->getAvailableGroups();

        // Return data as JSON for AJAX requests
        header('Content-Type: application/json');
        echo json_encode($groups);
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



    public function getInstructorsByGroup($groupID)
{
    if (!is_numeric($groupID)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid group ID']);
        return;
    }

    $groupsModel = new Groups($this->db);

    // Fetch instructors for the selected group
    $instructors = $groupsModel->getInstructorsByGroup((int)$groupID);

    // Return data as JSON for AJAX requests
    header('Content-Type: application/json');
    echo json_encode($instructors);
}


public function deleteStudentFromGroup()
{
    // Get the data sent in the request
    $data = json_decode(file_get_contents("php://input"), true);
    $studentID = $data['studentID'];
    $groupID = $data['groupID'];

    if (!is_numeric($studentID) || !is_numeric($groupID)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid student or group ID']);
        return;
    }

    // Prepare and execute the query to remove the student from the group
    $query = "DELETE FROM user_groups WHERE userID = ? AND groupID = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("ii", $studentID, $groupID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}

public function deleteInstructorFromGroup()
{
    // Get the data sent in the request
    $data = json_decode(file_get_contents("php://input"), true);
    $instructorID = $data['instructorID'];
    $groupID = $data['groupID'];

    // Validate the instructorID and groupID
    if (!is_numeric($instructorID) || !is_numeric($groupID)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid instructor or group ID']);
        return;
    }

    // Prepare and execute the query to remove the instructor from the group
    $query = "DELETE FROM user_groups WHERE userID = ? AND groupID = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("ii", $instructorID, $groupID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}

public function addStudentToGroup()
{
    // Ensure errors are reported
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Get the data sent in the request
    $data = json_decode(file_get_contents("php://input"), true);
    $studentID = $data['studentID'] ?? null;
    $groupID = $data['groupID'] ?? null;

    // Validate the inputs
    if (!is_numeric($studentID) || !is_numeric($groupID)) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid student or group ID']);
        return;
    }

    // Check if the student is already in the group
    $checkQuery = "SELECT 1 FROM user_groups WHERE userID = ? AND groupID = ?";
    $checkStmt = $this->db->prepare($checkQuery);

    if (!$checkStmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Failed to prepare check query']);
        return;
    }

    $checkStmt->bind_param("ii", $studentID, $groupID);

    if (!$checkStmt->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Failed to execute check query']);
        return;
    }

    $checkStmt->store_result(); // Store the result
    if ($checkStmt->num_rows > 0) {
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'message' => 'Student already exists in this group']);
        return;
    }

    // Insert the student into the group
    $insertQuery = "INSERT INTO user_groups (userID, groupID) VALUES (?, ?)";
    $insertStmt = $this->db->prepare($insertQuery);

    if (!$insertStmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Failed to prepare insert query']);
        return;
    }

    $insertStmt->bind_param("ii", $studentID, $groupID);

    if ($insertStmt->execute()) {
        http_response_code(200); // Success
        echo json_encode(['success' => true, 'message' => 'Student added successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Failed to add student']);
    }

    $insertStmt->close();
    $checkStmt->close();
}

// Assuming you have an instance of Groups model called $groupsModel

public function addInstructorToGroup()
{
    // Get the data sent in the request
    $data = json_decode(file_get_contents("php://input"), true);
    $instructorID = $data['instructorID'] ?? null;
    $groupID = $data['groupID'] ?? null;

    // Validate the inputs
    if (!isset($instructorID) || !isset($groupID) || !is_numeric($instructorID) || !is_numeric($groupID)) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid instructor or group ID']);
        return;
    }

    // Prepare the SQL query to insert the instructor into the group
    $sql = "INSERT INTO user_groups (userID, groupID, userTypeID) VALUES (:instructor_id, :group_id, 2)"; // 2 for instructor

    // Prepare the statement
    $stmt = $this->db->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':instructor_id', $instructorID);
    $stmt->bindParam(':group_id', $groupID);

    // Execute and return the result (true on success, false otherwise)
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Instructor added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add instructor']);
    }
}
}
