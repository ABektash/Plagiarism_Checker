<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Page.php';
require_once 'UserTypePage.php';

class User
{
    private $conn;
    private $table_name = "users";
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $organization;
    public $address;
    public $phone_number;
    public $birthday;
    public $user_type_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function signup()
{
    $first_name = mysqli_real_escape_string($this->conn, $this->first_name);
    $last_name = mysqli_real_escape_string($this->conn, $this->last_name);
    $email = mysqli_real_escape_string($this->conn, $this->email);
    $password = $this->password; 
    $organization = mysqli_real_escape_string($this->conn, $this->organization);
    $address = mysqli_real_escape_string($this->conn, $this->address);
    $phone_number = mysqli_real_escape_string($this->conn, $this->phone_number);
    $birthday = mysqli_real_escape_string($this->conn, $this->birthday);
    $user_type_id = 4; 

    $check_email_query = "SELECT Email FROM " . $this->table_name . " WHERE Email = '$email'";
    $result = mysqli_query($this->conn, $check_email_query);

    if (mysqli_num_rows($result) > 0) {
        return false; 
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO " . $this->table_name . " 
              (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
              VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$organization', '$address', '$phone_number', '$birthday', '$user_type_id')";

    if (mysqli_query($this->conn, $query)) {
        $user_id = mysqli_insert_id($this->conn);

        $_SESSION['user'] = [
            'ID' => $user_id,
            'FirstName' => $first_name,
            'LastName' => $last_name,
            'Email' => $email,
            'Organization' => $organization,
            'Address' => $address,
            'PhoneNumber' => $phone_number,
            'Birthday' => $birthday,
            'UserType_id' => $user_type_id,
            'Password' => $password
        ];

        return true; 
    }

    return false; 
}




    public function addUser()
    {
        $first_name = mysqli_real_escape_string($this->conn, $this->first_name);
        $last_name = mysqli_real_escape_string($this->conn, $this->last_name);
        $email = mysqli_real_escape_string($this->conn, $this->email);
        $password = mysqli_real_escape_string($this->conn, $this->password);
        $organization = mysqli_real_escape_string($this->conn, $this->organization);
        $address = mysqli_real_escape_string($this->conn, $this->address);
        $phone_number = mysqli_real_escape_string($this->conn, $this->phone_number);
        $birthday = mysqli_real_escape_string($this->conn, $this->birthday);
        $user_type_id = mysqli_real_escape_string($this->conn, $this->user_type_id);

        $check_email_query = "SELECT Email FROM " . $this->table_name . " WHERE Email = '$email'";
        $result = mysqli_query($this->conn, $check_email_query);

        if (mysqli_num_rows($result) > 0) {
            return false;
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table_name . " 
                  (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
                  VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$organization', '$address', '$phone_number', '$birthday', '$user_type_id')";

        if (mysqli_query($this->conn, $query)) {
            //$user_id = mysqli_insert_id($this->conn);
            return true;
        }

        return false;
    }


    public function login($email, $password)
{
    $email = mysqli_real_escape_string($this->conn, $email);

    $query = "SELECT * FROM " . $this->table_name . " WHERE Email = '$email' LIMIT 1";
    $result = mysqli_query($this->conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user_data['Password'])) {
            $_SESSION['user'] = [
                'ID' => $user_data['ID'],
                'FirstName' => $user_data['FirstName'],
                'LastName' => $user_data['LastName'],
                'Email' => $user_data['Email'],
                'Organization' => $user_data['Organization'],
                'Address' => $user_data['Address'],
                'PhoneNumber' => $user_data['PhoneNumber'],
                'Birthday' => $user_data['Birthday'],
                'UserType_id' => $user_data['UserType_id']
            ];
            $user_data['Password'] = $password;
            return $user_data;
        } else {
            return false;
        }
    }

    return false;
}



    public function getAllUsers()
    {
        $query = "SELECT * FROM " . $this->table_name;

        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $users;
        }

        return [];
    }


    public function editUser($id, $first_name, $last_name, $email, $organization, $address, $phone_number, $birthday, $password = null, $userTypeID = null)
    {
        $first_name = mysqli_real_escape_string($this->conn, $first_name);
        $last_name = mysqli_real_escape_string($this->conn, $last_name);
        $email = mysqli_real_escape_string($this->conn, $email);
        $organization = mysqli_real_escape_string($this->conn, $organization);
        $address = mysqli_real_escape_string($this->conn, $address);
        $phone_number = mysqli_real_escape_string($this->conn, $phone_number);
        $birthday = mysqli_real_escape_string($this->conn, $birthday);
        $id = mysqli_real_escape_string($this->conn, $id);

        $check_email_query = "SELECT id FROM " . $this->table_name . " WHERE Email = '$email' AND id != '$id'";
        $result = mysqli_query($this->conn, $check_email_query);

        if (mysqli_num_rows($result) > 0) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " 
              SET FirstName = '$first_name', LastName = '$last_name', Email = '$email', Organization = '$organization',
                  Address = '$address', PhoneNumber = '$phone_number', Birthday = '$birthday'";

        if ($password !== null) {
            $password = mysqli_real_escape_string($this->conn, $password);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query .= ", Password = '$hashed_password'";
        }

        if ($userTypeID !== null) {
            $userTypeID = mysqli_real_escape_string($this->conn, $userTypeID);
            $query .= ", UserType_id = '$userTypeID'";
        }

        $query .= " WHERE id = '$id'";

        if (mysqli_query($this->conn, $query)) {
            if ($_SESSION['user']['UserType_id'] != 1) {
                $_SESSION['user'] = [
                    'ID' => $id,
                    'FirstName' => $first_name,
                    'LastName' => $last_name,
                    'Email' => $email,
                    'Organization' => $organization,
                    'Address' => $address,
                    'PhoneNumber' => $phone_number,
                    'Birthday' => $birthday,
                    'Password' => $password !== null ? $password : $_SESSION['user']['Password'],
                    'UserType_id' => $userTypeID !== null ? $userTypeID : $_SESSION['user']['UserType_id']
                ];
            }
            return true;
        }

        return false;
    }

    public function resetPassword($email, $password){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $email = $this->conn->real_escape_string($email);

        $query = "UPDATE users SET Password = '$hashedPassword' WHERE Email = '$email'";

        return $this->conn->query($query);
    }


    public function deleteUser($userID)
    {
        $userID = intval($userID);

        $query = "DELETE FROM users WHERE ID = $userID";

        return mysqli_query($this->conn, $query);
    }



    public function getUserById($id)
    {
        $id = mysqli_real_escape_string($this->conn, $id);

        $query = "SELECT * FROM " . $this->table_name . " WHERE id = '$id' LIMIT 1";

        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }
}
