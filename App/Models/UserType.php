<?php
class UserType {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function create($name) {
        $query = "INSERT INTO usertypes (Name) VALUES ('$name')";
        $this->db->query($query);
    }
    
    public function read($id) {
        $query = "SELECT * FROM usertypes WHERE ID = $id";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }
    
    public function update($id, $name) {
        $query = "UPDATE usertypes SET Name = '$name' WHERE ID = $id";
        $this->db->query($query);
    }
    
    public function delete($id) {
        $query = "DELETE FROM usertypes WHERE ID = $id";
        $this->db->query($query);
    }
    
    public function getAll() {
        $query = "SELECT * FROM usertypes";
        $result = $this->db->query($query);
        $usertypes = [];
        
        while ($row = $result->fetch_assoc()) {
            $usertypes[] = $row;
        }
        
        return $usertypes;
    }
}
?>
