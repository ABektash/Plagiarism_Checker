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

    public function getPageById($pageId) {
        $pageId = intval($pageId);
    
        if ($pageId <= 0) {
            return null;
        }
    
        $query = "SELECT id, FriendlyName, FileName FROM pages WHERE id = $pageId";
        $result = $this->db->query($query);
    
        if ($row = $result->fetch_assoc()) {
            return [
                'id' => $row['id'],
                'friendlyName' => $row['FriendlyName'],
                'fileName' => $row['FileName'],
            ];
        }
    
        return null;
    }
    
    
}
?>
