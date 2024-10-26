<?php 

require_once MODELS.'UserType.php'; 
require_once MODELS.'UserTypePage.php';

class ManagePermissionsController extends Controller
{

    private $userTypeModel;
    private $userTypePageModel;
    private $db;

    public function __construct()
    {
        require_once CONFIG.'dbh.inc.php';
        $this->db = $conn; 
    }
    public function index()
    {
        $this->view('managePermissions');
    }


    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userTypeModel = new UserType($this->db);
            $this->userTypePageModel = new UserTypePage($this->db);
            $userTypeID = intval($_POST['userTypeID']);
            $chosenPages = $_POST['chosenPages'] ?? []; 

            $this->userTypePageModel->deleteByUserTypeID($userTypeID);

            foreach ($chosenPages as $pageID) {
                $this->userTypePageModel->create($userTypeID, intval($pageID));
            }

            $this->view('managePermissions');
        }
    }
}