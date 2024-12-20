<?php

use PHPUnit\Framework\TestCase;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "App/Models/Assignments.php";
require_once "App/Models/User.php";
require_once "App/Models/Forums.php";
require_once "App/Models/Groups.php";
require_once "App/Models/PageReference.php";
require_once "App/Models/PlagiarismReport.php";
require 'App/Libraries/PHPMailer/PHPMailer.php';
require 'App/Libraries/PHPMailer/SMTP.php';
require 'App/Libraries/PHPMailer/Exception.php';

class StudentTest extends TestCase
{
    private $db;
    private $conn;
    protected function setUp(): void
    {
        $this->db = new mysqli('localhost', 'root', '', 'test_db'); 
        $this->conn = new PDO('mysql:host=localhost;dbname=test_db', 'root', '');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->db->connect_error) {
            $this->fail("Database connection failed: " . $this->db->connect_error);
        }

        $this->db->query("TRUNCATE TABLE forums");
        $this->db->query("TRUNCATE TABLE forums_messages");
        $this->db->query("TRUNCATE TABLE assignments");
        $this->db->query("TRUNCATE TABLE groups");
        $this->db->query("TRUNCATE TABLE users");
        $this->db->query("TRUNCATE TABLE user_groups");
        $this->db->query("TRUNCATE TABLE plagiarism_reports");
        $this->db->query("TRUNCATE TABLE submissions");
        $this->db->query("TRUNCATE TABLE page_reference");
    }

    protected function tearDown(): void
    {
        $this->db->close();
    }


    public function testGetAssignmentById()
    {
        $assignments = new Assignments($this->db);

        $assignmentID = 101;
        $title = "Test Assignment";
        $description = "This is a test assignment.";
        $dueDate = "2024-12-31";

        $this->conn->query("INSERT INTO assignments (ID, Title, Description, DueDate) VALUES ($assignmentID, '$title', '$description', '$dueDate')");

        $result = $assignments->getAssignmentById($assignmentID);

        $this->assertNotEmpty($result, "The function should return assignment data for the given ID");

        $this->assertEquals($assignmentID, $result['ID'], "The ID should match the inserted value");
        $this->assertEquals($title, $result['Title'], "The Title should match the inserted value");
        $this->assertEquals($description, $result['Description'], "The Description should match the inserted value");
        $this->assertEquals($dueDate, $result['DueDate'], "The DueDate should match the inserted value");
    }


    public function testGetAssignments()
{
    $assignments = new Assignments($this->db); 

    // Insert sample data into the assignments table for testing
    $title1 = "Test Assignment 1";
    $description1 = "This is the first test assignment.";
    $dueDate1 = "2024-12-31";
    $groupID1 = 101;
    $this->db->query("INSERT INTO assignments (Title, Description, DueDate, groupID) 
                        VALUES ('$title1', '$description1', '$dueDate1', '$groupID1')");

    $title2 = "Test Assignment 2";
    $description2 = "This is the second test assignment.";
    $dueDate2 = "2024-11-30";
    $groupID2 = 102;
    $this->db->query("INSERT INTO assignments (Title, Description, DueDate, groupID) 
                        VALUES ('$title2', '$description2', '$dueDate2', '$groupID2')");

    $result = $assignments->getAssignments();

    // Assertions to ensure the assignments are returned correctly
    $this->assertCount(2, $result, "There should be 2 assignments returned.");
    
    // Validate the first assignment
    $this->assertEquals(1, $result[0]['ID'], "The ID of the first assignment should be 1.");
    $this->assertEquals($title1, $result[0]['Title'], "The title of the first assignment should match.");
    $this->assertEquals($description1, $result[0]['Description'], "The description of the first assignment should match.");
    $this->assertEquals($dueDate1, $result[0]['DueDate'], "The due date of the first assignment should match.");

    // Validate the second assignment
    $this->assertEquals(2, $result[1]['ID'], "The ID of the second assignment should be 2.");
    $this->assertEquals($title2, $result[1]['Title'], "The title of the second assignment should match.");
    $this->assertEquals($description2, $result[1]['Description'], "The description of the second assignment should match.");
    $this->assertEquals($dueDate2, $result[1]['DueDate'], "The due date of the second assignment should match.");
}



    public function testCreateForum()
    {
        $forum = new Forums($this->db);

        $submissionID = 123;
        $instructorID = 456;
        $studentID = 789;

        $result = $forum->createForum($submissionID, $instructorID, $studentID);

        $this->assertTrue($result, "createForum should return true on successful insertion");

        $query = $this->db->query("SELECT * FROM forums");
        $row = $query->fetch_assoc();

        $this->assertNotEmpty($row, "A row should be inserted into the forums table");
        $this->assertEquals($submissionID, $row['SubmissionID'], "SubmissionID should match the input value");
        $this->assertEquals($instructorID, $row['InstructorID'], "InstructorID should match the input value");
        $this->assertEquals($studentID, $row['StudentID'], "StudentID should match the input value");
    }


public function testGetForumById()
{
    $studentFirstName = "John";
    $studentLastName = "Doe";
    $instructorFirstName = "Jane";
    $instructorLastName = "Smith";
    
    $this->db->query("INSERT INTO users (FirstName, LastName) 
                      VALUES ('$studentFirstName', '$studentLastName')");
    $studentID = $this->db->insert_id;

    $this->db->query("INSERT INTO users (FirstName, LastName) 
                      VALUES ('$instructorFirstName', '$instructorLastName')");
    $instructorID = $this->db->insert_id;

    $submissionID = 123; 
    $createdAt = '2024-12-19 12:00:00'; 
    $this->db->query("INSERT INTO forums (SubmissionID, StudentID, InstructorID, Createdat)
                      VALUES ($submissionID, $studentID, $instructorID, '$createdAt')");

    $forumID = $this->db->insert_id;

    $forum = new Forums($this->db); 

    $result = $forum->getForumById($forumID);

    // Assertions to ensure the forum data is retrieved correctly
    $this->assertNotNull($result, "The forum data should not be null.");
    $this->assertEquals($forumID, $result['ID'], "The forum ID should match.");
    $this->assertEquals($submissionID, $result['SubmissionID'], "The submission ID should match.");
    $this->assertEquals($studentFirstName, $result['StudentFirstName'], "The student first name should match.");
    $this->assertEquals($studentLastName, $result['StudentLastName'], "The student last name should match.");
    $this->assertEquals($instructorFirstName, $result['InstructorFirstName'], "The instructor first name should match.");
    $this->assertEquals($instructorLastName, $result['InstructorLastName'], "The instructor last name should match.");
}

    

public function testCreateForums_Messages()
{
    $senderFirstName = "John";
    $senderLastName = "Doe";
    $receiverFirstName = "Jane";
    $receiverLastName = "Smith";
    
    $this->db->query("INSERT INTO users (FirstName, LastName) 
                      VALUES ('$senderFirstName', '$senderLastName')");
    $senderID = $this->db->insert_id;

    $this->db->query("INSERT INTO users (FirstName, LastName) 
                      VALUES ('$receiverFirstName', '$receiverLastName')");
    $receiverID = $this->db->insert_id;

    $createdAt = '2024-12-19 12:00:00';
    $this->db->query("INSERT INTO forums (SubmissionID, StudentID, InstructorID, Createdat)
                      VALUES (1, $senderID, $receiverID, '$createdAt')");
    $forumID = $this->db->insert_id;

    $forum = new Forums_Messages($this->db); 

    $messageText = "This is a test message for the forum.";

    $result = $forum->createForums_Messages($forumID, $senderID, $messageText);

    $this->assertTrue($result, "The message should be inserted successfully.");
    
    $query = "SELECT * FROM forums_messages WHERE ForumID = $forumID AND SenderID = $senderID AND Messagetext = '$messageText'";
    $result = $this->db->query($query);
    $this->assertEquals(1, $result->num_rows, "The message should be present in the database.");

    $message = $result->fetch_assoc();
    $this->assertEquals($forumID, $message['ForumID'], "The forum ID should match.");
    $this->assertEquals($senderID, $message['SenderID'], "The sender ID should match.");
    $this->assertEquals($messageText, $message['Messagetext'], "The message text should match.");
    $this->assertEquals(0, $message['Isread'], "The message should initially be marked as unread.");
}



public function testSaveReport()
{
    $submissionID = 1;
    $submissionData = '{"title": "Test Submission", "content": "This is a test content."}';
    $responseAPI = '{"status":"success","details":"No plagiarism detected"}';
    $feedback = "Excellent work with no signs of plagiarism.";
    $similarityPercentage = 0;
    $grade = 95;

    $this->db->query("INSERT INTO submissions (ID, submissionData) VALUES ($submissionID, '$submissionData')");

    $reports = new PlagiarismReport($this->db); 

    $result = $reports->saveReport($submissionID, $responseAPI, $feedback, $similarityPercentage, $grade);

    $this->assertTrue($result, "The saveReport method should return true on success.");

    $query = "SELECT * FROM plagiarism_reports WHERE submissionID = $submissionID";
    $result = $this->db->query($query);

    $this->assertEquals(1, $result->num_rows, "A plagiarism report should have been created.");

    $report = $result->fetch_assoc();
    $this->assertEquals($submissionID, $report['submissionID'], "The submission ID should match.");
    $this->assertEquals($responseAPI, $report['responseAPI'], "The response API should match.");
    $this->assertEquals($feedback, $report['feedback'], "The feedback should match.");
    $this->assertEquals($similarityPercentage, $report['similarityPercentage'], "The similarity percentage should match.");
    $this->assertEquals($grade, $report['Grade'], "The grade should match.");
}

public function testGetSubmissionsByUserId()
{
    $_SESSION['user'] = ['ID' => 3]; 

    $submissionManager = new Submission($this->db); 

    $this->db->query("INSERT INTO assignments (ID, Title) VALUES 
        (101, 'Assignment 1'),
        (102, 'Assignment 2')");

    $submissionData = '{"title": "Test Submission", "content": "This is a test content."}';
        $this->db->query("INSERT INTO submissions (assignmentID, userID, submissionDate, submissionData) VALUES 
    (101, 1, '2024-12-20', '$submissionData'),
    (102, 1, '2024-12-20', '$submissionData'),
    (102, 2, '2024-12-21', '$submissionData')");

    $submissions = $submissionManager->getSubmissionsByUserId(1);

    $this->assertIsArray($submissions, "The result should be an array.");

    $this->assertCount(2, $submissions, "There should be 2 submissions for user 1.");

    $this->assertEquals(1, $submissions[0]['submissionID'], "The first submission ID should be 1.");
    $this->assertEquals(101, $submissions[0]['assignmentID'], "The first submission's assignment ID should be 101.");
    $this->assertEquals(1, $submissions[0]['studentID'], "The first submission's student ID should be 1.");

    $this->assertEquals(2, $submissions[1]['submissionID'], "The second submission ID should be 2.");
    $this->assertEquals(102, $submissions[1]['assignmentID'], "The second submission's assignment ID should be 102.");
    $this->assertEquals(1, $submissions[1]['studentID'], "The second submission's student ID should be 1.");

    $this->db->query("DELETE FROM submissions WHERE userID IN (1, 2)");
    $this->db->query("DELETE FROM assignments WHERE ID IN (101, 102)");
}

public function testCreateSubmission()
{
    $_SESSION['user'] = ['ID' => 3]; 

    $submissionManager = new Submission($this->db); 

    $assignmentID = 101;
    $userID = 1; 
    $submissionData = '{"title": "Test Submission", "content": "This is a test content."}';

    $this->db->query("INSERT INTO assignments (ID, Title) VALUES ($assignmentID, 'Test Assignment')");

    $submissionID = $submissionManager->createSubmission($assignmentID, $userID, $submissionData);

    $this->assertNotFalse($submissionID, "The submission should be created and the submission ID should be returned.");

    $query = $this->db->query("SELECT * FROM submissions WHERE ID = $submissionID");
    $submission = $query->fetch_assoc();

    $this->assertNotEmpty($submission, "The submission should exist in the database.");
    $this->assertEquals($assignmentID, $submission['assignmentID'], "The assignmentID should match the inserted value.");
    $this->assertEquals($userID, $submission['userID'], "The userID should match the inserted value.");
    $this->assertEquals($submissionData, $submission['submissionData'], "The submissionData should match the inserted value.");
    $this->assertEquals('Pending', $submission['status'], "The status should be 'Pending' by default.");
}


public function testSignup()
{
    $first_name = 'John';
    $last_name = 'Doe';
    $email = 'johndoe@example.com';
    $password = 'password123';  
    $organization = 'Test Organization';
    $address = '123 Test St, Test City';
    $phone_number = '1234567890';
    $birthday = '1990-01-01';

    $user = new User($this->db); 

    $user->first_name = $first_name;
    $user->last_name = $last_name;
    $user->email = $email;
    $user->password = $password;
    $user->organization = $organization;
    $user->address = $address;
    $user->phone_number = $phone_number;
    $user->birthday = $birthday;

    $result = $user->signup();

    $this->assertTrue($result, "The signup should be successful.");

    $query = $this->db->query("SELECT * FROM users WHERE Email = '$email'");
    $user_data = $query->fetch_assoc();

    $this->assertNotEmpty($user_data, "The user should exist in the database.");
    $this->assertEquals($first_name, $user_data['FirstName'], "The first name should match the inserted value.");
    $this->assertEquals($last_name, $user_data['LastName'], "The last name should match the inserted value.");
    $this->assertEquals($email, $user_data['Email'], "The email should match the inserted value.");
    $this->assertTrue(password_verify($password, $user_data['Password']), "The password should be hashed correctly.");
    $this->assertEquals($organization, $user_data['Organization'], "The organization should match the inserted value.");
    $this->assertEquals($address, $user_data['Address'], "The address should match the inserted value.");
    $this->assertEquals($phone_number, $user_data['PhoneNumber'], "The phone number should match the inserted value.");
    $this->assertEquals($birthday, $user_data['Birthday'], "The birthday should match the inserted value.");

    $this->assertEquals($_SESSION['user']['ID'], $user_data['ID'], "The user ID in the session should match the database.");
    $this->assertEquals($_SESSION['user']['FirstName'], $first_name, "The first name in the session should match the database.");
    $this->assertEquals($_SESSION['user']['LastName'], $last_name, "The last name in the session should match the database.");
    $this->assertEquals($_SESSION['user']['Email'], $email, "The email in the session should match the database.");
    $this->assertEquals($_SESSION['user']['Organization'], $organization, "The organization in the session should match the database.");
    $this->assertEquals($_SESSION['user']['Address'], $address, "The address in the session should match the database.");
    $this->assertEquals($_SESSION['user']['PhoneNumber'], $phone_number, "The phone number in the session should match the database.");
    $this->assertEquals($_SESSION['user']['Birthday'], $birthday, "The birthday in the session should match the database.");

    $this->db->query("DELETE FROM users WHERE Email = '$email'");
}



public function testLogin()
{
    $email = 'johndoe@example.com';
    $password = 'password123';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id)
              VALUES ('John', 'Doe', '$email', '$hashed_password', 'Test Organization', '123 Test St, Test City', '1234567890', '1990-01-01', 4)";
    $this->db->query($query);

    $user = new User($this->db); 

    $result = $user->login($email, $password);

    $this->assertIsArray($result, "The login should return user data.");
    $this->assertEquals($email, $result['Email'], "The email should match the logged-in user.");
    $this->assertEquals('John', $result['FirstName'], "The first name should match the logged-in user.");
    $this->assertEquals('Doe', $result['LastName'], "The last name should match the logged-in user.");
    
    $this->assertEquals($_SESSION['user']['ID'], $result['ID'], "The user ID in the session should match the login result.");
    $this->assertEquals($_SESSION['user']['FirstName'], 'John', "The first name in the session should match the login result.");
    $this->assertEquals($_SESSION['user']['LastName'], 'Doe', "The last name in the session should match the login result.");
    $this->assertEquals($_SESSION['user']['Email'], $email, "The email in the session should match the login result.");
    $this->assertEquals($_SESSION['user']['Organization'], 'Test Organization', "The organization in the session should match the login result.");
    $this->assertEquals($_SESSION['user']['Address'], '123 Test St, Test City', "The address in the session should match the login result.");
    $this->assertEquals($_SESSION['user']['PhoneNumber'], '1234567890', "The phone number in the session should match the login result.");
    $this->assertEquals($_SESSION['user']['Birthday'], '1990-01-01', "The birthday in the session should match the login result.");

    // Test login with incorrect password
    $incorrect_password = 'wrongpassword';
    $result = $user->login($email, $incorrect_password);

    $this->assertFalse($result, "The login should fail with incorrect password.");

    // Test login with a non-existing email
    $non_existing_email = 'nonexistent@example.com';
    $result = $user->login($non_existing_email, $password);

    $this->assertFalse($result, "The login should fail with a non-existing email.");
}


public function testEditUser()
{
    $user = new User($this->db); 

    $first_name = 'John';
    $last_name = 'Doe';
    $email = 'johndoe@example.com';
    $password = 'password123';
    $organization = 'Test Organization';
    $address = '123 Test St';
    $phone_number = '1234567890';
    $birthday = '1985-05-15';
    $user_type_id = 2;

    $query = "INSERT INTO users (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
              VALUES ('$first_name', '$last_name', '$email', '" . password_hash($password, PASSWORD_DEFAULT) . "', '$organization', '$address', '$phone_number', '$birthday', '$user_type_id')";
    mysqli_query($this->db, $query);
    $user_id = mysqli_insert_id($this->db);

    // Test case 1: Update the user successfully
    $new_first_name = 'Jane';
    $new_last_name = 'Smith';
    $new_email = 'janesmith@example.com';
    $new_organization = 'New Organization';
    $new_address = '456 New St';
    $new_phone_number = '0987654321';
    $new_birthday = '1990-01-01';
    $new_password = 'newpassword456';
    $new_user_type_id = 3;

    $result = $user->editUser($user_id, $new_first_name, $new_last_name, $new_email, $new_organization, $new_address, $new_phone_number, $new_birthday, $new_password, $new_user_type_id);

    $this->assertTrue($result, "User should be updated successfully.");

    $query = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($this->db, $query);
    $updated_user = mysqli_fetch_assoc($result);

    $this->assertEquals($new_first_name, $updated_user['FirstName']);
    $this->assertEquals($new_last_name, $updated_user['LastName']);
    $this->assertEquals($new_email, $updated_user['Email']);
    $this->assertEquals($new_organization, $updated_user['Organization']);
    $this->assertEquals($new_address, $updated_user['Address']);
    $this->assertEquals($new_phone_number, $updated_user['PhoneNumber']);
    $this->assertEquals($new_birthday, $updated_user['Birthday']);
    $this->assertTrue(password_verify($new_password, $updated_user['Password']));
    $this->assertEquals($new_user_type_id, $updated_user['UserType_id']);

    // Test case 2: Try to update with an already existing email
    $existing_email = 'existingemail@example.com';
    $query = "INSERT INTO users (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
              VALUES ('Existing', 'User', '$existing_email', '" . password_hash($password, PASSWORD_DEFAULT) . "', 'Existing Org', '789 Existing St', '1122334455', '1992-02-02', 2)";
    mysqli_query($this->db, $query);

    $result = $user->editUser($user_id, $new_first_name, $new_last_name, $existing_email, $new_organization, $new_address, $new_phone_number, $new_birthday, $new_password, $new_user_type_id);

    $this->assertFalse($result, "User update should fail because the email already exists.");
}

public function testResetPassword()
{
    $user = new User($this->db); 

    $email = 'johndoe@example.com';
    $original_password = 'originalPassword123';
    $new_password = 'newPassword456';

    $hashed_password = password_hash($original_password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
              VALUES ('John', 'Doe', '$email', '$hashed_password', 'Test Org', '123 Test St', '1234567890', '1985-05-15', 2)";
    mysqli_query($this->db, $query);

    $result = $user->resetPassword($email, $new_password);
    
    $this->assertTrue($result, "Password reset should be successful.");

    $query = "SELECT Password FROM users WHERE Email = '$email'";
    $result = mysqli_query($this->db, $query);
    $updated_user = mysqli_fetch_assoc($result);

    $this->assertTrue(password_verify($new_password, $updated_user['Password']), "The new password should be hashed and match the entered password.");

    // Test case 2: Try to reset the password for a non-existing user
    $non_existing_email = 'nonexistent@example.com';
    
    $result = $user->resetPassword($non_existing_email, $new_password);
}


    private function mockResult(array $rows)
    {
        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->method('fetch_assoc')->willReturnOnConsecutiveCalls(...array_merge($rows, [null]));
        return $mockResult;
    }
}
