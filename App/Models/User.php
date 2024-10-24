<?php

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
    public $user_type_id = 4;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $first_name = mysqli_real_escape_string($this->conn, $this->first_name);
        $last_name = mysqli_real_escape_string($this->conn, $this->last_name);
        $email = mysqli_real_escape_string($this->conn, $this->email);
        $password = mysqli_real_escape_string($this->conn, $this->password);
        $organization = mysqli_real_escape_string($this->conn, $this->organization);
        $address = mysqli_real_escape_string($this->conn, $this->address);
        $phone_number = mysqli_real_escape_string($this->conn, $this->phone_number);
        $birthday = mysqli_real_escape_string($this->conn, $this->birthday);
        $user_type_id = (int)$this->user_type_id;

        $check_email_query = "SELECT Email FROM " . $this->table_name . " WHERE Email = '$email'";
        $result = mysqli_query($this->conn, $check_email_query);

        if (mysqli_num_rows($result) > 0) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
                  VALUES ('$first_name', '$last_name', '$email', '$password', '$organization', '$address', '$phone_number', '$birthday', '$user_type_id')";

        if (mysqli_query($this->conn, $query)) {
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
}
