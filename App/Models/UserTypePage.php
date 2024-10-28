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

    public function deletePagesByUserType($userTypeID) {
        $sql = "DELETE FROM usertype_pages WHERE UserTypeID = $userTypeID";
        $this->db->query($sql);
    }

    public function addPageToUserType($userTypeID, $pageId) {
        $sql = "INSERT INTO usertype_pages (UserTypeID, PageID) VALUES ($userTypeID, $pageId)";
        $this->db->query($sql);
    }
}
?>
