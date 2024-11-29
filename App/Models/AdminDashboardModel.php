<?php
class AdminDashboardModel {
    private $conn;
    public $userID;

    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->userID = $userID;
    }

    public function getAdminDashboardDataAsJson()
    {
        $queries = [
            'plagiarismReportsCount' => "SELECT COUNT(*) as count FROM plagiarism_reports",
            'usersCount' => "SELECT COUNT(*) as count FROM users",
            'submissionsCount' => "SELECT COUNT(*) as count FROM submissions",
            'groupsCount' => "SELECT COUNT(*) as count FROM `groups`",
            'studentsCount' => "
                SELECT COUNT(*) as count 
                FROM users 
                WHERE UserType_id = (SELECT ID FROM usertypes WHERE Name = 'Student')",
            'instructorsCount' => "
                SELECT COUNT(*) as count 
                FROM users 
                WHERE UserType_id = (SELECT ID FROM usertypes WHERE Name = 'Instructor')"
        ];

        $resultData = [];

        foreach ($queries as $key => $query) {
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                die("Query preparation failed for $key: " . $this->conn->error);
            }
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result) {
                    $row = $result->fetch_assoc();
                    $resultData[$key] = $row['count'];
                } else {

                    $stmt->bind_result($count);
                    $stmt->fetch();
                    $resultData[$key] = $count;
                }
            } else {
                die("Execution failed for $key: " . $stmt->error);
            }
            $stmt->close();
        }

        return json_encode($resultData);
    }
}
?>
