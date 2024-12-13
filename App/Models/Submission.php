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
    public $assignmentID;
    public $submissionDate;
    public $status;
    public $assignment;
    private $studentName;
    public $submissions = [];

    public function __construct($db)
    {
        $this->db = $db;
        $this->UserID = isset($_SESSION['user']['ID']) && !is_null($_SESSION['user']['ID']) ? $_SESSION['user']['ID'] : null;
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



    public function returnAsJson()
    {
        $firstName = null;
        $lastName = null;
        $query = "SELECT FirstName, LastName FROM users WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->UserID);
        $stmt->execute();
        $stmt->bind_result($firstName, $lastName);
        $stmt->fetch();
        $stmt->close();

        $this->studentName = $firstName . ' ' . $lastName;
        $this->assignment = $this->fetchAssignment();
        return json_encode([
            'ID' => $this->ID,
            'assignmentID' => $this->assignmentID,
            'userID' => $this->UserID,
            'submissionDate' => $this->submissionDate,
            'status' => $this->status,
            'studentName' => $this->studentName,
            'assignment' => json_decode($this->assignment->returnAsJson())
        ]);
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
}
