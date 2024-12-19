<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'AssignmentSubject.php';

class Assignments implements AssignmentSubject
{
    private $conn;
    public $UserID;
    public $ID;
    public $Title;
    public $Description;
    public $StartDate;
    public $DueDate;
    public $groupID;
    public $assignments = [];
    private array $observers = [];

    public function __construct($db, $userId = null)
    {
        $this->conn = $db;
        $this->UserID = $userId ?? ($_SESSION['user']['ID'] ?? null);
    }
    public function getAssignments()
    {
        $query = "SELECT ID, Title, Description, groupID, DueDate FROM assignments";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die('MySQL prepare error: ' . $this->conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die('MySQL execute error: ' . $stmt->error);
        }

        $assignments = [];
        while ($row = $result->fetch_assoc()) {
            $assignments[] = $row;
        }

        return $assignments;
    }
    public function getAssignmentById($id)
    {
        $query = "SELECT ID, Title, Description, DueDate, groupID FROM assignments WHERE ID = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Failed to prepare statement: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $assignment = $result->fetch_assoc();

        if (!$assignment) {
            error_log("No assignment found for ID: " . $id);
        }

        return $assignment;
    }
    public function addAssignment($title, $description, $dueDate, $groupID)
    {
        if (!isset($_SESSION["user"]["ID"])) {
            throw new Exception("User not logged in.");
        }

        $user_ID = $_SESSION["user"]["ID"];
        $query = "INSERT INTO assignments (userID, Title, Description, DueDate, groupID) 
              VALUES ('$user_ID', '$title', '$description', '$dueDate', '$groupID')";
        try {
            $result = $this->conn->query($query);

            if ($result) {
                $usersQuery = "SELECT u.ID, u.FirstName, u.LastName, u.Email 
                FROM users u
                INNER JOIN user_groups ug ON u.ID = ug.userID
                WHERE ug.groupID = '$groupID'";

                $result = $this->conn->query($usersQuery);

                if ($result) {
                    while ($userData = $result->fetch_assoc()) {
                        $user = new User($this->conn);
                        $user->email = $userData['Email'];
                        $this->addObserver($user);
                    }
                }

                $message = "A new assignment '{$title}' has been uploaded. Due Date: {$dueDate}.";

                $this->notifyObservers($message);

                $this->observers = [];

                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error adding assignment: " . $e->getMessage());
            return false;
        }
    }
    public function editAssignment($id, $title, $description, $dueDate, $groupID)
    {
        $query = "UPDATE assignments 
              SET Title = ?, Description = ?, DueDate = ?, groupID = ? 
              WHERE ID = ?";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            error_log("Failed to prepare statement: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("ssssi", $title, $description, $dueDate, $groupID, $id);

        try {
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log("Error editing assignment: " . $e->getMessage());
            return false;
        }
    }
    public function deleteAssignment($id)
    {
        $id = intval($id);

        $query = "DELETE FROM assignments WHERE ID = $id";

        try {
            return $this->conn->query($query);
        } catch (PDOException $e) {
            error_log("Error deleting assignment: " . $e->getMessage());
            return false;
        }
    }
    public function getGroups()
    {
        $query = "SELECT ID, Name FROM groups";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get($field, $type = 'string')
    {
        $result = null;
        $query = "SELECT $field FROM assignments WHERE ID = ? AND userID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $this->ID, $this->UserID);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        settype($result, $type);
        return $result;
    }
    public function returnAsJson()
    {
        return json_encode([
            'ID' => $this->ID,
            'Title' => $this->Title,
            'Description' => $this->Description,
            'StartDate' => $this->StartDate,
            'DueDate' => $this->DueDate,
            'userID' => $this->UserID,
            'groupID' => $this->groupID
        ]);
    }
    public function fetchAll()
    {
        $query = "
            SELECT a.* 
            FROM assignments a
            INNER JOIN user_groups ug ON a.groupID = ug.groupID
            WHERE ug.userID = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->UserID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $assignment = new Assignments($this->conn);
            foreach ($row as $key => $value) {
                $assignment->$key = $value;
            }
            $this->assignments[] = $assignment;
        }
        $stmt->close();
        return $this->assignments;
    }
    public function addObserver(AssignmentObserver $observer): void
    {
        $this->observers[] = $observer;
    }
    public function removeObserver(AssignmentObserver $observer): void
    {
        $this->observers = array_filter($this->observers, fn($obs) => $obs !== $observer);
    }
    public function notifyObservers(string $message): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($message);
        }
    }
}
