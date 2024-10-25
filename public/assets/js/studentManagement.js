let rowToDelete; // To store the row to be deleted
let rowToEdit; // To store the row to be edited

// Select all the delete and edit buttons
const deleteButtons = document.querySelectorAll(".Delete-std-btn");
const editButtons = document.querySelectorAll(".Edit-std-btn");
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
deleteButtons.forEach((button) => {
  button.addEventListener("click", function () {
    rowToDelete = button.closest("tr"); // Get the row to delete
    deleteModal.style.display = "block"; // Show the modal
  });
});

// When the user clicks on Yes, delete the row
yesBtn.addEventListener("click", function () {
  rowToDelete.remove(); // Remove the row
  deleteModal.style.display = "none";
});

// When the user clicks on No, close the modal
noBtn.addEventListener("click", function () {
  deleteModal.style.display = "none";
});

// Add event listeners to edit buttons
editButtons.forEach((button) => {
  button.addEventListener("click", function () {
    rowToEdit = button.closest("tr"); // Get the row to edit

    // Pre-fill the form with the current student data
    editNameInput.value = rowToEdit.cells[1].textContent;
    editEmailInput.value = rowToEdit.cells[2].textContent;

    // Reset error messages
    nameError.style.display = "none";
    emailError.style.display = "none";

    editModal.style.display = "block"; // Show the edit modal
  });
});

// When the user clicks on Save, validate inputs and update the row if valid
saveBtn.addEventListener("click", function () {
  let isValid = true;

  // Validate Name (cannot be empty and must contain only letters)
  if (editNameInput.value.trim() === "" || !validateName(editNameInput.value)) {
    nameError.style.display = "block";
    nameError.textContent = "Please enter a valid name.";
    isValid = false;
  } else {
    nameError.style.display = "none";
  }

  // Validate Email (must be in correct format)
  if (!validateEmail(editEmailInput.value)) {
    emailError.style.display = "block";
    isValid = false;
  } else {
    emailError.style.display = "none";
  }

  // If validation passes, update the row with the new data
  if (isValid) {
    rowToEdit.cells[1].textContent = editNameInput.value; // Update student name
    rowToEdit.cells[2].textContent = editEmailInput.value; // Update student email
    editModal.style.display = "none";
  }
});

// Cancel edit button
cancelEditBtn.addEventListener("click", function () {
  editModal.style.display = "none";
});

// Add Student Modal
const addModal = document.getElementById("addModal");
const addNameInput = document.getElementById("addName");
const addEmailInput = document.getElementById("addEmail");
const addSaveBtn = document.getElementById("add-save-btn");
const addCancelBtn = document.getElementById("cancel-add-btn");

const addNameError = document.getElementById("addNameError");
const addEmailError = document.getElementById("addEmailError");
const duplicateEmailError = document.getElementById("duplicateEmailError");

// Function to check for duplicate emails
function isDuplicateEmail(email) {
  const rows = document.querySelectorAll("table tbody tr");
  for (let row of rows) {
    if (row.cells[2].textContent === email) {
      return true; // Duplicate email found
    }
  }
  return false; // No duplicates
}

// Add event listener to add student button
document.querySelector(".add-std-btn").addEventListener("click", function () {
  // Reset the form
  addNameInput.value = "";
  addEmailInput.value = "";
  addNameError.style.display = "none";
  addEmailError.style.display = "none";
  duplicateEmailError.style.display = "none";

  addModal.style.display = "block"; // Show the add modal
});

// When the user clicks on Add, validate inputs and add a new row to the table
addSaveBtn.addEventListener("click", function () {
  let isValid = true;

  // Validate Name (cannot be empty and must contain only letters)
  if (addNameInput.value.trim() === "" || !validateName(addNameInput.value)) {
    addNameError.style.display = "block";
    addNameError.textContent = "Please enter a valid name.";
    isValid = false;
  } else {
    addNameError.style.display = "none";
  }

  // Validate Email (must be in correct format)
  if (!validateEmail(addEmailInput.value)) {
    addEmailError.style.display = "block";
    isValid = false;
  } else if (isDuplicateEmail(addEmailInput.value)) {
    duplicateEmailError.style.display = "block"; // Show duplicate email error
    isValid = false;
  } else {
    addEmailError.style.display = "none";
    duplicateEmailError.style.display = "none";
  }

  // If validation passes, add a new row to the table
  if (isValid) {
    const tableBody = document.querySelector("table tbody");

    // Create a new row
    const newRow = document.createElement("tr");

    // Generate a new student ID (for simplicity, using a random number)
    const newId = Math.floor(Math.random() * 100000)
      .toString()
      .padStart(5, "0");

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
    newRow
      .querySelector(".Edit-std-btn")
      .addEventListener("click", function () {
        rowToEdit = newRow; // Get the new row to edit
        editNameInput.value = newRow.cells[1].textContent;
        editEmailInput.value = newRow.cells[2].textContent;
        editModal.style.display = "block"; // Show the edit modal
      });

    newRow
      .querySelector(".Delete-std-btn")
      .addEventListener("click", function () {
        rowToDelete = newRow; // Get the new row to delete
        deleteModal.style.display = "block"; // Show the delete modal
      });

    addModal.style.display = "none";
  }
});

// Cancel add button
addCancelBtn.addEventListener("click", function () {
  addModal.style.display = "none";
});

// Close modals when clicking outside of them
window.addEventListener("click", function (event) {
  if (event.target == deleteModal) {
    deleteModal.style.display = "none";
  }
  if (event.target == editModal) {
    editModal.style.display = "none";
  }
  if (event.target == addModal) {
    addModal.style.display = "none";
  }
});
