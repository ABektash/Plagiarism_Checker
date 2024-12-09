<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class testController extends Controller
{

    private $db;
    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index()
    {
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 2)) {
            $user = new User($this->db);
            $users = $user->getAllUsers();
            $data = ['users' => $users];

            $this->view('test', $data);
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }

    public function detectPlagiarism()
    {
        // Fetch the request payload from the frontend
        $data = json_decode(file_get_contents('php://input'), true);

        // Define API endpoint and headers
        $apiUrl = "https://api.edenai.run/v2/text/plagia_detection";
        $headers = [
            'accept: application/json',
            'content-type: application/json',
            'authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiOWE5YTI2ZmYtNTJlOS00N2RkLWJmZDMtNmUxNzcwNDM5NjAwIiwidHlwZSI6ImFwaV90b2tlbiJ9.roZn8xBXOqwlQaBDmdxr5SAaIZtp0Sq6ETXar0clhF4' 
        ];

        // Initialize cURL for the API request
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Return the response to the frontend
        header('Content-Type: application/json');
        http_response_code($httpCode);
        echo $response;
    }
}