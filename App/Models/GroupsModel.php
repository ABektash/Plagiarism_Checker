<?php
require_once 'AssignmentModel.php';
require_once 'SubmissionModel.php';
require_once 'PlagiarismReportModel.php';

class GroupsModel
{
    private $conn;
    private $instructorID;

    public function __construct($conn, $instructorID)
    {
        $this->conn = $conn;
        $this->instructorID = $instructorID;
    }

    public function getAllAssignments()
    {
        $query = "
            SELECT a.* 
            FROM assignments a
            INNER JOIN user_groups ug ON a.groupID = ug.groupID
            WHERE ug.userID = ?
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->instructorID);
        $stmt->execute();
        $result = $stmt->get_result();

        $assignments = [];
        while ($row = $result->fetch_assoc()) {
            $assignment = new AssignmentObject($this->conn, $this->instructorID);
            foreach ($row as $key => $value) {
                $assignment->$key = $value;
            }
            $assignments[] = $assignment;
        }
        
        $stmt->close();
        return $assignments;
    }


    public function getAllSubmissions($assignmentIDs = [])
{
    if (empty($assignmentIDs)) {
        return []; 
    }

    
    $placeholders = implode(',', array_fill(0, count($assignmentIDs), '?'));
    $query = "
        SELECT s.* 
        FROM submissions s
        WHERE s.assignmentID IN ($placeholders)
    ";

    $stmt = $this->conn->prepare($query);
    
    $types = str_repeat('i', count($assignmentIDs));
    $stmt->bind_param($types, ...$assignmentIDs);
    $stmt->execute();
    $result = $stmt->get_result();

    $submissions = [];
    while ($row = $result->fetch_assoc()) {
        $submission = new SubmissionObject($this->conn, $this->instructorID);
        foreach ($row as $key => $value) {
            $submission->$key = $value;
        }
        $submissions[] = $submission;
    }

    $stmt->close();
    return $submissions;
}

    
    public function getAllPlagiarismReports($assignmentIDs = [])
    {
        $submissions = $this->getAllSubmissions($assignmentIDs);
        $submissionIDs = array_map(function($submission) {
            return $submission->ID;
        }, $submissions);

        if (empty($submissionIDs)) {
            return [];
        }

        $query = "
            SELECT pr.* 
            FROM plagiarism_reports pr
            WHERE pr.submissionID IN (" . implode(',', array_fill(0, count($submissionIDs), '?')) . ")
        ";

        $stmt = $this->conn->prepare($query);
        $types = str_repeat('i', count($submissionIDs));
        $stmt->bind_param($types, ...$submissionIDs);
        $stmt->execute();
        $result = $stmt->get_result();

        $reports = [];
        while ($row = $result->fetch_assoc()) {
            $report = new PlagiarismReportObject($this->conn, $row['submissionID'], $this->instructorID);
            foreach ($row as $key => $value) {
                $report->$key = $value;
            }
            $reports[] = $report;
        }

        $stmt->close();
        return $reports;
    }

    
    public function returnAsJson()
{
    $assignments = $this->getAllAssignments();
    $assignmentIDs = array_map(function($assignment) {
        return $assignment->ID; 
    }, $assignments);

    $submissions = $this->getAllSubmissions($assignmentIDs); 
    $reports = $this->getAllPlagiarismReports($assignmentIDs);

    $result = [
        'assignments' => array_map(function($assignment) {
            return json_decode($assignment->returnAsJson());
        }, $assignments),
        'submissions' => array_map(function($submission) {
            return json_decode($submission->returnAsJson());
        }, $submissions),
        'plagiarism_reports' => array_map(function($report) {
            return json_decode($report->returnAsJson());
        }, $reports),
    ];

    return json_encode($result);
}

}

?>