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

    public function createForum($submissionID, $instructorID, $studentID)
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


    public function getForumsData($userID)
    {
        $query = "
        SELECT 
            forums.ID AS forumID,
            forums.Createdat AS forumCreatedAt,
            CASE
                WHEN forums.StudentID = $userID THEN forums.InstructorID
                ELSE forums.StudentID
            END AS otherUserID,
            users.FirstName,
            users.LastName,
            forums_messages.Messagetext AS lastMessage,
            forums_messages.Sentat AS lastMessageDate,
            forums_messages.Isread
        FROM forums
        JOIN users 
            ON (users.ID = CASE
                            WHEN forums.StudentID = $userID THEN forums.InstructorID
                            ELSE forums.StudentID
                           END)
        JOIN forums_messages 
            ON forums_messages.ID = (
                SELECT MAX(ID) 
                FROM forums_messages 
                WHERE forums_messages.ForumID = forums.ID
            )
        WHERE forums.StudentID = $userID OR forums.InstructorID = $userID
        ";
    
        $result = $this->db->query($query);
    
        if (!$result) {
            die("Query failed: " . $this->db->error);
        }
    
        $forumsData = [];
        while ($row = $result->fetch_assoc()) {
            $forumsData[] = $row;
        }

        usort($forumsData, function ($a, $b) {
            return strtotime($b['lastMessageDate']) - strtotime($a['lastMessageDate']);
        });
    
        return $forumsData;
    }

    public function deleteForum($id)
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

    public function createForums_Messages($forumID, $senderID, $messagetext)
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
        $userID = $_SESSION['user']['ID'] ?? null;

        $updateQuery = "UPDATE forums_messages
                        SET Isread = 1
                        WHERE ForumID = '$forumID'
                          AND SenderID != '$userID';";

        $this->db->query($updateQuery);

        $selectQuery = "SELECT m.SenderID, m.Messagetext, m.Isread, m.sentat
                        FROM forums_messages m
                        WHERE m.ForumID = '$forumID'
                        ORDER BY m.Sentat ASC;";

        $result = $this->db->query($selectQuery);
        $AllMessages = [];

        while ($row = $result->fetch_assoc()) {
            $AllMessages[] = $row;
        }

        return $AllMessages;
    }
}
