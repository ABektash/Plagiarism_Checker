<?php

use PHPUnit\Framework\TestCase;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "App/Models/Assignments.php";
require_once "App/Models/User.php";
require_once "App/Models/Forums.php";
require_once "App/Models/PageReference.php";
require_once "App/Models/Groups.php";
require_once "App/Models/Submission.php";
require 'App/Libraries/PHPMailer/PHPMailer.php';
require 'App/Libraries/PHPMailer/SMTP.php';
require 'App/Libraries/PHPMailer/Exception.php';

class AdminTest extends TestCase
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

        $incorrect_password = 'wrongpassword';
        $result = $user->login($email, $incorrect_password);

        $this->assertFalse($result, "The login should fail with incorrect password.");

        $non_existing_email = 'nonexistent@example.com';
        $result = $user->login($non_existing_email, $password);

        $this->assertFalse($result, "The login should fail with a non-existing email.");

        $this->db->query("DELETE FROM users WHERE Email = '$email'");
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



    public function testAddAssignment()
    {
        $_SESSION['user'] = ['ID' => 1]; 

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




    public function testDeleteForum()
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

        $result = $forum->deleteForum($forumID);

        $this->assertTrue($result, "The forum deletion should return true.");

        $query = "SELECT * FROM forums WHERE ID = $forumID";
        $deletedForum = $this->db->query($query);

        $this->assertEquals(0, $deletedForum->num_rows, "The forum should be deleted.");
    }


    public function testGetAllForums()
    {
        $instructorFirstName = "John";
        $instructorLastName = "Doe";
        $studentFirstName = "Jane";
        $studentLastName = "Smith";

        $this->db->query("INSERT INTO users (FirstName, LastName) 
                      VALUES ('$instructorFirstName', '$instructorLastName')");
        $instructorID = $this->db->insert_id;

        $this->db->query("INSERT INTO users (FirstName, LastName) 
                      VALUES ('$studentFirstName', '$studentLastName')");
        $studentID = $this->db->insert_id;

        $assignmentTitle = "Math Assignment";
        $this->db->query("INSERT INTO assignments (Title) 
                      VALUES ('$assignmentTitle')");
        $assignmentID = $this->db->insert_id;

        $submissionDate = '2024-12-19 12:00:00';
        $submissionData = '{"title": "Test Submission", "content": "This is a test content."}'; 
        $this->db->query("INSERT INTO submissions (assignmentID, submissionData, submissionDate) 
                      VALUES ($assignmentID, '$submissionData', '$submissionDate')");
        $submissionID = $this->db->insert_id;

        $createdAt = '2024-12-19 12:00:00';
        $this->db->query("INSERT INTO forums (SubmissionID, StudentID, InstructorID, Createdat)
                      VALUES ($submissionID, $studentID, $instructorID, '$createdAt')");
        $forumID = $this->db->insert_id;

        $sentAt = '2024-12-19 12:30:00';
        $this->db->query("INSERT INTO forums_messages (ForumID, Sentat)
                      VALUES ($forumID, '$sentAt')");

        $forum = new Forums($this->db); 

        $result = $forum->getAllForums($instructorID);

        $this->assertNotEmpty($result, "The forums data should not be empty.");

        $this->assertEquals($forumID, $result[0]['ForumID'], "The forum ID should match.");
        $this->assertEquals($submissionID, $result[0]['SubmissionID'], "The submission ID should match.");
        $this->assertEquals($instructorFirstName . ' ' . $instructorLastName, $result[0]['InstructorName'], "The instructor's full name should match.");
        $this->assertEquals($studentFirstName . ' ' . $studentLastName, $result[0]['StudentName'], "The student's full name should match.");
        $this->assertEquals($assignmentTitle, $result[0]['AssignmentTitle'], "The assignment title should match.");
        $this->assertEquals($submissionDate, $result[0]['SubmissionTime'], "The submission time should match.");
    }


    public function testGetAvailableGroups()
    {
        $this->db->query("INSERT INTO groups (ID) VALUES (1)");
        $this->db->query("INSERT INTO groups (ID) VALUES (2)");
        $this->db->query("INSERT INTO groups (ID) VALUES (3)");

        $group = new Groups($this->db); 

        $groups = $group->getAvailableGroups();

        $this->assertIsArray($groups, "The result should be an array.");
        $this->assertCount(3, $groups, "There should be 3 groups in the result.");

        $expectedGroupIds = [1, 2, 3];
        foreach ($groups as $index => $group) {
            $this->assertEquals($expectedGroupIds[$index], $group['group_id'], "The group_id should match.");
        }
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

    public function testGetInstructorsByGroup()
    {
        $this->db->query("INSERT INTO users (FirstName, LastName, Email, UserType_id) 
                      VALUES ('John', 'Doe', 'john.doe@example.com', 2)");
        $this->db->query("INSERT INTO users (FirstName, LastName, Email, UserType_id) 
                      VALUES ('Jane', 'Smith', 'jane.smith@example.com', 2)");
        $this->db->query("INSERT INTO users (FirstName, LastName, Email, UserType_id) 
                      VALUES ('Alice', 'Brown', 'alice.brown@example.com', 3)"); 

        $this->db->query("INSERT INTO user_groups (userID, groupID) VALUES (1, 101)");
        $this->db->query("INSERT INTO user_groups (userID, groupID) VALUES (2, 101)");
        $this->db->query("INSERT INTO user_groups (userID, groupID) VALUES (3, 101)"); 

        $this->db->query("INSERT INTO groups (ID) VALUES (101)");

        $groupManager = new Groups($this->db); 

        $students = $groupManager->getInstructorsByGroup(101);

        $this->assertIsArray($students, "The result should be an array.");
        $this->assertCount(2, $students, "There should be 2 students in the group.");
    }


    public function testAddStudentToGroup()
    {
        $studentFirstName = "Test";
        $studentLastName = "Student";
        $studentEmail = "teststudent@example.com";
        $this->db->query("INSERT INTO users (FirstName, LastName, Email, UserType_id) 
                      VALUES ('$studentFirstName', '$studentLastName', '$studentEmail', 3)");
        $studentID = $this->db->insert_id;

        $this->db->query("INSERT INTO groups (ID) VALUES (NULL)"); 
        $groupID = $this->db->insert_id;

        $groups = new Groups($this->conn); 
        $result = $groups->addStudentToGroup($studentID, $groupID);

        $this->assertTrue($result, "The student should be successfully added to the group.");

        $query = "SELECT * FROM user_groups WHERE userID = $studentID AND groupID = $groupID";
        $checkResult = $this->db->query($query);
        $this->assertEquals(1, $checkResult->num_rows, "The student should exist in the group.");
    }

    public function testAddInstructorToGroup()
    {
        $instructorFirstName = "Test";
        $instructorLastName = "Instructor";
        $instructorEmail = "testinstructor@example.com";
        $this->db->query("INSERT INTO users (FirstName, LastName, Email, UserType_id) 
                      VALUES ('$instructorFirstName', '$instructorLastName', '$instructorEmail', 3)");
        $instructorID = $this->db->insert_id;

        $this->db->query("INSERT INTO groups (ID) VALUES (NULL)"); 
        $groupID = $this->db->insert_id;

        $groups = new Groups($this->conn); 
        $result = $groups->addInstructorToGroup($instructorID, $groupID);

        $this->assertTrue($result, "The instructor should be successfully added to the group.");

        $query = "SELECT * FROM user_groups WHERE userID = $instructorID AND groupID = $groupID";
        $checkResult = $this->db->query($query);
        $this->assertEquals(1, $checkResult->num_rows, "The instructor should exist in the group.");
    }


    public function testCreateGroup()
    {
        $groups = new Groups($this->db); 

        $groupID = $groups->createGroup();

        $this->assertNotFalse($groupID, "The group ID should not be false on successful creation.");
        $this->assertIsInt($groupID, "The returned group ID should be an integer.");

        $query = "SELECT * FROM groups WHERE ID = $groupID";
        $result = $this->db->query($query);

        $this->assertEquals(1, $result->num_rows, "The newly created group should exist in the database.");
    }


    public function testDeleteGroup()
    {
        $this->db->query("INSERT INTO groups () VALUES ()");
        $groupID = $this->db->insert_id;

        $this->db->query("INSERT INTO user_groups (userID, groupID) VALUES (1, $groupID)");
        $this->db->query("INSERT INTO user_groups (userID, groupID) VALUES (2, $groupID)");

        $groupResult = $this->db->query("SELECT * FROM groups WHERE ID = $groupID");
        $this->assertEquals(1, $groupResult->num_rows, "The group should exist before deletion.");

        $userGroupResult = $this->db->query("SELECT * FROM user_groups WHERE groupID = $groupID");
        $this->assertEquals(2, $userGroupResult->num_rows, "There should be 2 user-group associations before deletion.");

        $groups = new Groups($this->db); 

        $result = $groups->deleteGroup($groupID);

        $this->assertTrue($result, "The deleteGroup method should return true on success.");

        $groupResult = $this->db->query("SELECT * FROM groups WHERE ID = $groupID");
        $this->assertEquals(0, $groupResult->num_rows, "The group should no longer exist after deletion.");

        $userGroupResult = $this->db->query("SELECT * FROM user_groups WHERE groupID = $groupID");
        $this->assertEquals(0, $userGroupResult->num_rows, "There should be no user-group associations after deletion.");
    }

    public function testGetAllSubmissions()
    {
        $_SESSION['user'] = ['ID' => 1]; 

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


    public function testDeleteSubmission()
    {
        $_SESSION['user'] = ['ID' => 1]; 

        $submissionManager = new Submission($this->db); 

        $submissionData = '{"title": "Test Submission", "content": "This is a test content."}';
        $this->db->query("INSERT INTO assignments (ID, Title) VALUES (101, 'Test Assignment')"); 
        $this->db->query("INSERT INTO submissions (assignmentID, userID, submissionDate, status, submissionData) 
                      VALUES (101, 1, '2024-12-20', 'submitted', '$submissionData')");

        $result = $this->db->query("SELECT ID FROM submissions WHERE userID = 1 AND assignmentID = 101 LIMIT 1");
        $submission = $result->fetch_assoc();
        $submissionID = $submission['ID'];

        $result = $submissionManager->deleteSubmission($submissionID);

        $this->assertTrue($result, "The submission should be deleted successfully.");

        $query = $this->db->query("SELECT ID FROM submissions WHERE ID = $submissionID");
        $this->assertEmpty($query->fetch_assoc(), "The submission should no longer exist in the database.");

        $this->db->query("DELETE FROM assignments WHERE ID = 101");
    }



    public function testAddUser()
    {
        $first_name = 'John';
        $last_name = 'Doe';
        $email = 'johndoe@example.com';
        $password = 'password123'; 
        $organization = 'Test Organization';
        $address = '123 Test St, Test City';
        $phone_number = '1234567890';
        $birthday = '1990-01-01';
        $user_type_id = 4; 

        $user = new User($this->db); 

        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = $password;
        $user->organization = $organization;
        $user->address = $address;
        $user->phone_number = $phone_number;
        $user->birthday = $birthday;
        $user->user_type_id = $user_type_id;

        $result = $user->addUser();

        $this->assertTrue($result, "The user should be successfully added.");

        $check_query = "SELECT * FROM users WHERE Email = '$email'";
        $check_result = mysqli_query($this->db, $check_query);
        $this->assertEquals(1, mysqli_num_rows($check_result), "The user should exist in the database after being added.");

        $result = $user->addUser();

        $this->assertFalse($result, "The user should not be added due to duplicate email.");
    }

    public function testGetAllUsers()
    {
        $user = new User($this->db); 

        $result = $user->getAllUsers();

        $this->assertEmpty($result, "The result should be an empty array when no users are present.");

        $first_name = 'Jane';
        $last_name = 'Doe';
        $email = 'janedoe@example.com';
        $password = 'password123';
        $organization = 'Test Organization';
        $address = '123 Test St, Test City';
        $phone_number = '1234567890';
        $birthday = '1990-01-01';
        $user_type_id = 4;

        $query = "INSERT INTO users (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
              VALUES ('$first_name', '$last_name', '$email', '" . password_hash($password, PASSWORD_DEFAULT) . "', '$organization', '$address', '$phone_number', '$birthday', '$user_type_id')";
        mysqli_query($this->db, $query);

        $result = $user->getAllUsers();

        $this->assertNotEmpty($result, "The result should contain users when there are users in the database.");
        $this->assertIsArray($result, "The result should be an array.");
        $this->assertGreaterThanOrEqual(1, count($result), "There should be at least one user in the result.");
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

        $existing_email = 'existingemail@example.com';
        $query = "INSERT INTO users (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
              VALUES ('Existing', 'User', '$existing_email', '" . password_hash($password, PASSWORD_DEFAULT) . "', 'Existing Org', '789 Existing St', '1122334455', '1992-02-02', 2)";
        mysqli_query($this->db, $query);

        $result = $user->editUser($user_id, $new_first_name, $new_last_name, $existing_email, $new_organization, $new_address, $new_phone_number, $new_birthday, $new_password, $new_user_type_id);

        $this->assertFalse($result, "User update should fail because the email already exists.");
    }




    public function testDeleteUser()
    {
        $user = new User($this->db); 

        $user_id_to_delete = 1;

        $query = "INSERT INTO users (FirstName, LastName, Email, Password, Organization, Address, PhoneNumber, Birthday, UserType_id) 
              VALUES ('John', 'Doe', 'johndoe@example.com', 'hashedpassword', 'Test Org', '123 Test St', '1234567890', '1985-05-15', 2)";
        mysqli_query($this->db, $query);
        $inserted_user_id = mysqli_insert_id($this->db);

        $result = $user->deleteUser($inserted_user_id);

        $this->assertTrue($result, "User should be deleted successfully.");

        $query = "SELECT * FROM users WHERE ID = $inserted_user_id";
        $result = mysqli_query($this->db, $query);

        $this->assertEquals(mysqli_num_rows($result), 0, "The user should not exist after deletion.");

        $non_existing_user_id = 9999;

        $result = $user->deleteUser($non_existing_user_id);
    }


    public function testManagePermissions()
    {
        $userTypeID = 1; 
        $pageID = 5; 

        $userPermissions = new PageReference($this->db);

        $userPermissions->addPageToParent($userTypeID, $pageID);

        $pages = $userPermissions->getPagesByParentID($userTypeID);
        $this->assertTrue(in_array($pageID, $pages), "Page should be added to the user type.");

        $userPermissions->deletePagesByParentID($userTypeID);

        $pages = $userPermissions->getPagesByParentID($userTypeID);
        $this->assertEmpty($pages, "Pages should be deleted for the user type.");
    }


    private function mockResult(array $rows)
    {
        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->method('fetch_assoc')->willReturnOnConsecutiveCalls(...array_merge($rows, [null]));
        return $mockResult;
    }
}
