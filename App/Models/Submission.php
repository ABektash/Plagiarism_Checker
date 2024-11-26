<?php
class Submission
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
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

    function getAssignmentsByUserID($userID) {
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
}
