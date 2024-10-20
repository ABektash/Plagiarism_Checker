<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>

    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/StudentManagement.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/modal.css"> <!-- Link to new modal CSS -->
</head>

<body class="StudentManagementBody">

    <?php include 'inc/header.php'; ?>

    <main class="StudentManagementMain">

        <section class="Groups">
            <div class="Group-Container">
                <div class="Left-Group-Container">
                    <h2>Group:</h2>

                    <select name="Group Number" class="Group-Selection">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>

                <div class="Right-Group-Container">
                    <button class="create-group-btn">Create Group</button>
                    <button class="edit-group-btn">Edit Group</button>
                    <button class="delete-group-btn">Delete Group</button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Profile</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>03242</td>
                        <td>Ahmed Mohamed</td>
                        <td>ghazouly@gmail.com</td>
                        <td><button class="View-Profile-btn">View</button></td>
                        <td><button class="Edit-std-btn">Edit</button> <button class="Delete-std-btn">Delete</button></td>
                    </tr>
                    <tr>
                        <td>00106</td>
                        <td>Ammar Bektash</td>
                        <td>Abektash@gmail.com</td>
                        <td><button class="View-Profile-btn">View</button></td>
                        <td><button class="Edit-std-btn">Edit</button> <button class="Delete-std-btn">Delete</button></td>
                    </tr>
                </tbody>
            </table>

        </section>

    </main>

    <?php include 'inc/footer.php'; ?>

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
                <p id="nameError" style="color: red; display: none;">Name cannot be empty.</p> <!-- Name error message -->
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

    <script>
        let rowToDelete; // To store the row to be deleted
        let rowToEdit;   // To store the row to be edited

        // Select all the delete and edit buttons
        const deleteButtons = document.querySelectorAll('.Delete-std-btn');
        const editButtons = document.querySelectorAll('.Edit-std-btn');
        const deleteModal = document.getElementById("deleteModal");
        const editModal = document.getElementById("editModal");
        const yesBtn = document.getElementById("yes-btn");
        const noBtn = document.getElementById("no-btn");
        const saveBtn = document.getElementById("save-btn");
        const cancelEditBtn = document.getElementById("cancel-edit-btn");

        const editNameInput = document.getElementById("editName");
        const editEmailInput = document.getElementById("editEmail");

        const nameError = document.getElementById("nameError");
        const emailError = document.getElementById("emailError");

        // Validation function for email format
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Add event listeners to delete buttons
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                rowToDelete = button.closest('tr');  // Get the row to delete
                deleteModal.style.display = "block";  // Show the modal
            });
        });

        // When the user clicks on Yes, delete the row
        yesBtn.addEventListener('click', function () {
            rowToDelete.remove();  // Remove the row
            deleteModal.style.display = "none";  // Hide the modal
        });

        // When the user clicks on No, close the modal
        noBtn.addEventListener('click', function () {
            deleteModal.style.display = "none";
        });

        // Add event listeners to edit buttons
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                rowToEdit = button.closest('tr');  // Get the row to edit

                // Pre-fill the form with the current student data
                editNameInput.value = rowToEdit.cells[1].textContent;
                editEmailInput.value = rowToEdit.cells[2].textContent;

                // Reset error messages
                nameError.style.display = 'none';
                emailError.style.display = 'none';

                editModal.style.display = "block";  // Show the edit modal
            });
        });

        // When the user clicks on Save, validate inputs and update the row if valid
        saveBtn.addEventListener('click', function () {
            let isValid = true;

            // Validate Name (cannot be empty)
            if (editNameInput.value.trim() === "") {
                nameError.style.display = 'block';
                isValid = false;
            } else {
                nameError.style.display = 'none';
            }

            // Validate Email (must be in correct format)
            if (!validateEmail(editEmailInput.value)) {
                emailError.style.display = 'block';
                isValid = false;
            } else {
                emailError.style.display = 'none';
            }

            // If validation passes, update the row with the new data
            if (isValid) {
                rowToEdit.cells[1].textContent = editNameInput.value;  // Update student name
                rowToEdit.cells[2].textContent = editEmailInput.value; // Update student email
                editModal.style.display = "none";  // Hide the modal
            }
        });

        // When the user clicks on Cancel, close the modal
        cancelEditBtn.addEventListener('click', function () {
            editModal.style.display = "none";
        });

        // Close the modal when the user clicks outside of it
        window.addEventListener('click', function (event) {
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
        });
    </script>

</body>

</html>
