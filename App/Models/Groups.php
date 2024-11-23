<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'User.php';
require_once 'UserTypePage.php';

class Groups
{
    private $db;
    private $table_name = "users";
    public $groupID;

    public function __construct($db)
    {
        $this->db = $db;
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



public function getUserGroupCountByUserID($userID) {
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
}
