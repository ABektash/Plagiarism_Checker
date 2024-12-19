<?php
require_once 'Submission.php';
class PlagiarismReport
{
    private $db;
    public $ID;
    public $submissionID;
    public $feedback;
    public $similarityPercentage;
    public $userID;
    public $submission;
    public function __construct($db, $submissionID = null, $userID = null)
    {
        $this->db = $db;
        $this->submissionID = $submissionID;
        $this->userID = $userID;
    }
    public function set($field, $value)
    {
        $query = "UPDATE plagiarism_reports SET $field = ? WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $value, $this->ID);
        $stmt->execute();
        $stmt->close();
        $this->$field = $value;
    }
    public function get($field, $type = 'string')
    {
        $result = null;
        $query = "SELECT $field FROM plagiarism_reports WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->ID);
        $stmt->execute();
        $stmt->bind_result($result);
        $stmt->fetch();
        $stmt->close();
        settype($result, $type);
        return $result;
    }
    private function fetchSubmission()
    {
        $query = "SELECT * FROM submissions WHERE ID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $this->submissionID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $submission = new Submission($this->db);
            foreach ($row as $key => $value) {
                $submission->$key = $value;
            }
        }
        $stmt->close();
        return isset($submission) ? $submission : null;
    }
    public function returnAsJson()
    {
        $this->submission = $this->fetchSubmission();
        return json_encode([
            'ID' => $this->ID,
            'submissionID' => $this->submissionID,
            'feedback' => $this->feedback,
            'similarityPercentage' => $this->similarityPercentage,
            'submission' => json_decode($this->submission->returnAsJson())
        ]);
    }
    public function fetchBySubmissionIDs($submissionIDs)
    {
        $placeholders = implode(',', array_fill(0, count($submissionIDs), '?'));
        $query = "SELECT * FROM plagiarism_reports WHERE submissionID IN ($placeholders)";

        $stmt = $this->db->prepare($query);

        $types = str_repeat('i', count($submissionIDs));
        $stmt->bind_param($types, ...$submissionIDs);

        $stmt->execute();
        $result = $stmt->get_result();
        $reports = [];

        while ($row = $result->fetch_assoc()) {
            $report = new PlagiarismReport($this->db, $row['submissionID'], $this->userID);

            foreach ($row as $key => $value) {
                $report->$key = $value;
            }
            $reports[] = $report;
        }

        $stmt->close();
        return $reports;
    }

    public function saveReport($submissionID, $responseAPI, $feedback = null, $similarityPercentage = 0, $grade = null)
    {
        try {
            $submissionID = mysqli_real_escape_string($this->db, $submissionID);
            $responseAPI = mysqli_real_escape_string($this->db, $responseAPI);
            $feedback = mysqli_real_escape_string($this->db, $feedback ?? '');
            $similarityPercentage = mysqli_real_escape_string($this->db, $similarityPercentage);
            $grade = $grade !== null ? mysqli_real_escape_string($this->db, $grade) : 'NULL';

            $sql = "
            INSERT INTO plagiarism_reports (submissionID, responseAPI, feedback, similarityPercentage, Grade) 
            VALUES ('$submissionID', '$responseAPI', '$feedback', '$similarityPercentage', $grade)
            ON DUPLICATE KEY UPDATE 
            responseAPI = '$responseAPI', 
            feedback = '$feedback', 
            similarityPercentage = '$similarityPercentage', 
            Grade = $grade
        ";

            $result = mysqli_query($this->db, $sql);

            if (!$result) {
                error_log('Database Error: ' . mysqli_error($this->db));
                error_log('Failed SQL: ' . $sql);
                return false;
            }

            return true;
        } catch (Exception $e) {
            error_log('Exception in saveReport: ' . $e->getMessage());
            return false;
        }
    }
}
