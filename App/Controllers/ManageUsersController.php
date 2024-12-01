<?php
require_once MODELS . 'User.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ManageUsersController extends Controller
{
    private $db;

    public function __construct()
    {
        require_once CONFIG . 'dbh.inc.php';
        $this->db = $conn;
    }

    public function index()
    {
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 1)) {
            $user = new User($this->db);
            $users = $user->getAllUsers();
            $data = ['users' => $users];

            $this->view('manageUsers', $data);
        } else {

            $data = [
                "error_code" => 403,
                "error_message" => "We're sorry, You don't have access to this page.",
                "page_To_direct" => "home",
            ];

            $this->view('errorPage', $data);
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userID'])) {
            $userID = $_POST['userID'];
            $user = new User($this->db);

            $result = $user->deleteUser($userID);
            $users = $user->getAllUsers();
            $data = ['users' => $users];
            if ($result) {
                $this->view('manageUsers', $data);
            } else {
                $data["deleteError"] = "Couldn't delete user!";
                $this->view('manageUsers', $data);
            }
        }
    }

    public function submit()
    {
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST['FirstName'])) {
                $errors['firstNameError'] = "First name is required";
            }

            if (empty($_POST['LastName'])) {
                $errors['lastNameError'] = "Last name is required";
            }

            if (empty($_POST['Email'])) {
                $errors['emailError'] = "Email is required";
            } elseif (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Invalid email format";
            }

            $organizationName = $_POST['Organization'];
            if (!empty($organizationName) && strlen($organizationName) > 255) {
                $errors['organizationNameError'] = "Organization name is too long";
            }

            $address = $_POST['Address'];

            $phone = $_POST['PhoneNumber'];
            if (!empty($phone) && !ctype_digit($phone)) {
                $errors['phoneError'] = "Phone number must contain only numbers";
            }

            $birthday = $_POST['Birthday'];
            if (!empty($birthday)) {
                $date = DateTime::createFromFormat('Y-m-d', $birthday);
                $currentDate = new DateTime();

                if (!$date || $date->format('Y-m-d') !== $birthday) {
                    $errors['birthdayError'] = "Invalid birthday format. Use YYYY-MM-DD";
                } elseif ($date >= $currentDate) {
                    $errors['birthdayError'] = "Birthday must be before the current date";
                }
            }


            $password = $_POST['Password'];
            if (empty($password)) {
                $errors['passwordError'] = "Password is required.";
            } elseif (strlen($password) < 6) {
                $errors['passwordError'] = "Password must be at least 6 characters long.";
            }

            $UserType_id = $_POST['UserType_id'];

            if (empty($errors)) {
                $user = new User(db: $this->db);

                $id = $_POST['ID'];

                $user->first_name = $_POST['FirstName'];
                $user->last_name = $_POST['LastName'];
                $user->email = $_POST['Email'];
                $user->organization = $organizationName;
                $user->address = $address;
                $user->phone_number = $phone;
                $user->birthday = $birthday;
                $user->user_type_id = $UserType_id;
                $user->password = $password;

                if ($user->editUser($id, $user->first_name, $user->last_name, $user->email, $user->organization, $user->address, $user->phone_number, $user->birthday, $user->password, $user->user_type_id)) {
                    $user = new User($this->db);
                    $users = $user->getAllUsers();
                    $data = ['users' => $users];

                    $this->view('manageUsers', $data);
                } else {
                    $errors['emailError'] = "Email already exists or failed to edit user.";
                    $this->view('manageUsers', $errors);
                }
            } else {
                $this->view('manageUsers', $errors);
            }
        }
    }
}
