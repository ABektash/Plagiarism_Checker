<?php
require_once 'Assignments.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Submission
{
    private $db;
    public $UserID;
    public $ID;
    public $userFullName;
    public $assignmentID;
    public $submissionDate;
    public $status;
    public $assignment;
    private $studentID;
    private $studentName;
    public $submissions = [];

    public function __construct($db, $userId = null)
    {
        $this->db = $db;
        $this->UserID = $userId ?? ($_SESSION['user']['ID'] ?? null);
    }
    public function getAllSubmissions()
    {
        $query = "
        SELECT 
            submissions.ID AS submissionID,
            submissions.assignmentID,
            submissions.userID AS studentID,
            submissions.submissionDate,
            submissions.status,
            assignments.Title AS assignmentTitle
        FROM submissions
        JOIN assignments ON submissions.assignmentID = assignments.ID
    ";

        $result = $this->db->query($query);

        if (!$result) {
            die("Query failed: " . $this->db->error);
        }

        $submissions = [];
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $row;
        }

        return $submissions;
    }
    public function getSubmissionsByUserId($userID)
    {
        $userID = (int)$userID;

        $query = "
        SELECT 
            submissions.ID AS submissionID,
            submissions.assignmentID,
            submissions.userID AS studentID,
            submissions.submissionDate,
            submissions.status,
            assignments.Title AS assignmentTitle
        FROM submissions
        JOIN assignments ON submissions.assignmentID = assignments.ID
        WHERE submissions.userID = $userID
    ";

        $result = $this->db->query($query);

        if (!$result) {
            die("Query failed: " . $this->db->error);
        }

        $submissions = [];
        while ($row = $result->fetch_assoc()) {
            $submissions[] = $row;
        }

        return $submissions;
    }
    function getAssignmentsByUserID($userID)
    {
        $userID = intval($userID);

        $query = "
            SELECT 
                ID, 
                Title, 
                Description, 
                StartDate, 
                DueDate, 
                groupID 
            FROM 
                assignments 
            WHERE 
                userID = $userID
        ";

        $result = $this->db->query($query);

        if (!$result) {
            die("Query failed: " . $this->db->error);
        }

        $assignments = [];
        while ($row = $result->fetch_assoc()) {
            $assignments[] = $row;
        }

        $result->free();
        return $assignments;
    }
    public function deleteSubmission($submissionID)
    {
        $userID = intval($submissionID);

        $query = "DELETE FROM submissions WHERE ID = $submissionID";

        return mysqli_query($this->db, $query);
    }


    public function set($field, $value)
    {
        $query = "UPDATE submissions SET $field = ? WHERE ID = ? AND userID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $value, $this->ID, $this->UserID);
        $stmt->execute();
        $stmt->close();
        $this->$field = $value;
    }

    public function get($field, $type = 'string')
    {

        $result = null;
        $query = "SELECT $field FROM submissions WHERE ID = ? AND userID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $this->ID, $this->UserID);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        settype($result, $type);
        return $result;
    }


    private function fetchAssignment()
    {
        $query = "SELECT * FROM assignments WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->assignmentID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $assignment = new Assignments($this->db);
            foreach ($row as $key => $value) {
                $assignment->$key = $value;
            }
        }
        $stmt->close();
        return isset($assignment) ? $assignment : null;
    }

    public $userID;
    public function returnAsJson()
    {
        try {
            $firstName = '';
            $lastName = '';
            $this->studentName = 'Unknown';
            $this->assignment = null;
            
            $query = "SELECT FirstName, LastName FROM users WHERE ID = ?";
            $stmt = $this->db->prepare($query);
    
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $this->db->error);
            }
    
            $stmt->bind_param("i", $this->userID);
            $stmt->execute();
            $stmt->bind_result($firstName, $lastName);
            $stmt->fetch();
            $stmt->close();
    
            $this->studentName = trim($firstName . ' ' . $lastName);
    
            if (empty($this->studentName)) {
                $this->studentName = 'Unknown';
            }
    
            $this->assignment = $this->fetchAssignment();
    
            return json_encode([
                'ID' => $this->ID ?? null,
                'assignmentID' => $this->assignmentID ?? null,
                'userID' => $this->userID ?? null,
                'submissionDate' => $this->submissionDate ?? null,
                'status' => $this->status ?? null,
                'studentName' => $this->studentName,
                'assignment' => $this->assignment ? json_decode($this->assignment->returnAsJson()) : null,
            ]);
        } catch (Exception $e) {
            error_log("Error in returnAsJson: " . $e->getMessage());
            return json_encode([
                'error' => true,
                'message' => "Failed to process submission details.",
            ]);
        }
    }
    

    public function fetchAll()
    { ##MAYBE ERROR
        $query = "SELECT * FROM submissions WHERE userID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->UserID);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $submission = new Submission($this->db);
            foreach ($row as $key => $value) {
                $submission->$key = $value;
            }
            $this->submissions[] = $submission;
        }
        $stmt->close();
        return $this->submissions;
    }


    public function updateStatus($submissionID, $status)
    {
        $submissionID = mysqli_real_escape_string($this->db, $submissionID);
        $status = mysqli_real_escape_string($this->db, $status);

        $sql = "UPDATE submissions SET status = '$status' WHERE ID = '$submissionID'";

        $result = mysqli_query($this->db, $sql);

        if ($result) {
            return true;
        } else {
            error_log('Database Update Failed: ' . mysqli_error($this->db));
            return false;
        }
    }

    public function createSubmission($assignmentID, $userID, $submissionData)
    {
        $assignmentID = mysqli_real_escape_string($this->db, $assignmentID);
        $userID = mysqli_real_escape_string($this->db, $userID);
        $submissionData = mysqli_real_escape_string($this->db, $submissionData);

        $sql = "INSERT INTO submissions (assignmentID, userID, submissionData, status) 
                VALUES ('$assignmentID', '$userID', '$submissionData', 'Pending')";

        $result = mysqli_query($this->db, $sql);

        if ($result) {
            return mysqli_insert_id($this->db);
        } else {
            error_log('Database Insertion Failed: ' . mysqli_error($this->db));
            return false;
        }
    }

    public function alreadySubmitted($userId, $assignmentId)
    {
        $userId = (int)$userId;
        $assignmentId = (int)$assignmentId;

        $query = "SELECT 1 FROM submissions WHERE userID = $userId AND assignmentID = $assignmentId LIMIT 1";

        $result = mysqli_query($this->db, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return true;
        }

        return false;
    }
}
