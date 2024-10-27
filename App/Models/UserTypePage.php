<?php
class UserTypePage {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getPagesByUserType($userTypeID) {
        $query = "SELECT PageID FROM usertype_pages WHERE UserTypeID = $userTypeID";
        $result = $this->db->query($query);
        $pages = [];

        while ($row = $result->fetch_assoc()) {
            $pages[] = $row['PageID'];
        }

        return $pages;
    }
}
?>
