<?php 
class LoginController extends Controller
{
    public function index()
    {
        $this->view('login');
    }


    public function submit()
    {
        if(isset($_POST['submit']))
        {
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
                $this->view('login',$errors);
            } else {
                $this->view('home');
            }
        }
        
    }




    public function signup()
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

            if (empty($_POST['accountType'])) {
                $errors['accountTypeError'] = "Account type is required";
            }

            if (empty($errors)) {
                $this->view('home');
            } else {
                $this->view('login', $errors);
            }
        }
    }
}
