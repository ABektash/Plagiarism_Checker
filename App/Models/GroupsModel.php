<?php
require_once 'Groups.php';
require_once 'AssignmentModel.php';
require_once 'SubmissionModel.php';
require_once 'PlagiarismReportModel.php';
class GroupObject {
    private $conn;
    public $userID;

    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->userID = $userID;
    }

}
class GroupsFetcher {
    private $conn;
    private $userID;
    public $Groups = [];
    private $students = [];
    public function __construct($conn, $userID) {
        $this->conn = $conn;
        $this->userID = $userID;
        
    }
    public function fetchAllAssigments()
    {
        $groupsIds = [];   
    $query = "SELECT groupID FROM user_groups";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
      
        while ($row = $result->fetch_assoc()) {
            $groupsIds[] = $row['groupID'];
        }
    }
        for($i=0;$i<count($groupsIds);$i++)
        {
        $resultedGroup=new Groups($conn)->getStudentsByGroup($groupsIds[$i]);
            foreach ($resultedGroup as $student) {
                $this->students[] = $student;
            }
        }
    }
}
?>