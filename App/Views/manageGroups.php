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
            <div class="head-title">
                <h1>Groups</h1>
            </div>
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
            </div>
        </main>
    </section>

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

        // Validation function to check if the name contains only letters and spaces
        function validateName(name) {
            const nameRegex = /^[A-Za-z\s]+$/;
            return nameRegex.test(name);
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
            deleteModal.style.display = "none";   
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

            // Validate Name (cannot be empty and must contain only letters)
            if (editNameInput.value.trim() === "" || !validateName(editNameInput.value)) {
                nameError.style.display = 'block';
                nameError.textContent = "Please enter a valid name.";
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
                editModal.style.display = "none";   
            }
        });

        // Cancel edit button
        cancelEditBtn.addEventListener('click', function () {
            editModal.style.display = "none";   
        });

        // Add Student Modal functionality
        const addModal = document.getElementById("addModal");
        const addSaveBtn = document.getElementById("add-save-btn");
        const cancelAddBtn = document.getElementById("cancel-add-btn");

        const addNameInput = document.getElementById("addName");
        const addEmailInput = document.getElementById("addEmail");

        const addNameError = document.getElementById("addNameError");
        const addEmailError = document.getElementById("addEmailError");
        const duplicateEmailError = document.getElementById("duplicateEmailError");

        // Show Add Student Modal
        document.querySelector('.add-std-btn').addEventListener('click', function () {
            addModal.style.display = "block";
        });

        // Cancel Add Student
        cancelAddBtn.addEventListener('click', function () {
            addModal.style.display = "none";
        });

        // Function to check for duplicate emails
        function isDuplicateEmail(email) {
            const rows = document.querySelectorAll('table tbody tr');
            
            if (rows.length === 0) return false;  // No rows exist, so no duplicates

            for (let row of rows) {
                // Ensure row and cells exist before accessing cells[2]
                if (row && row.cells[2].textContent === email) {
                    return true; // Duplicate email found
                }
            }
            return false; // No duplicates
        }

        // Add new student to the table
        addSaveBtn.addEventListener('click', function () {
            let isValid = true;

            // Validate Name (cannot be empty and must contain only letters)
            if (addNameInput.value.trim() === "" || !validateName(addNameInput.value)) {
                addNameError.style.display = 'block';
                addNameError.textContent = "Please enter a valid name.";
                isValid = false;
            } else {
                addNameError.style.display = 'none';
            }

            // Validate Email (must be in correct format)
            if (!validateEmail(addEmailInput.value)) {
                addEmailError.style.display = 'block';
                isValid = false;
            } else if (isDuplicateEmail(addEmailInput.value)) {
                duplicateEmailError.style.display = 'block';  // Show duplicate email error
                isValid = false;
            } else {
                addEmailError.style.display = 'none';
                duplicateEmailError.style.display = 'none';
            }

            // If validation passes, add a new row to the table
            if (isValid) {
                const tableBody = document.querySelector('table tbody');
                
                // Create a new row
                const newRow = document.createElement('tr');
                
                // Generate a new student ID (for simplicity, using a random number)
                const newId = Math.floor(Math.random() * 100000).toString().padStart(5, '0');
                
                // Set the row's HTML with the new student data
                newRow.innerHTML = `
                    <td>${newId}</td>
                    <td>${addNameInput.value}</td>
                    <td>${addEmailInput.value}</td>
                    <td><button class="View-Profile-btn">View</button></td>
                    <td>
                        <button class="Edit-std-btn">Edit</button>
                        <button class="Delete-std-btn">Delete</button>
                    </td>
                `;

                // Append the new row to the table body
                tableBody.appendChild(newRow);

                // Add event listeners for the new edit and delete buttons
                const editBtn = newRow.querySelector('.Edit-std-btn');
                const deleteBtn = newRow.querySelector('.Delete-std-btn');

                if (editBtn) {
                    editBtn.addEventListener('click', function () {
                        rowToEdit = newRow;  // Get the new row to edit
                        editNameInput.value = newRow.cells[1].textContent;
                        editEmailInput.value = newRow.cells[2].textContent;
                        editModal.style.display = "block";  // Show the edit modal
                    });
                }

                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function () {
                        rowToDelete = newRow;  // Get the new row to delete
                        deleteModal.style.display = "block";  // Show the delete modal
                    });
                }

                addModal.style.display = "none";   
            }
        });
    </script>
</body>

</html>
