<?php

require_once MODELS . 'UserType.php';
require_once MODELS . 'UserTypePage.php';
require_once MODELS . 'Page.php';

class ManagePermissionsController extends Controller
{

    private $userTypeModel;
    private $userTypePageModel;
    private $pageModel;
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }
    public function index()
    {
        $data = $this->getPages();

        $this->view('managePermissions', $data); 
    }



    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->userTypePageModel = new UserTypePage($this->db);
            
            $userTypeID = isset($_POST['userTypeID']) ? intval($_POST['userTypeID']) : null;
            $chosenPages = isset($_POST['chosenPages']) ? $_POST['chosenPages'] : [];
            $data = $this->getPages();
    
            if ($userTypeID && !empty($chosenPages)) {
                $chosenPages = array_unique($chosenPages);
    
                $this->userTypePageModel->deletePagesByUserType($userTypeID);
    
                foreach ($chosenPages as $pageId) {
                    $this->userTypePageModel->addPageToUserType($userTypeID, $pageId);
                }
    
                $data["result"] = "Success";
    
                $this->view('managePermissions', $data);
            } else {
                $data["result"] = "Invalid data. Please select a user type and at least one page.";
    
                $this->view('managePermissions', $data);
            }
        }
    }
    





    public function getPages(){
        $this->userTypePageModel = new UserTypePage($this->db);
        $this->pageModel = new Page($this->db);

        $allPages = $this->pageModel->getAllPages();

        $chosenPageIds = $this->userTypePageModel->getPagesByUserType(4);

        
        $availablePages = [];
        $chosenPages = [];

        foreach ($allPages as $page) {
            $friendlyName = $this->pageModel->getFriendlyNameById($page['id']);
            if ($friendlyName) {
                
                $availablePages[] = [
                    'id' => $page['id'],
                    'friendlyName' => $friendlyName
                ];

                
                if (in_array($page['id'], $chosenPageIds)) {
                    $chosenPages[] = [
                        'id' => $page['id'],
                        'friendlyName' => $friendlyName
                    ];
                }
            }
        }

        $data = [
            'availablePages' => $availablePages,
            'chosenPages' => $chosenPages
        ];

        return $data;
    }
}
