<?php
require_once 'User.php';
require_once 'UserTypePage.php';
require_once 'Assignments.php';
require_once 'Submission.php';
require_once 'PlagiarismReport.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Groups
{
    private $db;
    public $groupID;
    private $UserID;

    public function __construct($db)
    {
        $this->db = $db;
        $this->UserID = isset($_SESSION['user']['ID']) && !is_null($_SESSION['user']['ID']) ? $_SESSION['user']['ID'] : null;
    }

    public function getAvailableGroups()
    {
        $query = "
            SELECT 
                ID AS group_id
            FROM 
                groups
            ORDER BY 
                ID";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $groups = [];
        while ($row = $result->fetch_assoc()) {
            // Return only group_id as you already are
            $groups[] = ['group_id' => $row['group_id']];
        }

        return $groups;  // Return the groups array
    }
    public function getStudentsByGroup($groupID)
    {
        $query = "
            SELECT 
                users.ID AS student_id,
                CONCAT(users.FirstName, ' ', users.LastName) AS student_name,
                users.Email AS student_email
            FROM 
                user_groups
            INNER JOIN 
                users ON user_groups.userID = users.ID
            WHERE 
                user_groups.groupID = ? AND users.UserType_id = 3
            ORDER BY 
                student_name";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $groupID);
        $stmt->execute();
        $result = $stmt->get_result();

        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        return $students;
    }
    public function getInstructorsByGroup($groupID)
    {
        $query = "
        SELECT 
            users.ID AS inst_id,
            CONCAT(users.FirstName, ' ', users.LastName) AS inst_name
        FROM 
            user_groups
        INNER JOIN 
            users ON user_groups.userID = users.ID
        WHERE 
            user_groups.groupID = ? AND users.UserType_id = 2
        ORDER BY 
            inst_name";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $groupID);
        $stmt->execute();
        $result = $stmt->get_result();

        $instructors = [];
        while ($row = $result->fetch_assoc()) {
            $instructors[] = $row;
        }

        return $instructors;
    }
    public function addStudentToGroup($studentID, $groupID)
    {
        $sql = "INSERT INTO user_groups (student_id, group_id) VALUES (:student_id, :group_id)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':student_id', $studentID);
        $stmt->bindParam(':group_id', $groupID);

        return $stmt->execute(); // Returns true on success, false otherwise
    }
    public function addInstructorToGroup($instructorID, $groupID)
    {
        // Prepare the SQL query to insert the instructor into the group
        $sql = "INSERT INTO user_groups (userID, groupID) VALUES (:instructor_id, :group_id)";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':instructor_id', $instructorID);
        $stmt->bindParam(':group_id', $groupID);

        // Execute and return the result (true on success, false otherwise)
        return $stmt->execute();
    }
    public function createGroup()
    {
        try {
            // Insert a new row; only the auto-increment ID will be created
            $query = "INSERT INTO groups () VALUES ()";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            // Return the ID of the newly created group
            return $this->db->insert_id;  // For MySQLi
        } catch (Exception $e) {
            // Log the error for debugging
            error_log("Error creating group: " . $e->getMessage());
            return false;
        }
    }
    public function deleteGroup($groupID)
    {
        try {
            // Delete associated users from the user_groups table
            $query = "DELETE FROM user_groups WHERE groupID = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $groupID);
            $stmt->execute();

            // Delete the group from the groups table
            $query = "DELETE FROM groups WHERE ID = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $groupID);
            $stmt->execute();

            return true; // Return true if the group and users were deleted successfully
        } catch (Exception $e) {
            // Log the error
            error_log("Error deleting group: " . $e->getMessage());
            return false; // Return false if there was an error
        }
    }
    public function getUserGroupCountByUserID($userID)
    {
        $userID = intval($userID);

        $query = "SELECT COUNT(*) AS count FROM user_groups WHERE userID = $userID";
        $result = mysqli_query($this->db, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['count'];
        } else {
            return "Error: " . mysqli_error($this->db);
        }
    }

    public function getAllAssignments()
    {
        $query = "
            SELECT a.* 
            FROM assignments a
            INNER JOIN user_groups ug ON a.groupID = ug.groupID
            WHERE ug.userID = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->UserID);
        $stmt->execute();
        $result = $stmt->get_result();

        $assignments = [];
        while ($row = $result->fetch_assoc()) {
            $assignment = new Assignments($this->db);
            foreach ($row as $key => $value) {
                $assignment->$key = $value;
            }
            $assignments[] = $assignment;
        }

        $stmt->close();
        return $assignments;
    }
    public function getAllSubmissions($assignmentIDs = [])
    {
        if (empty($assignmentIDs)) {
            return [];
        }


        $placeholders = implode(',', array_fill(0, count($assignmentIDs), '?'));
        $query = "
        SELECT s.* 
        FROM submissions s
        WHERE s.assignmentID IN ($placeholders)
    ";

        $stmt = $this->db->prepare($query);

        $types = str_repeat('i', count($assignmentIDs));
        $stmt->bind_param($types, ...$assignmentIDs);
        $stmt->execute();
        $result = $stmt->get_result();

        $submissions = [];
        while ($row = $result->fetch_assoc()) {
            $submission = new Submission($this->db);
            foreach ($row as $key => $value) {
                $submission->$key = $value;
            }
            $submissions[] = $submission;
        }

        $stmt->close();
        return $submissions;
    }


    public function getAllPlagiarismReports($assignmentIDs = [])
    {
        $submissions = $this->getAllSubmissions($assignmentIDs);
        $submissionIDs = array_map(function ($submission) {
            return $submission->ID;
        }, $submissions);

        if (empty($submissionIDs)) {
            return [];
        }

        $query = "
            SELECT pr.* 
            FROM plagiarism_reports pr
            WHERE pr.submissionID IN (" . implode(',', array_fill(0, count($submissionIDs), '?')) . ")
        ";

        $stmt = $this->db->prepare($query);
        $types = str_repeat('i', count($submissionIDs));
        $stmt->bind_param($types, ...$submissionIDs);
        $stmt->execute();
        $result = $stmt->get_result();

        $reports = [];
        while ($row = $result->fetch_assoc()) {
            $report = new PlagiarismReport($this->db, $row['submissionID'], $this->UserID);
            foreach ($row as $key => $value) {
                $report->$key = $value;
            }
            $reports[] = $report;
        }

        $stmt->close();
        return $reports;
    }
    public function getGroupsAndCountAsJson()
    {
        $userId = $this->UserID;
        $groupId = null;
        $memberCount = null;
        $submissionCount = null;

        $sql = "
        SELECT 
            g.ID AS GroupID, 
            COUNT(DISTINCT ug.userID) AS MemberCount,
            COUNT(DISTINCT s.ID) AS SubmissionCount
        FROM 
            `groups` g
        INNER JOIN 
            user_groups ug ON g.ID = ug.groupID
        INNER JOIN 
            users u ON ug.userID = u.ID AND u.UserType_id = 3
        LEFT JOIN 
            assignments a ON g.ID = a.groupID
        LEFT JOIN 
            submissions s ON a.ID = s.assignmentID
        WHERE 
            g.ID IN (
                SELECT 
                    groupID
                FROM 
                    user_groups
                WHERE 
                    userID = ?
            )
        GROUP BY 
            g.ID
    ";

        try {
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                return json_encode(['error' => $this->db->error]);
            }

            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->bind_result($groupId, $memberCount, $submissionCount);

            $groups = [];
            while ($stmt->fetch()) {
                $groups[] = [
                    'GroupID' => $groupId,
                    'MemberCount' => $memberCount,
                    'SubmissionCount' => $submissionCount
                ];
            }
            $stmt->close();

            return json_encode($groups);
        } catch (Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }
    public function getGroupMembersAsJson($groupId)
    {
        $this->db;
        $this->UserID;

        $sql = "
        SELECT u.ID AS UserID, u.FirstName, u.LastName, u.Email
        FROM users u
        INNER JOIN user_groups ug ON u.ID = ug.userID
        WHERE ug.groupID = ? AND u.ID != ?
    ";

        if ($stmt = $this->db->prepare($sql)) {

            $stmt->bind_param("ii", $groupId, $this->UserID);
            $stmt->execute();
            $result = $stmt->get_result();
            $groupMembers = [];

            while ($row = $result->fetch_assoc()) {
                $groupMembers[] = [
                    'UserID' => $row['UserID'],
                    'FirstName' => $row['FirstName'],
                    'LastName' => $row['LastName'],
                    'Email' => $row['Email']
                ];
            }
            $result->free();
            $stmt->close();
            return json_encode($groupMembers);
        } else {
            return json_encode(['error' => 'Failed to prepare the query']);
        }
    }
    public function getGroupsAsJson()
    {
        $userID = $this->UserID;
        $query = "SELECT groupID FROM user_groups WHERE userID = ?";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();
            $groupIDs = [];
            while ($row = $result->fetch_assoc()) {
                $groupIDs[] = $row['groupID'];
            }

            $result->free();
            $stmt->close();

            return json_encode($groupIDs);
        } else {

            return json_encode(["error" => "Failed to prepare the SQL statement"]);
        }
    }
    public function returnAsJson()
    {
        $assignments = $this->getAllAssignments();
        $assignmentIDs = array_map(function ($assignment) {
            return $assignment->ID;
        }, $assignments);

        $submissions = $this->getAllSubmissions($assignmentIDs);
        $reports = $this->getAllPlagiarismReports($assignmentIDs);

        $result = [
            'assignments' => array_map(function ($assignment) {
                return json_decode($assignment->returnAsJson());
            }, $assignments),
            'submissions' => array_map(function ($submission) {
                return json_decode($submission->returnAsJson());
            }, $submissions),
            'plagiarism_reports' => array_map(function ($report) {
                return json_decode($report->returnAsJson());
            }, $reports),
        ];

        return json_encode($result);
    }
}
