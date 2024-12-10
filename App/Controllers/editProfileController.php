<?php
require_once MODELS . 'User.php';
require_once MODELS . 'Groups.php';
require_once MODELS . 'Submission.php';
require_once MODELS . 'Forums.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
        $id = $_SESSION['user']['ID'] ?? null;
        $userType = $_SESSION['user']['UserType_id'] ?? null;

        if (($id !== null) && ($userType == 2 || $userType == 3)) {

            $this->view('editProfile');
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
            if (!empty($phone)) {
                if (!preg_match('/^(010|011|012|015)[0-9]{8}$/', $phone)) {
                    $errors['phoneError'] = "Invalid phone number";
                }
            }

            $birthday = $_POST['birthday'];
            if (!empty($birthday) && $birthday != "0000-00-00") {
                $date = DateTime::createFromFormat('Y-m-d', $birthday);
                if ($date && $date->format('Y-m-d') === $birthday) {
                    $currentDate = new DateTime();
                    if ($date > $currentDate) {
                        $errors['birthdayError'] = "Birthday cannot be in the future.";
                    }
                } else {
                    $errors['birthdayError'] = "Invalid birthday format. Use YYYY-MM-DD";
                }
            }

            if (empty($errors)) {
                $user = new User(db: $this->db);

                $id = $_SESSION['user']['ID'];

                $user->first_name = $_POST['firstName'];
                $user->last_name = $_POST['lastName'];
                $user->email = $_POST['email'];
                $user->organization = $organizationName;
                $user->address = $address;
                $user->phone_number = $phone;
                $user->birthday = $birthday;

                if ($user->editUser($id, $user->first_name, $user->last_name, $user->email, $user->organization, $user->address, $user->phone_number, $user->birthday)) {
                    $group = new Groups($this->db);
                    $data["groupsCount"] = $group->getUserGroupCountByUserID($_SESSION['user']['ID']);

                    if ($_SESSION['user']['UserType_id'] == 2) {
                        $submission = new Submission($this->db);
                        $forum = new Forums($this->db);
                        $forumsData = $forum->getForumsData($_SESSION['user']['ID']);
                        $assignments = $submission->getAssignmentsByUserID($_SESSION['user']['ID']);
                        $data["numberOfAssignments"] = count($assignments);
                        $data["assignments"] = $assignments;
                        $data["forumsData"] = $forumsData;
                    } elseif ($_SESSION['user']['UserType_id'] == 3) {
                        $submission = new Submission($this->db);
                        $forum = new Forums($this->db);
                        $forumsData = $forum->getForumsData($_SESSION['user']['ID']);
                        $submissions = $submission->getSubmissionsByUserId($_SESSION['user']['ID']);
                        $data["numberOfAssignments"] = count($submissions);
                        $data["submissions"] = $submissions;
                        $data["forumsData"] = $forumsData;
                    }

                    $this->view('profile', $data);
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
