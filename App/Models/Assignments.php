<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class Assignments
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
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
    // Function to get a single assignment by its ID
    public function getAssignmentById($id)
    {
        $query = "SELECT * FROM assignments WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        return $this->conn->query($query); 
    } catch (PDOException $e) {
        
        error_log("Error adding assignment: " . $e->getMessage());
        return false;
    }
}


// public function editAssignment($id, $title, $description, $dueDate, $groupID)
// {

//     $id = intval($id);
    
//     $query = "UPDATE assignments 
//               SET Title = $title, Description = $description, DueDate = $dueDate, groupID = $groupID 
//               WHERE ID = $id";

//     try {
//         return $this->conn->query($query);
//     } catch (PDOException $e) {
//         error_log("Error editing assignment: " . $e->getMessage());
//         return false;
//     }
// }
public function editAssignment($id, $title, $description, $dueDate, $groupID)
{
    $query = "UPDATE assignments 
              SET Title = ?, Description = ?, DueDate = ?, groupID = ? 
              WHERE ID = ?";

    // Prepare the statement
    $stmt = $this->conn->prepare($query);

    // Check if the statement was prepared successfully
    if (!$stmt) {
        // Log error and return false
        error_log("Failed to prepare statement: " . $this->conn->error);
        return false;
    }

    // Bind parameters to prevent SQL injection
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
    // Sanitize the input ID
    $id = intval($id);

    // Create the delete query
    $query = "DELETE FROM assignments WHERE ID = $id";

    // Execute the query
    try {
        return $this->conn->query($query);
    } catch (PDOException $e) {
        // Log the error and return false
        error_log("Error deleting assignment: " . $e->getMessage());
        return false;
    }
}

    
public function getGroups() {
    $query = "SELECT ID, Name FROM groups"; // Modify based on your database structure
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}



