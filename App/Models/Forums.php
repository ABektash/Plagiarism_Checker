<?php
class Forums
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($submissionID, $instructorID, $studentID)
    {
        $query = "INSERT INTO `forums` (`SubmissionID`, `InstructorID`, `StudentID`) VALUES ('$submissionID', '$instructorID', '$studentID');";
        return mysqli_query($this->db, $query);
    }

    public function getForumById($id)
    {
        $id = mysqli_real_escape_string($this->db, $id);

        $query = "SELECT f.ID, f.SubmissionID, f.Createdat, 
                    s.FirstName AS StudentFirstName, s.LastName AS StudentLastName,
                    i.FirstName AS InstructorFirstName, i.LastName AS InstructorLastName
                    FROM `forums` f
                    LEFT JOIN users s ON f.StudentID = s.ID
                    LEFT JOIN users i ON f.InstructorID = i.ID
                    WHERE f.ID = $id
                    LIMIT 1";

        $result = mysqli_query($this->db, $query);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function delete($id)
    {
        $query = "DELETE FROM `forums` WHERE ID = $id";
        return mysqli_query($this->db, $query);
    }

    public function getAll($userid)
    {
        $query = "SELECT * FROM `forums` WHERE `InstructorID` = '$userid' OR `StudentID` = '$userid'; ORDER BY Creadedat ASC;";
        $result = $this->db->query($query);
        $Allforums = [];

        while ($row = $result->fetch_assoc()) {
            $Allforums[] = $row;
        }
        return $Allforums;
    }
}


class Forums_Messages
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($forumID, $senderID, $messagetext)
    {
        $message_text = mysqli_real_escape_string($this->db, $messagetext);
        $query = "INSERT INTO `Forums_Messages` (`ForumID`, `SenderID`, `Messagetext`) VALUES ('$forumID', '$senderID', '$message_text');";

        return mysqli_query($this->db, $query);
    }

    public function getAllMessages($forumID)
    {
        $query = "SELECT m.SenderID, u.Messagetext FROM Messages m WHERE m.ForumID = '$forumID' ORDER BY m.Sentat ASC;";
        $result = $this->db->query($query);
        $AllMessages = [];

        while ($row = $result->fetch_assoc()) {
            $AllMessages[] = $row;
        }

        return $AllMessages;
    }
}
