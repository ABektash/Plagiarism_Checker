<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>

    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageGroupInsturctor.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>

</head>

<body>

    <?php include 'inc/header.php'; ?>

    <div class="Group-Container">
        <div class="Left-Group-Container">
            <h2 class="Group-Selection-Title">Group:</h2>

            <form method="POST" action="">
                <select name="GroupNumber" class="Group-Selection" onchange="this.form.submit()">
                    <?php
                    $userID = $_SESSION['user']['ID'];

                    $response = file_get_contents(redirect("ManageGroupInsturctor/getGroups") . "?userID=" . $userID);
                    $groups = json_decode($response, true);

                    if (is_array($groups) && !empty($groups)) {
                        foreach ($groups as $groupID) {
                            echo "<option value='$groupID'" . (isset($_POST['GroupNumber']) && $_POST['GroupNumber'] == $groupID ? ' selected' : '') . ">$groupID</option>";
                        }
                    } else {
                        echo "<option>No groups available</option>";
                    }

                    if (!empty($groups))
                        if (!isset($_POST['GroupNumber'])) {
                            $_POST['GroupNumber'] = $groups[0];
                        }
                    ?>
                </select>
            </form>
        </div>

        <div class="Right-Group-Container">
            <!-- <a class="add-std-btn">Add Student</a>
                    <a class="create-group-btn">Add Group</a>
                    <a class="delete-group-btn">Delete Group</a> -->
        </div>
    </div>

    <main class="StudentManagementBody">

        <section>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Profile</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_POST['GroupNumber'])) {
                        $userID = $_SESSION['user']['ID'];
                        $groupID = $_POST['GroupNumber'];

                        $response = file_get_contents(redirect("ManageGroupInsturctor/getMembers") . "?userID=" . $userID . "&groupID=" . $groupID);
                        $members = json_decode($response, true);

                        if (is_array($members) && !empty($members)) {

                            foreach ($members as $member) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($member['UserID']) . "</td>";
                                echo "<td>" . htmlspecialchars($member['FirstName']) . " " . htmlspecialchars($member['LastName']) . "</td>";
                                echo "<td>" . htmlspecialchars($member['Email']) . "</td>";
                                echo "<td><a class='a-link' href='" . redirect("profile/student/" . $member['UserID']) . "'><i class='bx bxs-user'></a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No members found for this group.</td></tr>";
                        }
                    }

                    ?>

                </tbody>
            </table>

        </section>

        <!-- Delete Confirmation Modal
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <p>Are you sure you want to delete this student?</p>
                <a id="yes-btn">Yes</a>
                <a id="no-btn">No</a>
            </div>
        </div>

        Edit Student Modal 
        <div id="editModal" class="modal">
            <div class="modal-content">
                <h2>Edit Student</h2>
                <form id="editForm">
                    <label for="editName">Name:</label>
                    <input type="text" id="editName" name="editName" required>
                    <p id="nameError" style="color: red; display: none;">Please enter a valid name.</p> Name error message 
                    <br>
                    <label for="editEmail">Email:</label>
                    <input type="email" id="editEmail" name="editEmail" required>
                    <p id="emailError" style="color: red; display: none;">Please enter a valid email address.</p>  Email error message 
                    <br>
                    <a type="a" id="save-btn">Save</a>
                    <a type="a" id="cancel-edit-btn">Cancel</a>
                </form>
            </div>
        </div>

       Add Student Modal 
        <div id="addModal" class="modal">
            <div class="modal-content">
                <h2>Add Student</h2>
                <form id="addForm">
                    <label for="addName">Name:</label>
                    <input type="text" id="addName" name="addName" required>
                    <p id="addNameError" style="color: red; display: none;">Please enter a valid name.</p>  Name error message 
                    <br>
                    <label for="addEmail">Email:</label>
                    <input type="email" id="addEmail" name="addEmail" required>
                    <p id="addEmailError" style="color: red; display: none;">Please enter a valid email address.</p>  Email error message 
                    <p id="duplicateEmailError" style="color: red; display: none;">This email already exists.</p> Duplicate email error message 
                    <br>
                    <a type="a" id="add-save-btn">Add</a>
                    <a type="a" id="cancel-add-btn">Cancel</a>
                </form>
            </div>
        </div> -->


    </main>

    <?php include 'inc/footer.php'; ?>

</body>

<script src="/Plagiarism_Checker/public\assets\js\manageStudent.js"></script>

</html>