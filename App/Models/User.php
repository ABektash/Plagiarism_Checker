<?php

session_start();

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
        $password = mysqli_real_escape_string($this->conn, $this->password);
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

        $query = "INSERT INTO " . $this->table_name . " 
                  (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
                  VALUES ('$first_name', '$last_name', '$email', '$password', '$organization', '$address', '$phone_number', '$birthday', '$user_type_id')";

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
                'UserType_id' => $user_type_id
            ];
            return true;
        }

        return false;
    }


    public function login($email, $password)
    {
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = mysqli_real_escape_string($this->conn, $password);

        $query = "SELECT * FROM " . $this->table_name . " WHERE Email = '$email' LIMIT 1";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if ($password === $user_data['Password']) {
                return $user_data;
            } else {
                return false;
            }
        }

        return false;
    }




    public function editUser($id, $first_name, $last_name, $email, $organization, $address, $phone_number, $birthday)
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
                      Address = '$address', PhoneNumber = '$phone_number', Birthday = '$birthday' 
                  WHERE id = '$id'";

        if (mysqli_query($this->conn, $query)) {
            $_SESSION['user'] = [
                'ID' => $id,
                'FirstName' => $first_name,
                'LastName' => $last_name,
                'Email' => $email,
                'Organization' => $organization,
                'Address' => $address,
                'PhoneNumber' => $phone_number,
                'Birthday' => $birthday,
                'UserType_id' => $_SESSION['user']['UserType_id'] 
            ];
            return true; 
        }

        return false; 
    }
}
