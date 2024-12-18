<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class submitController extends Controller
{

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
        $id = $_GET['assignmentID'] ?? null;

        if (!$id) {
            $this->view('submit', ['error' => 'No assignment ID provided.']);
            return;
        }

        $assignmentModel = new Assignments($this->db);
        $assignment = $assignmentModel->getAssignmentById($id);

        if ($assignment) {
            $this->view('submit', ['assignment' => $assignment]);
        } else {
            $this->view('submit', ['error' => 'No assignment data found.']);
        }
    }

    public function submit()
    {
        // Get assignment ID
        $id = $_GET['assignmentID'] ?? null;

        if (!$id) {
            $this->view('submit', ['error' => 'No assignment ID provided.']);
            return;
        }

        // Get assignment data from the model
        $assignmentModel = new Assignments($this->db);
        $assignment = $assignmentModel->getAssignmentById($id);

        if ($assignment) {
            // Check if a file is uploaded
            if (isset($_FILES['assignment-file']) && $_FILES['assignment-file']['error'] == UPLOAD_ERR_OK) {
                // Get the temporary file path and file name from the uploaded file
                $fileTmpPath = $_FILES['assignment-file']['tmp_name'];
                $fileName = $_FILES['assignment-file']['name'];

                // Define the local file path (uploads directory)
                $targetDirectory = __DIR__ . "/../../uploads/"; // Path relative to the controller file
                $targetFilePath = $targetDirectory . basename($fileName);

                // Move the uploaded file to the 'uploads' directory
                if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                    $assignmentModel->saveFileToDatabase($id, $fileName, $targetFilePath);
                    $this->view('dashboard');
                } else {
                    // Error in moving the uploaded file
                    $this->view('submit', ['error' => 'Error in moving the uploaded file.']);
                }
            } else {
                // Error in file upload
                $this->view('submit', ['error' => 'No file uploaded or upload error occurred.']);
            }
        } else {
            $this->view('submit', ['error' => 'No assignment data found.']);
        }

    }



}


