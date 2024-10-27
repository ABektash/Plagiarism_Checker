<?php 

require_once MODELS.'UserType.php'; 
require_once MODELS.'UserTypePage.php';
require_once MODELS.'Page.php';

class ManagePermissionsController extends Controller
{

    private $userTypeModel;
    private $userTypePageModel;
    private $pageModel;
    private $db;

    public function __construct()
    {
        require_once CONFIG.'dbh.inc.php';
        $this->db = $conn; 
    }
    public function index()
    {
        $this->userTypePageModel = new UserTypePage($this->db);
        $this->pageModel = new Page($this->db);
        
        $pageIds = $this->userTypePageModel->getPagesByUserType(4);
        
        $friendlyNames = [];
        
        foreach ($pageIds as $page) {
            $friendlyName = $this->pageModel->getFriendlyNameById($page); 
            if ($friendlyName) {
                $friendlyNames[] = $friendlyName;
            }
        }
        
        $data["pages"] = $friendlyNames;

        $this->view('managePermissions', $data);
    }


    public function submit() {
        
    }
}