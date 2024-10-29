document
  .getElementById("assignment-form")
  .addEventListener("submit", function (event) {
    let isValid = true;

    // Clear previous errors
    document.getElementById("date-error").innerText = "";
    document.getElementById("file-error").innerText = "";

    // Validate due date
    const dueDate = new Date(document.getElementById("due-date").value);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison

    if (dueDate < today) {
      document.getElementById("date-error").innerText =
        "Due date cannot be in the past.";
      isValid = false;
    }

    // Validate file format (only PDF)
    const fileInput = document.getElementById("assignment-file");
    const filePath = fileInput.value;
    const allowedExtension = /(\.pdf)$/i;

    if (!allowedExtension.test(filePath)) {
      document.getElementById("file-error").innerText =
        "Only PDF files are allowed.";
      isValid = false;
    }

    // Prevent form submission if invalid
    if (!isValid) {
      event.preventDefault();
    }
    closeForumADD();
    closeForumEdit();
  });

function closeForumADD() {
  document.getElementById("assignment-form-Add").reset();
  document.getElementById("forum-container-ADD").style.display = "none";
  forum.style.animation = "slideOut 0.5s forwards";
  setTimeout(() => {
    forum.style.display = "none";
  }, 500);
}
function openForumADD() {
  document.getElementById("forum-container-ADD").style.display = "block";
  forum.style.animation = "slideIn 0.5s forwards";
}

function closeForumEdit() {
  document.getElementById("assignment-form-Edit").reset();
  document.getElementById("forum-container-EDIT").style.display = "none";
  forum.style.animation = "slideOut 0.5s forwards";
  setTimeout(() => {
    forum.style.display = "none";
  }, 500);
}
function openForumEdit() {
  document.getElementById("forum-container-EDIT").style.display = "block";
  forum.style.animation = "slideIn 0.5s forwards";
}

function filterTable() {
  const filter = document.getElementById("search-bar").value.toUpperCase();
  const table = document.getElementById("assignment-table");
  const rows = table.getElementsByTagName("tr");

  for (let i = 1; i < rows.length; i++) {
    // Start at 1 to skip header row
    let cells = rows[i].getElementsByTagName("td");
    let match = false;

    // Check only the first two cells (columns)
    for (let j = 0; j < 2 && j < cells.length; j++) {
      if (cells[j].innerText.toUpperCase().includes(filter)) {
        match = true;
        break;
      }
    }
    rows[i].style.display = match ? "" : "none";
  }
}
function deleteAssignment(element) {
  // Confirm before deleting
  const confirmed = confirm("Are you sure you want to delete this assignment?");
  if (confirmed) {
    // Access the row containing the clicked delete link
    const row = element.closest("tr");
    // Remove the row from the table
    row.remove();
  }
}
