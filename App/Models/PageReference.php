<?php
class PageReference {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getPagesByUserType($userTypeID) {
        $query = "SELECT PageID FROM page_reference WHERE ParentPageID = $userTypeID";
        $result = $this->db->query($query);
        $pages = [];

        while ($row = $result->fetch_assoc()) {
            $pages[] = $row['PageID'];
        }

        return $pages;
    }

    public function deletePagesByUserType($userTypeID) {
        $sql = "DELETE FROM page_reference WHERE ParentPageID = $userTypeID";
        $this->db->query($sql);
    }

    public function addPageToUserType($userTypeID, $pageId) {
        $sql = "INSERT INTO page_reference (ParentPageID, PageID) VALUES ($userTypeID, $pageId)";
        $this->db->query($sql);
    }
}
?>
