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


    public function deleteSubmission($submissionID)
    {
        $userID = intval($submissionID);

        $query = "DELETE FROM submissions WHERE ID = $submissionID";

        return mysqli_query($this->db, $query);
    }
}
