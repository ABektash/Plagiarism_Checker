<?php
require_once MODELS . 'User.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ChangePasswordController extends Controller
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
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 2 || $userType == 3 || $userType == 4)) {

            $this->view('changePassword');
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
        $errors = [];

        if (isset($_POST['submit'])) {

            if (empty($_POST['oldPassword'])) {
                $errors['oldPasswordError'] = "Old password is required";
            } elseif (strlen($_POST['password']) < 6) {
                $errors['oldPasswordError'] = "Password must be at least 6 characters long";
            } elseif ($_POST["oldPassword"] != $_SESSION["user"]["Password"]) {
                $errors['oldPasswordError'] = "Wrong password";
            }

            if (empty($_POST['password'])) {
                $errors['passwordError'] = "New password is required";
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

                if ($user->editUser(
                    $_SESSION['user']['ID'],
                    $_SESSION['user']['FirstName'],
                    $_SESSION['user']['LastName'],
                    $_SESSION['user']['Email'],
                    $_SESSION['user']['Organization'],
                    $_SESSION['user']['Address'],
                    $_SESSION['user']['PhoneNumber'],
                    $_SESSION['user']['Birthday'],
                    $_POST['password']
                )) {
                    $this->view('editProfile');
                } else {
                    $errors['confirmPasswordError'] = "Something went wrong!";
                    $this->view('changePassword', $errors);
                }
            } else {
                $this->view('changePassword', $errors);
            }
        }
    }
}
