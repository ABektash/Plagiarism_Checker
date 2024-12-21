document.getElementById("assignment-form-Add").addEventListener("submit", function (event) {
  let isValid = true;

  // Clear previous errors
  document.getElementById("date-error-Add").innerText = "";

  // Validate due date
  const dueDate = new Date(document.getElementById("due-date").value);
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  if (dueDate < today) {
    document.getElementById("date-error-Add").innerText = "Due date cannot be in the past.";
    isValid = false;
  }

  // Prevent form submission if invalid
  if (!isValid) {
    event.preventDefault();
  }
});


// Add event listener for the Edit Assignment form
document
  .getElementById("assignment-form-Edit")
  .addEventListener("submit", function (event) {
    let isValid = true;

    // Clear previous errors
    document.getElementById("date-error-Edit").innerText = "";
    document.getElementById("file-error-Edit").innerText = "";

    // Validate due date for the Edit form
    const dueDate = new Date(document.getElementById("due-date-Edit").value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison

    if (dueDate < today) {
      document.getElementById("date-error-Edit").innerText =
        "Due date cannot be in the past.";
      isValid = false;
    }

    // Validate file format (only PDF) for Edit form
    const fileInput = document.getElementById("assignment-file-Edit");
    const filePath = fileInput.value;
    const allowedExtension = /(\.pdf)$/i;

    if (filePath && !allowedExtension.test(filePath)) {
      document.getElementById("file-error-Edit").innerText =
        "Only PDF files are allowed.";
      isValid = false;
    }

    // Prevent form submission if invalid
    if (!isValid) {
      event.preventDefault();
    }

    closeForumEdit();
  });

// Function to close Add Assignment forum
function closeForumADD() {
  document.getElementById("assignment-form-Add").reset();
  document.getElementById("forum-container-ADD").style.display = "none";
  document.getElementById("forum-container-ADD").style.animation = "slideOut 0.5s forwards";
}

// Function to open Add Assignment forum
function openForumADD() {
  document.getElementById("forum-container-ADD").style.display = "block";
  document.getElementById("forum-container-ADD").style.animation = "slideIn 0.5s forwards";
}

// Function to close Edit Assignment forum
function closeForumEdit() {
  document.getElementById("assignment-form-Edit").reset();
  document.getElementById("forum-container-EDIT").style.display = "none";
  document.getElementById("forum-container-EDIT").style.animation = "slideOut 0.5s forwards";
  setTimeout(() => {
    document.getElementById("forum-container-EDIT").style.display = "none";
  }, 500);
}

function openForumEdit(element) {
  // Get the row containing the clicked edit button
  const row = element.closest('tr');

  // Extract data from the row cells, adjusting indices to match table structure
  const assignmentId = row.getAttribute('data-assignment-id'); // Retrieve ID from data attribute
  const title = row.cells[1].innerText.trim(); // Second cell for title
  const description = row.cells[2].innerText.trim(); // Hidden third cell for description
  const groupId = row.cells[3].innerText.trim(); // Fourth cell for group ID
  const dueDate = row.cells[4].innerText.trim(); // Fifth cell for due date

  // Populate the form fields in the Edit form with the extracted data
  document.getElementById("assignment-title-Edit").value = title;
  document.getElementById("assignment-description-Edit").value = description;
  document.getElementById("due-date-Edit").value = dueDate;
  document.getElementById("Group-Number-Edit").value = groupId;

  // Validate due date for the Edit form
  const dueDate2 = new Date(document.getElementById("due-date-Edit").value);
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison


    // Update the form action URL with the assignment ID for editing
    const formEdit = document.getElementById("assignment-form-Edit");
    formEdit.action = `${editAssignmentUrl}/${assignmentId}`;


  // Show the edit form modal with animation
  const editFormContainer = document.getElementById("forum-container-EDIT");
  editFormContainer.style.display = "block";
  editFormContainer.style.animation = "slideIn 0.5s forwards";
}





// Function to filter assignments in the table
function filterTable() {
  const filter = document.getElementById("search-bar").value.toUpperCase();
  const table = document.getElementById("assignment-table");
  const rows = table.getElementsByTagName("tr");

  for (let i = 1; i < rows.length; i++) {
    let cells = rows[i].getElementsByTagName("td");
    let match = false;

    for (let j = 0; j < 2 && j < cells.length; j++) {
      if (cells[j].innerText.toUpperCase().includes(filter)) {
        match = true;
        break;
      }
    }
    rows[i].style.display = match ? "" : "none";
  }
}





