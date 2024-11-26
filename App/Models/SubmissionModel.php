<?php
require_once 'AssignmentModel.php';
class SubmissionObject {
    private $conn;
    public $userID;
    public $ID;
    public $assignmentID;
    public $submissionDate;
    public $status;
    public $assignment; 
    private $studentName;
    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->userID = $userID;
    }

    public function set($field, $value) {
        $query = "UPDATE submissions SET $field = ? WHERE ID = ? AND userID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $value, $this->ID, $this->userID);
        $stmt->execute();
        $stmt->close();
        $this->$field = $value;
    }

    public function get($field, $type = 'string') {
        $query = "SELECT $field FROM submissions WHERE ID = ? AND userID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $this->ID, $this->userID);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        settype($result, $type);
        return $result;
    }

    
    private function fetchAssignment() {
        $query = "SELECT * FROM assignments WHERE ID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->assignmentID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $assignment = new AssignmentObject($this->conn, $this->userID);
            foreach ($row as $key => $value) {
                $assignment->$key = $value;
            }
        }
        $stmt->close();
        return isset($assignment) ? $assignment : null;
    }
    

    
    public function returnAsJson() {
        $query = "SELECT FirstName, LastName FROM users WHERE ID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->userID);
        $stmt->execute();
        $stmt->bind_result($firstName, $lastName);
        $stmt->fetch();
        $stmt->close();

        $this->studentName = $firstName . ' ' . $lastName;
        $this->assignment = $this->fetchAssignment();
        return json_encode([
            'ID' => $this->ID,
            'assignmentID' => $this->assignmentID,
            'userID' => $this->userID,
            'submissionDate' => $this->submissionDate,
            'status' => $this->status,
            'studentName' => $this->studentName,
            'assignment' => json_decode($this->assignment->returnAsJson())
        ]);
    }
}
class SubmissionFetcher {
    private $conn;
    private $userID;
    public $submissions = [];

    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->userID = $userID;
    }

    public function fetchAll() {
        $query = "SELECT * FROM submissions WHERE userID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->userID);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $submission = new SubmissionObject($this->conn, $this->userID);
            foreach ($row as $key => $value) {
                $submission->$key = $value;
            }
            $this->submissions[] = $submission;
        }
        $stmt->close();
        return $this->submissions;
    }
}
?>
