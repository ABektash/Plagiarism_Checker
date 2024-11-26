<?php

require_once MODELS . 'Assignments.php'; // Include the Assignments model

class ManageAssignmentsController extends Controller
{
    private $assignmentsModel;

    // Constructor to initialize the model and database connection
    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php'; // Include the database connection
        $this->db = $conn;
        $this->assignmentsModel = new Assignments($this->db); // Instantiate the model
    }

    // Function to load the assignments view with all assignments
    public function index()
    {
        $assignments = $this->assignmentsModel->getAssignments(); // Fetch all assignments
        $this->view('manageAssignments', ['assignments' => $assignments]); // Pass assignments to the view
    }

    // Function to handle adding a new assignment
    public function addAssignment()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get form data
            $title = $_POST['assignment-title'];
            $description = $_POST['assignment-description'];
            $dueDate = $_POST['due-date'];
            $groupID = $_POST['groupID'];

            // Add the assignment to the database
            if($this->assignmentsModel->addAssignment($title, $description, $dueDate,  $groupID)){
                echo "<script>
                window.location.href = '" . redirect('manageAssignments/index') . "';
                </script>";
            }else{
                $assignments = $this->assignmentsModel->getAssignments(); 
                $data['assignments'] = $assignments;
                $data['insertError'] = true;
                $this->view('manageAssignments', $data ); 
                
            }
       

        }
    }
    

    // Function to handle editing an existing assignment
    public function editAssignment($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $title = $_POST['assignment-title'];
            $description = $_POST['assignment-description'];
            $dueDate = $_POST['due-date'];
            $groupID = $_POST['groupID'];

            
            if ($this->assignmentsModel->editAssignment($id, $title, $description, $dueDate, $groupID)) {
                echo "<script>
                    window.location.href = '" . redirect('manageAssignments/index') . "';
                    </script>";
            } else {
                    $assignments = $this->assignmentsModel->getAssignments(); 
                    $data['assignments'] = $assignments;
                    $data['insertError'] = true;
                    $this->view('manageAssignments', $data ); 
            }
        } else {           
            $assignment = $this->assignmentsModel->getAssignmentById($id);
            $this->view('editAssignment', ['assignment' => $assignment]);
        }
    }

    // Function to handle deleting an assignment
    public function deleteAssignment($id)
    {
        if ($this->assignmentsModel->deleteAssignment($id)) {
            echo "<script>
                window.location.href = '" . redirect('manageAssignments/index') . "';
                </script>";
        } else {
            $assignments = $this->assignmentsModel->getAssignments(); 
                $data['assignments'] = $assignments;
                $data['insertError'] = true;
                $this->view('manageAssignments', $data ); 
        }
    }
    
    
}
