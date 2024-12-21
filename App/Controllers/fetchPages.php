<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['userType'])) {

    require_once '../Models/PageReference.php';
    require_once '../Models/Page.php';
    require_once '../Config/DatabaseConnection.php';
    $db_instance = DatabaseConnection::getInstance();
    $conn = $db_instance->getConnection();

    $userTypeID = intval($_POST['userType']);

    if (!$conn) {
        echo "Error in connection";
    }

    $pageModel = new Page($conn);
    $PageReferenceModel = new PageReference($conn);

    $availablePages = $pageModel->getAllPages();
    $chosenPages = $PageReferenceModel->getPagesByParentID($userTypeID);

    $response = [
        'availablePages' => $availablePages,
        'chosenPages' => array_filter($availablePages, function ($page) use ($chosenPages) {
            return in_array($page['id'], $chosenPages);
        })
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
