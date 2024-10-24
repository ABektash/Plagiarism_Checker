(() => {
    let rowToDelete; // To store the row to be deleted
    let rowToEdit;   // To store the row to be edited
    let instructorToRemove; // To store the instructor to be removed

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
    const validateEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

    // Validation function to check if the name contains only letters and spaces
    const validateName = (name) => /^[A-Za-z\s]+$/.test(name);

    // Add event listeners to delete buttons
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            rowToDelete = button.closest('tr');  // Get the row to delete
            deleteModal.style.display = "block";  // Show the modal
        });
    });

    // When the user clicks on Yes, delete the row
    yesBtn.addEventListener('click', function () {
        if (rowToDelete) {
            rowToDelete.remove();  // Remove the row
            deleteModal.style.display = "none";   
        }
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
        if (!validateName(editNameInput.value.trim())) {
            nameError.style.display = 'block';
            nameError.textContent = "Please enter a valid name.";
            isValid = false;
        } else {
            nameError.style.display = 'none';
        }

        // Validate Email (must be in correct format)
        if (!validateEmail(editEmailInput.value.trim())) {
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
    const isDuplicateEmail = (email) => {
        const rows = document.querySelectorAll('table tbody tr');
        
        return Array.from(rows).some(row => row.cells[2].textContent === email);
    };

    // Add new student to the table
    addSaveBtn.addEventListener('click', function () {
        let isValid = true;

        // Validate Name (cannot be empty and must contain only letters)
        if (!validateName(addNameInput.value.trim())) {
            addNameError.style.display = 'block';
            addNameError.textContent = "Please enter a valid name.";
            isValid = false;
        } else {
            addNameError.style.display = 'none';
        }

        // Validate Email (must be in correct format)
        if (!validateEmail(addEmailInput.value.trim())) {
            addEmailError.style.display = 'block';
            isValid = false;
        } else if (isDuplicateEmail(addEmailInput.value.trim())) {
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
            
            // Generate a new student ID consisting of numbers only
            const newId = Math.floor(Math.random() * 100000).toString().padStart(5, '0');
            
            // Set the row's HTML with the new student data
            newRow.innerHTML = `
                <td>${newId}</td>
                <td>${addNameInput.value.trim()}</td>
                <td>${addEmailInput.value.trim()}</td>
                <td><button class="View-Profile-btn">View</button></td>
                <td>
                    <button class="Edit-std-btn">Edit</button>
                    <button class="Delete-std-btn">Remove</button>
                </td>
            `;

            // Append the new row to the table body
            tableBody.appendChild(newRow);

            // Add event listeners for the new edit and delete buttons
            newRow.querySelector('.Edit-std-btn').addEventListener('click', function () {
                rowToEdit = newRow;  // Get the new row to edit
                editNameInput.value = newRow.cells[1].textContent;
                editEmailInput.value = newRow.cells[2].textContent;
                editModal.style.display = "block";  // Show the edit modal
            });

            newRow.querySelector('.Delete-std-btn').addEventListener('click', function () {
                rowToDelete = newRow;  // Get the new row to delete
                deleteModal.style.display = "block";  // Show the delete modal
            });

            addModal.style.display = "none";   
        }
    });

    // Add Instructor Modal functionality
    const addInstructorModal = document.getElementById("addInstructorModal");
    const addInstructorSaveBtn = document.getElementById("addInstructorSaveBtn");
    const cancelAddInstructorBtn = document.getElementById("cancelAddInstructorBtn");

    const instructorNameInput = document.getElementById("instructorName");
    const instructorNameError = document.getElementById("instructorNameError");

    // Show Add Instructor Modal
    document.querySelector('.add-instructor-btn').addEventListener('click', function () {
        addInstructorModal.style.display = "block";
    });

    // Cancel Add Instructor
    cancelAddInstructorBtn.addEventListener('click', function () {
        addInstructorModal.style.display = "none";
    });

    // Add Instructor functionality
    addInstructorSaveBtn.addEventListener('click', function () {
        let isValid = true;

        // Validate Instructor Name (cannot be empty and must contain only letters)
        if (!validateName(instructorNameInput.value.trim())) {
            instructorNameError.style.display = 'block';
            instructorNameError.textContent = "Please enter a valid name.";
            isValid = false;
        } else {
            instructorNameError.style.display = 'none';
        }

        if (isValid) {
            const instructorSelection = document.querySelector('.Instructor-Selection');
            const newOption = document.createElement('option');
            newOption.value = instructorNameInput.value.trim().replace(/\s+/g, '') // Remove spaces for value
            newOption.textContent = instructorNameInput.value.trim();  // Set the text of the option

            // Add the new instructor to the selection
            instructorSelection.appendChild(newOption);
            addInstructorModal.style.display = "none";   
        }
    });

    // Remove Instructor functionality
    const removeInstructorModal = document.getElementById("removeInstructorModal");
    const confirmRemoveInstructorBtn = document.getElementById("confirmRemoveInstructorBtn");
    const cancelRemoveInstructorBtn = document.getElementById("cancelRemoveInstructorBtn");

    // Select instructor to remove
    document.querySelector('.remove-instructor-btn').addEventListener('click', function () {
        const instructorSelection = document.querySelector('.Instructor-Selection');
        instructorToRemove = instructorSelection.value;  // Get the currently selected instructor
        removeInstructorModal.style.display = "block";  // Show the confirmation modal
    });

    // Confirm remove instructor
    confirmRemoveInstructorBtn.addEventListener('click', function () {
        if (instructorToRemove) {
            const instructorSelection = document.querySelector('.Instructor-Selection');
            instructorSelection.querySelector(`option[value="${instructorToRemove}"]`).remove();  // Remove the selected instructor
            removeInstructorModal.style.display = "none";   
        }
    });

    // Cancel remove instructor
    cancelRemoveInstructorBtn.addEventListener('click', function () {
        removeInstructorModal.style.display = "none";   
    });

})();
