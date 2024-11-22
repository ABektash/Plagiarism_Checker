<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class Forums
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($submissionID, $instructorID, $studentID)
    {
        $submissionID = mysqli_real_escape_string($this->db, $submissionID);
        $instructorID = mysqli_real_escape_string($this->db, $instructorID);
        $studentID = mysqli_real_escape_string($this->db, $studentID);

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
                    WHERE f.ID = '$id'
                    LIMIT 1";

        $result = mysqli_query($this->db, $query);


        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function delete($id)
    {
        $id = mysqli_real_escape_string($this->db, $id);

        $query = "DELETE FROM `forums` WHERE ID = $id";
        return mysqli_query($this->db, $query);
    }

    public function getAllForums($userid)
    {
        $userid = mysqli_real_escape_string($this->db, $userid);

        $query = "SELECT 
                      f.*, 
                      last_message.last_message_time,
                      CONCAT(i.FirstName, ' ', i.LastName) AS InstructorName,
                      CONCAT(s.FirstName, ' ', s.LastName) AS StudentName
                    FROM 
                      forums f
                    LEFT JOIN (
                        SELECT ForumID, MAX(Sentat) AS last_message_time
                        FROM forums_messages
                        GROUP BY ForumID
                    ) AS last_message ON f.ID = last_message.ForumID
                    LEFT JOIN users i ON f.InstructorID = i.ID
                    LEFT JOIN users s ON f.StudentID = s.ID
                    WHERE 
                        f.InstructorID = '$userid' OR f.StudentID = '$userid'
                    ORDER BY 
                        last_message.last_message_time DESC;";

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
        $forumID = mysqli_real_escape_string($this->db, $forumID);
        $senderID = mysqli_real_escape_string($this->db, $senderID);
        $messagetext = mysqli_real_escape_string($this->db, $messagetext);

        $query = "INSERT INTO `forums_messages` (`ForumID`, `SenderID`, `Messagetext`,`Isread`) VALUES ('$forumID', '$senderID', '$messagetext',0);";

        return mysqli_query($this->db, $query);
    }

    public function getAllMessages($forumID)
    {
        $forumID = mysqli_real_escape_string($this->db, $forumID);

        $query = "SELECT m.SenderID, m.Messagetext, m.Isread, m.sentat  FROM forums_messages m WHERE m.ForumID = '$forumID' ORDER BY m.Sentat ASC;";
        $result = $this->db->query($query);
        $AllMessages = [];

        while ($row = $result->fetch_assoc()) {
            $AllMessages[] = $row;
        }

        return $AllMessages;
    }
}
