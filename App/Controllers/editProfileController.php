<?php

require_once MODELS . 'User.php';



class EditProfileController extends Controller
{

    private $db;

    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index()
    {
        $this->view('editProfile');
    }



    public function submit()
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST['firstName'])) {
                $errors['firstNameError'] = "First name is required";
            }

            if (empty($_POST['lastName'])) {
                $errors['lastNameError'] = "Last name is required";
            }

            if (empty($_POST['email'])) {
                $errors['emailError'] = "Email is required";
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Invalid email format";
            }

            $organizationName = $_POST['organizationName'];
            if (!empty($organizationName) && strlen($organizationName) > 255) {
                $errors['organizationNameError'] = "Organization name is too long";
            }

            
            $address = $_POST['address'];

            
            $phone = $_POST['phone'];
            if (!empty($phone) && !ctype_digit($phone)) {
                $errors['phoneError'] = "Phone number must contain only numbers";
            }

            $birthday = $_POST['birthday'];
            if (!empty($birthday) && $birthday != "0000-00-00") {
                $birthdayDate = DateTime::createFromFormat('m/d/Y', $birthday);
                $currentDate = new DateTime();

                if (!$birthdayDate || $birthdayDate->format('m/d/Y') !== $birthday) {
                    $errors['birthdayError'] = "Invalid birthday format. Use MM/DD/YYYY";
                } elseif ($birthdayDate >= $currentDate) {
                    $errors['birthdayError'] = "Birthday must be before the current date";
                }
            }

            if (empty($errors)) {
                $user = new User($this->db);

                $id = $_SESSION['user']['ID']; 

                $user->first_name = $_POST['firstName'];
                $user->last_name = $_POST['lastName'];
                $user->email = $_POST['email'];
                $user->organization = $organizationName;
                $user->address = $address;
                $user->phone_number = $phone;
                $user->birthday = $birthday;

                if ($user->editUser($id, $user->first_name, $user->last_name, $user->email, $user->organization, $user->address, $user->phone_number, $user->birthday)) {
                    $this->view('profile');
                } else {
                    $errors['emailError'] = "Email already exists or failed to update profile.";
                    $this->view('editProfile', $errors);
                }
            } else {
                $this->view('editProfile', $errors);
            }
        }
    }
}
