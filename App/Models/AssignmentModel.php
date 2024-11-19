<?php

class AssignmentObject {
    private $conn;
    public $userID;
    public $ID;
    public $Title;
    public $Description;
    public $StartDate;
    public $DueDate;
    public $groupID;

    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->userID = $userID;
    }

    public function set($field, $value) {
        $query = "UPDATE assignments SET $field = ? WHERE ID = ? AND userID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $value, $this->ID, $this->userID);
        $stmt->execute();
        $stmt->close();
        $this->$field = $value;
    }

    public function get($field, $type = 'string') {
        $query = "SELECT $field FROM assignments WHERE ID = ? AND userID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $this->ID, $this->userID);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        settype($result, $type);
        return $result;
    }
    
    public function returnAsJson() {
        return json_encode([
            'ID' => $this->ID,
            'Title' => $this->Title,
            'Description' => $this->Description,
            'StartDate' => $this->StartDate,
            'DueDate' => $this->DueDate,
            'userID' => $this->userID,
            'groupID' => $this->groupID
        ]);
    }
}
class AssignmentsFetcher {
    private $conn;
    private $userID;
    public $assignments = [];

    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->userID = $userID;
    }

    public function fetchAll() {
        $query = "SELECT * FROM assignments WHERE userID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->userID);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $assignment = new AssignmentObject($this->conn, $this->userID);
            foreach ($row as $key => $value) {
                $assignment->$key = $value;
            }
            $this->assignments[] = $assignment;
        }
        $stmt->close();
        return $this->assignments;
    }
}
?>

