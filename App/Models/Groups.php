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

    public $userID;
    public $groupID;

    public function __construct($db)
    {
        $this->db = $db;
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


    function getUserGroupCountByUserID($userID) {
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
    

    public function getAllInst()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $result = $this->db->query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
}
