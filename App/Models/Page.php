<?php
class Page {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllPages() {
        $query = "SELECT id, FreindlyName FROM pages";
        $result = $this->db->query($query);

        $pages = [];

        while ($row = $result->fetch_assoc()) {
            $pages[] = $row;
        }

        return $pages;
    }


    public function getFriendlyNameById($id) {
        $query = "SELECT FreindlyName FROM pages WHERE id = $id";
        $result = $this->db->query($query);
        
        if ($result && $row = $result->fetch_assoc()) {
            return $row['FreindlyName'];
        }

        return null; 
    }
}
?>
