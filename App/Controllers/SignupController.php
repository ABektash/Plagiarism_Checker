<?php
require_once MODELS . 'User.php';
require_once MODELS . 'Page.php';
require_once MODELS . 'PageReference.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class SignupController extends Controller
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
        $this->view('signup');
    }

    public function submit()
    {
        $errors = [];

        if (isset($_POST['submit'])) {
            if (empty($_POST['fname'])) {
                $errors['fnameError'] = "First name is required";
            }

            if (empty($_POST['lname'])) {
                $errors['lnameError'] = "Last name is required";
            }

            if (empty($_POST['email'])) {
                $errors['emailError'] = "Email is required";
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Invalid email format";
            }

            if (empty($_POST['password'])) {
                $errors['passwordError'] = "Password is required";
            } elseif (strlen($_POST['password']) < 6) {
                $errors['passwordError'] = "Password must be at least 6 characters long";
            }

            if (empty($_POST['confirmPassword'])) {
                $errors['confirmPasswordError'] = "Confirming password is required";
            } elseif ($_POST['confirmPassword'] != $_POST['password']) {
                $errors['confirmPasswordError'] = "Passwords don't match";
            }

            if (empty($errors)) {
                $user = new User($this->db);

                $user->first_name = $_POST['fname'];
                $user->last_name = $_POST['lname'];
                $user->email = $_POST['email'];
                $user->password = $_POST['password'];
                $PageReferenceModel = new PageReference($this->db);
                $pageModel = new Page($this->db);

                $allowedPageIds = $PageReferenceModel->getPagesByParentID(4);
                $_SESSION['pages'] = [];

                foreach ($allowedPageIds as $pageId) {
                    $friendlyName = $pageModel->getFriendlyNameById($pageId);
                    if ($friendlyName) {
                        $_SESSION['pages'][] = $friendlyName;
                    }
                }

                if ($user->signup()) {
                    $this->view('home');
                } else {
                    $errors['emailError'] = "Email already exists";
                    $this->view('signup', $errors);
                }
            } else {
                $this->view('signup', $errors);
            }
        }
    }
}
