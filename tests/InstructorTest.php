<?php

use PHPUnit\Framework\TestCase;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "App/Models/Assignments.php";
require_once "App/Models/User.php";
require_once "App/Models/Forums.php";
require_once "App/Models/Groups.php";
require_once "App/Models/PageReference.php";
require_once "App/Models/Submission.php";
require 'App/Libraries/PHPMailer/PHPMailer.php';
require 'App/Libraries/PHPMailer/SMTP.php';
require 'App/Libraries/PHPMailer/Exception.php';

class InstructorTest extends TestCase
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
        $this->db->query("TRUNCATE TABLE submissions");
        $this->db->query("TRUNCATE TABLE page_reference");
    }

    protected function tearDown(): void
    {
        $this->db->close();
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

    $non_existing_email = 'nonexistent@example.com';
    
    $result = $user->resetPassword($non_existing_email, $new_password);
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

        $this->assertCount(2, $result, "There should be 2 assignments returned.");

        $this->assertEquals(1, $result[0]['ID'], "The ID of the first assignment should be 1.");
        $this->assertEquals($title1, $result[0]['Title'], "The title of the first assignment should match.");
        $this->assertEquals($description1, $result[0]['Description'], "The description of the first assignment should match.");
        $this->assertEquals($dueDate1, $result[0]['DueDate'], "The due date of the first assignment should match.");

        $this->assertEquals(2, $result[1]['ID'], "The ID of the second assignment should be 2.");
        $this->assertEquals($title2, $result[1]['Title'], "The title of the second assignment should match.");
        $this->assertEquals($description2, $result[1]['Description'], "The description of the second assignment should match.");
        $this->assertEquals($dueDate2, $result[1]['DueDate'], "The due date of the second assignment should match.");
    }



    public function testAddAssignment()
    {
        $_SESSION['user'] = ['ID' => 2]; 

        $mockObserver = $this->createMock(AssignmentObserver::class);

        $mockObserver->expects($this->once())
            ->method('update')
            ->with($this->equalTo("A new assignment 'Test Assignment' has been uploaded. Due Date: 2024-12-31."));

        $assignments = new Assignments($this->db);
        $assignments->addObserver($mockObserver);

        $title = 'Test Assignment';
        $description = 'This is a test assignment.';
        $dueDate = '2024-12-31';
        $groupID = 101;

        $result = $assignments->addAssignment($title, $description, $dueDate, $groupID);

        $this->assertTrue($result, "The assignment should be added successfully.");

        $query = $this->db->query("SELECT * FROM assignments WHERE Title = '$title'");
        $row = $query->fetch_assoc();

        $this->assertNotEmpty($row, "The assignment should be inserted into the assignments table.");
        $this->assertEquals($title, $row['Title'], "The title should match the inserted value.");
        $this->assertEquals($description, $row['Description'], "The description should match the inserted value.");
        $this->assertEquals($dueDate, $row['DueDate'], "The due date should match the inserted value.");
        $this->assertEquals($groupID, $row['groupID'], "The groupID should match the inserted value.");
    }


    public function testEditAssignment()
    {
        $title = "Test Assignment";
        $description = "This is a test assignment for editing.";
        $dueDate = "2024-12-31";
        $groupID = 101;
        $this->db->query("INSERT INTO assignments (Title, Description, DueDate, groupID) 
                      VALUES ('$title', '$description', '$dueDate', '$groupID')");

        $assignmentID = $this->db->insert_id;

        $newTitle = "Updated Test Assignment";
        $newDescription = "This is the updated description.";
        $newDueDate = "2025-01-15";
        $newGroupID = 102;

        $assignments = new Assignments($this->db);

        $result = $assignments->editAssignment($assignmentID, $newTitle, $newDescription, $newDueDate, $newGroupID);

        $this->assertTrue($result, "The assignment should be updated successfully.");

        $result = $this->db->query("SELECT Title, Description, DueDate, groupID FROM assignments WHERE ID = $assignmentID");
        $updatedAssignment = $result->fetch_assoc();

        $this->assertEquals($newTitle, $updatedAssignment['Title'], "The title should be updated.");
        $this->assertEquals($newDescription, $updatedAssignment['Description'], "The description should be updated.");
        $this->assertEquals($newDueDate, $updatedAssignment['DueDate'], "The due date should be updated.");
        $this->assertEquals($newGroupID, $updatedAssignment['groupID'], "The groupID should be updated.");
    }



    public function testDeleteAssignment()
    {
        $title = "Test Assignment for Deletion";
        $description = "This is a test assignment that will be deleted.";
        $dueDate = "2024-12-31";
        $groupID = 101;
        $this->db->query("INSERT INTO assignments (Title, Description, DueDate, groupID) 
                      VALUES ('$title', '$description', '$dueDate', '$groupID')");

        $assignmentID = $this->db->insert_id;

        $assignments = new Assignments($this->db);

        $result = $assignments->deleteAssignment($assignmentID);

        $this->assertTrue($result, "The assignment should be deleted successfully.");

        $result = $this->db->query("SELECT * FROM assignments WHERE ID = $assignmentID");

        $this->assertEquals(0, $result->num_rows, "The assignment should no longer exist in the database.");
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


    public function testGetStudentsByGroup()
    {
        $this->db->query("INSERT INTO users (FirstName, LastName, Email, UserType_id) 
                      VALUES ('John', 'Doe', 'john.doe@example.com', 3)");
        $this->db->query("INSERT INTO users (FirstName, LastName, Email, UserType_id) 
                      VALUES ('Jane', 'Smith', 'jane.smith@example.com', 3)");
        $this->db->query("INSERT INTO users (FirstName, LastName, Email, UserType_id) 
                      VALUES ('Alice', 'Brown', 'alice.brown@example.com', 2)"); 

        $this->db->query("INSERT INTO user_groups (userID, groupID) VALUES (1, 101)");
        $this->db->query("INSERT INTO user_groups (userID, groupID) VALUES (2, 101)");
        $this->db->query("INSERT INTO user_groups (userID, groupID) VALUES (3, 101)"); 

        $this->db->query("INSERT INTO groups (ID) VALUES (101)");

        $groupManager = new Groups($this->db); 

        $students = $groupManager->getStudentsByGroup(101);

        $this->assertIsArray($students, "The result should be an array.");
        $this->assertCount(2, $students, "There should be 2 students in the group.");
    }


    public function testGetAllSubmissions()
    {
        $_SESSION['user'] = ['ID' => 2]; 

        $submissionManager = new Submission($this->db); 

        $this->db->query("INSERT INTO assignments (ID, Title) VALUES 
        (101, 'Assignment 1'),
        (102, 'Assignment 2')");

        $submissionData = '{"title": "Test Submission", "content": "This is a test content."}';
        $this->db->query("INSERT INTO submissions (assignmentID, userID, submissionDate, submissionData) VALUES 
    (101, 1, '2024-12-20', '$submissionData'),
    (102, 2, '2024-12-21', '$submissionData')");

        $submissions = $submissionManager->getAllSubmissions();

        $this->assertIsArray($submissions, "The result should be an array.");

        $this->assertCount(2, $submissions, "There should be 2 submissions.");

        $this->assertEquals(1, $submissions[0]['submissionID'], "The first submission ID should be 1.");
        $this->assertEquals(101, $submissions[0]['assignmentID'], "The first submission's assignment ID should be 101.");
        $this->assertEquals(1, $submissions[0]['studentID'], "The first submission's student ID should be 1.");
        $this->assertEquals('Assignment 1', $submissions[0]['assignmentTitle'], "The first submission's assignment title should be 'Assignment 1'.");

        $this->assertEquals(2, $submissions[1]['submissionID'], "The second submission ID should be 2.");
        $this->assertEquals(102, $submissions[1]['assignmentID'], "The second submission's assignment ID should be 102.");
        $this->assertEquals(2, $submissions[1]['studentID'], "The second submission's student ID should be 2.");
        $this->assertEquals('Assignment 2', $submissions[1]['assignmentTitle'], "The second submission's assignment title should be 'Assignment 2'.");
    }


    

    private function mockResult(array $rows)
    {
        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->method('fetch_assoc')->willReturnOnConsecutiveCalls(...array_merge($rows, [null]));
        return $mockResult;
    }
}
