<?php

require_once MODELS . 'User.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ResetPasswordController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index($resetKey)
    {
        $query = "SELECT email, resetKey, expDate FROM password_reset_temp WHERE resetKey = '$resetKey'";
        $result = $this->db->query($query);

        if ($result->num_rows === 0) {
            $data = [
                "error_code" => 404,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    
        $row = $result->fetch_assoc();
    
        $currentDate = new DateTime(); 
        $expiryDate = new DateTime($row['expDate']); 
    
        if ($currentDate > $expiryDate) {
            $data = [
                "error_code" => 404,
                "error_message" => "This link has expired.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
        $_SESSION['reset_email'] = $row['email'];
        $deleteQuery = "DELETE FROM password_reset_temp WHERE resetKey = '$resetKey'";
        $this->db->query($deleteQuery);
        $this->view('resetPassword');
    }

    public function submit()
    {
        $errors = [];

        if (isset($_POST['submit'])) {

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

                if ($user->resetPassword($_SESSION["reset_email"], $_POST['password'])) {
                    $this->view('login');
                } else {
                    $errors['confirmPasswordError'] = "Something went wrong!";
                    $this->view('resetPassword', $errors);
                }
            } else {
                $this->view('resetPassword', $errors);
            }
        }
    }

}
