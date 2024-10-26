<?php 

require_once MODELS.'User.php'; 


class AdminProfileController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG.'dbh.inc.php';
        $this->db = $conn; 
    }


    public function index($id)
    { 
        $user = new User($this->db);
        $userDetails = $user->getUserById($id);
        if ($userDetails){
            $this->view('adminProfile', $userDetails);
        } else {
            $this->view('404Page');
        }
    }
    
}