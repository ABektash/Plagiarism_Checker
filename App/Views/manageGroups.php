<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <title>Plagiarism Detection</title>

    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageGroups.css">

</head>

<body>
    <?php include 'inc/sidebar.php'; ?>

    <section id="content">
        <?php include 'inc/navbar.php'; ?>

        <main>
            <div class="Group-Container">
          
          
            <div class="Left-Group-Container">
            <h2 class="Group-Selection-Title">Group:</h2>

        <select name="Group Number" class="Group-Selection">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
        </div>
        <div class="Right-Group-Container">
                    <button class="add-std-btn">Add Student</button>
                    <button class="create-group-btn">Add Group</button>
                    <button class="delete-group-btn">Delete Group</button>
                </div>

        </div>
        <div class="Group-Container">
                <div class="Left-Group-Container">
                <h2 class="Group-Selection-Title">Instructors:</h2>

                <select name="Instructor" class="Instructor-Selection">
                <option value="JohnDoe">John Doe</option>
                <option value="JaneSmith">Jane Smith</option>
                <option value="MarkJohnson">Mark Johnson</option>
                <option value="EmilyDavis">Emily Davis</option>
                </select>

                </div>

                <div class="Right-Group-Container">
                    <button class="add-instructor-btn">Add Instructor</button>
                    <button class="remove-instructor-btn">Remove Instructor</button>
                </div>
            </div>

            <div class="Table-Container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>03242</td>
                        <td>Ahmed Mohamed</td>
                        <td>ghazouly@gmail.com</td>
                        <td><button class="View-Profile-btn">View</button></td>
                        <td><button class="Edit-std-btn">Edit</button> <button class="Delete-std-btn">Remove</button></td>
                        </tr>
                        <tr>
                        <td>00106</td>
                        <td>Ammar Bektash</td>
                        <td>Abektash@gmail.com</td>
                        <td><button class="View-Profile-btn">View</button></td>
                        <td><button class="Edit-std-btn">Edit</button> <button class="Delete-std-btn">Remove</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </section>


    <!-- Add Instructor Modal -->
<div id="addInstructorModal" class="modal">
    <div class="modal-content">
        <h2>Add Instructor</h2>
        <form id="addInstructorForm">
            <label for="instructorName">Name:</label>
            <input type="text" id="instructorName" name="instructorName" required>
            <p id="instructorNameError" style="color: red; display: none;">Please enter a valid name.</p> <!-- Name error message -->
            <br>
            <button type="button" id="addInstructorSaveBtn">Add</button>
            <button type="button" id="cancelAddInstructorBtn">Cancel</button>
        </form>
    </div>
</div>


<!-- Remove Instructor Confirmation Modal -->
<div id="removeInstructorModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to remove this instructor?</p>
        <button id="confirmRemoveInstructorBtn">Yes</button>
        <button id="cancelRemoveInstructorBtn">No</button>
    </div>
</div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this student?</p>
            <button id="yes-btn">Yes</button>
            <button id="no-btn">No</button>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Edit Student</h2>
            <form id="editForm">
                <label for="editName">Name:</label>
                <input type="text" id="editName" name="editName" required>
                <p id="nameError" style="color: red; display: none;">Please enter a valid name.</p> <!-- Name error message -->
                <br>
                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="editEmail" required>
                <p id="emailError" style="color: red; display: none;">Please enter a valid email address.</p> <!-- Email error message -->
                <br>
                <button type="button" id="save-btn">Save</button>
                <button type="button" id="cancel-edit-btn">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Add Student</h2>
            <form id="addForm">
                <label for="addName">Name:</label>
                <input type="text" id="addName" name="addName" required>
                <p id="addNameError" style="color: red; display: none;">Please enter a valid name.</p> <!-- Name error message -->
                <br>
                <label for="addEmail">Email:</label>
                <input type="email" id="addEmail" name="addEmail" required>
                <p id="addEmailError" style="color: red; display: none;">Please enter a valid email address.</p> <!-- Email error message -->
                <p id="duplicateEmailError" style="color: red; display: none;">This email already exists.</p> <!-- Duplicate email error message -->
                <br>
                <button type="button" id="add-save-btn">Add</button>
                <button type="button" id="cancel-add-btn">Cancel</button>
            </form>
        </div>
    </div>

    <script src="/Plagiarism_Checker/public/assets/js/manageGroups.js"></script>
</body>

</html>
