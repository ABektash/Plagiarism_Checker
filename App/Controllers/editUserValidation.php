<?php
require_once '../Models/User.php';
require_once '../Config/DatabaseConnection.php';
$db_instance = DatabaseConnection::getInstance();
$conn = $db_instance->getConnection();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
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
    if (!empty($phone)) {
        if (!preg_match('/^(010|011|012|015)[0-9]{8}$/', $phone)) {
            $errors['phoneError'] = "Invalid phone number";
        }
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
    if (!empty($password) && strlen($password) < 6) {
        $errors['passwordError'] = "Password must be at least 6 characters long.";
    }

    $UserType_id = $_POST['UserType_id'];

    if (empty($errors)) {
        $user = new User($conn);
        $id = $_POST['ID'];

        $user->first_name = $_POST['FirstName'];
        $user->last_name = $_POST['LastName'];
        $user->email = $_POST['Email'];
        $user->organization = $organizationName;
        $user->address = $address;
        $user->phone_number = $phone;
        $user->birthday = $birthday;
        $user->user_type_id = $UserType_id;
        if ($password != '') {
            $user->password = $password;
        } else {
            $user->password = null;
        }


        if ($user->editUser($id, $user->first_name, $user->last_name, $user->email, $user->organization, $user->address, $user->phone_number, $user->birthday, $user->password, $user->user_type_id)) {
            echo json_encode(['success' => true]);
        } else {
            $errors['emailError'] = "Email already exists or failed to edit user.";
            echo json_encode(['errors' => $errors]);
        }
    } else {
        echo json_encode(['errors' => $errors]);
    }
}
