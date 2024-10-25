<?php


require_once MODELS.'User.php'; 


class LoginController extends Controller
{

    private $db;

    public function __construct()
    {
        require_once CONFIG.'dbh.inc.php';
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
                    if ($_SESSION['user']['UserType_id'] == 1){
                        $this->view('adminDashboard');
                    } elseif ($_SESSION['user']['UserType_id'] == 2 || $_SESSION['user']['UserType_id'] == 3){
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
