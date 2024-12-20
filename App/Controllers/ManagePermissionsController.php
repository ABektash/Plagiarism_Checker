<?php
require_once MODELS . 'UserType.php';
require_once MODELS . 'PageReference.php';
require_once MODELS . 'Page.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ManagePermissionsController extends Controller
{
    private $PageReferenceModel;
    private $pageModel;
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'DatabaseConnection.php';
        $db_instance = DatabaseConnection::getInstance();
        $conn = $db_instance->getConnection();
        $this->db = $conn;
    }
    public function index()
    {
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 1)) {
            $data = $this->getPages();

            $this->view('managePermissions', $data);
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->PageReferenceModel = new PageReference($this->db);

            $userTypeID = isset($_POST['userTypeID']) ? intval($_POST['userTypeID']) : null;
            $chosenPages = isset($_POST['chosenPages']) ? $_POST['chosenPages'] : [];
            $data = $this->getPages();

            if ($userTypeID && !empty($chosenPages)) {
                $chosenPages = array_unique($chosenPages);

                $this->PageReferenceModel->deletePagesByParentID($userTypeID);

                foreach ($chosenPages as $pageId) {
                    $this->PageReferenceModel->addPageToParent($userTypeID, $pageId);
                }

                $data["result"] = "Success";

                $this->view('managePermissions', $data);
            } else {
                $data["result"] = "Invalid data. Please select a user type and at least one page.";

                $this->view('managePermissions', $data);
            }
        }
    }

    public function getPages()
    {
        $this->PageReferenceModel = new PageReference($this->db);
        $this->pageModel = new Page($this->db);

        $allPages = $this->pageModel->getAllPages();

        $chosenPageIds = $this->PageReferenceModel->getPagesByParentID(4);

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
