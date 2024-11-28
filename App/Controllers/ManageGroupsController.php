<?php
require_once MODELS . 'Groups.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ManageGroupsController extends Controller
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

        if (($id !== null) && ($userType == 1)) {
            $groupsModel = new Groups($this->db);

            $groupID = 1;
            $students = $groupsModel->getStudentsByGroup($groupID);
            $instructors = $groupsModel->getInstructorsByGroup($groupID);
            $availableGroups = $groupsModel->getAvailableGroups();

            $data = [
                'students' => $students,
                'instructors' => $instructors,
                'defaultGroupID' => $groupID,
                'availableGroups' => $availableGroups,
            ];

            $this->view('manageGroups', $data);
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }

    public function getAvailableGroups()
    {
        $groupsModel = new Groups($this->db);
        $groups = $groupsModel->getAvailableGroups();

        header('Content-Type: application/json');
        echo json_encode($groups);
    }

    public function getStudentsByGroup($groupID)
    {
        if (!is_numeric($groupID)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid group ID']);
            return;
        }

        $groupsModel = new Groups($this->db);
        $users = $groupsModel->getStudentsByGroup((int)$groupID);

        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function getInstructorsByGroup($groupID)
    {
        if (!is_numeric($groupID)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid group ID']);
            return;
        }

        $groupsModel = new Groups($this->db);
        $instructors = $groupsModel->getInstructorsByGroup((int)$groupID);

        header('Content-Type: application/json');
        echo json_encode($instructors);
    }

    public function deleteStudentFromGroup()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $studentID = $data['studentID'];
        $groupID = $data['groupID'];
        if (!is_numeric($studentID) || !is_numeric($groupID)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid student or group ID']);
            return;
        }

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
        $data = json_decode(file_get_contents("php://input"), true);
        $instructorID = $data['instructorID'];
        $groupID = $data['groupID'];
        if (!is_numeric($instructorID) || !is_numeric($groupID)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid instructor or group ID']);
            return;
        }

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
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $data = json_decode(file_get_contents("php://input"), true);
        $studentID = $data['studentID'] ?? null;
        $groupID = $data['groupID'] ?? null;
        if (!is_numeric($studentID) || !is_numeric($groupID)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid student or group ID']);
            return;
        }

        $userTypeQuery = "SELECT userType_id FROM users WHERE ID = ?";
        $userTypeStmt = $this->db->prepare($userTypeQuery);
        if (!$userTypeStmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare user type query']);
            return;
        }

        $userTypeStmt->bind_param("i", $studentID);
        if (!$userTypeStmt->execute()) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to execute user type query']);
            return;
        }

        $userTypeResult = $userTypeStmt->get_result();
        if ($userTypeResult->num_rows === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Student not found']);
            return;
        }

        $user = $userTypeResult->fetch_assoc();
        if ($user['userType_id'] != 3) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'User is not a valid student']);
            return;
        }

        $groupQuery = "SELECT 1 FROM groups WHERE ID = ?";
        $groupStmt = $this->db->prepare($groupQuery);
        if (!$groupStmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare group check query']);
            return;
        }

        $groupStmt->bind_param("i", $groupID);
        if (!$groupStmt->execute()) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to execute group check query']);
            return;
        }

        $groupResult = $groupStmt->get_result();
        if ($groupResult->num_rows === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Group ID does not exist']);
            return;
        }

        $checkQuery = "SELECT 1 FROM user_groups WHERE userID = ? AND groupID = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        if (!$checkStmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare check query']);
            return;
        }

        $checkStmt->bind_param("ii", $studentID, $groupID);
        if (!$checkStmt->execute()) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to execute check query']);
            return;
        }

        $checkStmt->store_result();
        if ($checkStmt->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Student already exists in this group']);
            return;
        }

        $insertQuery = "INSERT INTO user_groups (userID, groupID) VALUES (?, ?)";
        $insertStmt = $this->db->prepare($insertQuery);
        if (!$insertStmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare insert query']);
            return;
        }

        $insertStmt->bind_param("ii", $studentID, $groupID);
        if ($insertStmt->execute()) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Student added successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add student']);
        }

        $userTypeStmt->close();
        $groupStmt->close();
        $checkStmt->close();
        $insertStmt->close();
    }

    public function addInstructorToGroup()
    {

        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $data = json_decode(file_get_contents("php://input"), true);
        $instructorID = $data['instructorID'] ?? null;
        $groupID = $data['groupID'] ?? null;

        if (!is_numeric($instructorID) || !is_numeric($groupID)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid instructor or group ID']);
            return;
        }

        $userTypeQuery = "SELECT userType_id FROM users WHERE ID = ?";
        $userTypeStmt = $this->db->prepare($userTypeQuery);
        if (!$userTypeStmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare user type query']);
            return;
        }

        $userTypeStmt->bind_param("i", $instructorID);
        if (!$userTypeStmt->execute()) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to execute user type query']);
            return;
        }

        $userTypeResult = $userTypeStmt->get_result();
        if ($userTypeResult->num_rows === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Instructor not found']);
            return;
        }

        $user = $userTypeResult->fetch_assoc();
        if ($user['userType_id'] != 2) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'User is not a valid instructor']);
            return;
        }

        $groupQuery = "SELECT 1 FROM groups WHERE ID = ?";
        $groupStmt = $this->db->prepare($groupQuery);
        if (!$groupStmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare group check query']);
            return;
        }

        $groupStmt->bind_param("i", $groupID);
        if (!$groupStmt->execute()) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to execute group check query']);
            return;
        }

        $groupResult = $groupStmt->get_result();
        if ($groupResult->num_rows === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Group ID does not exist']);
            return;
        }

        $checkQuery = "SELECT 1 FROM user_groups WHERE userID = ? AND groupID = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        if (!$checkStmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare check query']);
            return;
        }

        $checkStmt->bind_param("ii", $instructorID, $groupID);
        if (!$checkStmt->execute()) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to execute check query']);
            return;
        }

        $checkStmt->store_result();
        if ($checkStmt->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Instructor already exists in this group']);
            return;
        }

        $insertQuery = "INSERT INTO user_groups (userID, groupID) VALUES (?, ?)";
        $insertStmt = $this->db->prepare($insertQuery);
        if (!$insertStmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare insert query']);
            return;
        }

        $insertStmt->bind_param("ii", $instructorID, $groupID);
        if ($insertStmt->execute()) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Instructor added successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add instructor']);
        }

        $userTypeStmt->close();
        $groupStmt->close();
        $checkStmt->close();
        $insertStmt->close();
    }
}
