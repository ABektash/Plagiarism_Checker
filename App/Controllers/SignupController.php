<?php 
class SignupController extends Controller
{
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
                $errors['confirmPasswordError'] = "Passwords doesn't match";
            }

            if (empty($_POST['accountType'])) {
                $errors['accountTypeError'] = "Account type is required";
            }

            if (empty($errors)) {
                $this->view('home');
            } else {
                $this->view('signup', $errors);
            }
        }
    }
}
