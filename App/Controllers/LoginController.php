<?php
require_once MODELS . 'User.php';
require_once MODELS . 'Page.php';
require_once MODELS . 'UserTypePage.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class LoginController extends Controller
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
        $this->view('login');
    }

    public function submit()
    {
        if (isset($_POST['submit'])) {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $errors = [];

            if (empty($email)) {
                $errors['emailError'] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Please enter a valid email address.";
            }

            if (empty($password)) {
                $errors['passwordError'] = "Password is required.";
            } elseif (strlen($password) < 6) {
                $errors['passwordError'] = "Password must be at least 6 characters long.";
            }

            if (!empty($errors)) {
                $this->view('login', $errors);
            } else {
                $user = new User($this->db);
                $login_result = $user->login($email, $password);

                if ($login_result) {
                    $_SESSION['user'] = $login_result;
                    $userTypePageModel = new UserTypePage($this->db);
                    $pageModel = new Page($this->db);
                    $allowedPageIds = $userTypePageModel->getPagesByUserType($_SESSION['user']['UserType_id']);
                    $_SESSION['pages'] = [];

                    foreach ($allowedPageIds as $pageId) {
                        $friendlyName = $pageModel->getFriendlyNameById($pageId);
                        if ($friendlyName) {
                            $_SESSION['pages'][] = $friendlyName;
                        }
                    }
                    if ($_SESSION['user']['UserType_id'] == 1) {
                        $this->view('adminDashboard');
                    } elseif ($_SESSION['user']['UserType_id'] == 2 || $_SESSION['user']['UserType_id'] == 3) {
                        $this->view('dashboard');
                    } else {
                        $this->view('home');
                    }
                } else {
                    $errors['passwordError'] = "Invalid email or password.";
                    $this->view('login', $errors);
                }
            }
        }
    }
}
