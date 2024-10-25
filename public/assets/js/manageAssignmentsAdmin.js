document
  .getElementById("assignment-form")
  .addEventListener("submit", function (event) {
    let isValid = true;

    document.getElementById("date-error").innerText = "";
    document.getElementById("file-error").innerText = "";

    const dueDate = new Date(document.getElementById("due-date").value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (dueDate < today) {
      document.getElementById("date-error").innerText =
        "Due date cannot be in the past.";
      isValid = false;
    }

    if (!isValid) {
      event.preventDefault();
    }
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
function deleteAssignment(element) {
  const confirmed = confirm("Are you sure you want to delete this assignment?");
  if (confirmed) {
    const row = element.closest("tr");
    row.remove();
  }
}
