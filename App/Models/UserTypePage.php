<?php
class UserTypePage {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function create($userTypeID, $pageID) {
        $query = "INSERT INTO usertype_pages (UserTypeID, PageID) VALUES ($userTypeID, $pageID)";
        $this->db->query($query);
    }
    
    public function read($id) {
        $query = "SELECT * FROM usertype_pages WHERE ID = $id";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }
    
    public function update($id, $userTypeID, $pageID) {
        $query = "UPDATE usertype_pages SET UserTypeID = $userTypeID, PageID = $pageID WHERE ID = $id";
        $this->db->query($query);
    }
    
    public function delete($id) {
        $query = "DELETE FROM usertype_pages WHERE ID = $id";
        $this->db->query($query);
    }
    
    public function getAll() {
        $query = "SELECT * FROM usertype_pages";
        $result = $this->db->query($query);
        $usertypePages = [];
        
        while ($row = $result->fetch_assoc()) {
            $usertypePages[] = $row;
        }
        
        return $usertypePages;
    }

    public function deleteByUserTypeID($userTypeID) {
        $query = "DELETE FROM usertype_pages WHERE UserTypeID = $userTypeID";
        $this->db->query($query);
    }
}
?>
