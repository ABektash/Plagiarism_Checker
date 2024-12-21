<?php
class PageReference {
    private $db;
    private $ID;
    private $parentID;
    private $pageID;


    public function __construct($db) {
        $this->db = $db;
    }

    public function getPagesByParentID($parentID) {
        $query = "SELECT PageID FROM page_reference WHERE ParentPageID = $parentID";
        $result = $this->db->query($query);
        $pages = [];

        while ($row = $result->fetch_assoc()) {
            $pages[] = $row['PageID'];
        }

        return $pages;
    }

    public function deletePagesByParentID($parentID) {
        $sql = "DELETE FROM page_reference WHERE ParentPageID = $parentID";
        $this->db->query($sql);
    }

    public function addPageToParent($parentID, $pageId) {
        $sql = "INSERT INTO page_reference (ParentPageID, PageID) VALUES ($parentID, $pageId)";
        $this->db->query($sql);
    }
}
?>
