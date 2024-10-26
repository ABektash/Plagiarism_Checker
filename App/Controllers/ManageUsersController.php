<?php
require_once MODELS . 'User.php';
class ManageUsersController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }
    public function index()
    {
        $user = new User($this->db);
        $users = $user->getAllUsers();
        $data = ['users' => $users]; 
    
        $this->view('manageUsers', $data); 
    }
    
}
