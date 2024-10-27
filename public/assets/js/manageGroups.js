(() => {
  let rowToDelete;
  let rowToEdit;
  let instructorToRemove;

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

  const validateEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

  const validateName = (name) => /^[A-Za-z\s]+$/.test(name);

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      rowToDelete = button.closest("tr");
      deleteModal.style.display = "block";
    });
  });

  yesBtn.addEventListener("click", function () {
    if (rowToDelete) {
      rowToDelete.remove();
      deleteModal.style.display = "none";
    }
  });

  noBtn.addEventListener("click", function () {
    deleteModal.style.display = "none";
  });

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      rowToEdit = button.closest("tr");

      editNameInput.value = rowToEdit.cells[1].textContent;
      editEmailInput.value = rowToEdit.cells[2].textContent;

      nameError.style.display = "none";
      emailError.style.display = "none";

      editModal.style.display = "block";
    });
  });

  saveBtn.addEventListener("click", function () {
    let isValid = true;

    if (!validateName(editNameInput.value.trim())) {
      nameError.style.display = "block";
      nameError.textContent = "Please enter a valid name.";
      isValid = false;
    } else {
      nameError.style.display = "none";
    }

    if (!validateEmail(editEmailInput.value.trim())) {
      emailError.style.display = "block";
      isValid = false;
    } else {
      emailError.style.display = "none";
    }

    if (isValid) {
      rowToEdit.cells[1].textContent = editNameInput.value;
      rowToEdit.cells[2].textContent = editEmailInput.value;
      editModal.style.display = "none";
    }
  });

  cancelEditBtn.addEventListener("click", function () {
    editModal.style.display = "none";
  });

  const addModal = document.getElementById("addModal");
  const addSaveBtn = document.getElementById("add-save-btn");
  const cancelAddBtn = document.getElementById("cancel-add-btn");

  const addNameInput = document.getElementById("addName");
  const addEmailInput = document.getElementById("addEmail");

  const addNameError = document.getElementById("addNameError");
  const addEmailError = document.getElementById("addEmailError");
  const duplicateEmailError = document.getElementById("duplicateEmailError");

  document.querySelector(".add-std-btn").addEventListener("click", function () {
    addModal.style.display = "block";
  });

  cancelAddBtn.addEventListener("click", function () {
    addModal.style.display = "none";
  });

  const isDuplicateEmail = (email) => {
    const rows = document.querySelectorAll("table tbody tr");

    return Array.from(rows).some((row) => row.cells[2].textContent === email);
  };

  addSaveBtn.addEventListener("click", function () {
    let isValid = true;

    if (!validateName(addNameInput.value.trim())) {
      addNameError.style.display = "block";
      addNameError.textContent = "Please enter a valid name.";
      isValid = false;
    } else {
      addNameError.style.display = "none";
    }

    if (!validateEmail(addEmailInput.value.trim())) {
      addEmailError.style.display = "block";
      isValid = false;
    } else if (isDuplicateEmail(addEmailInput.value.trim())) {
      duplicateEmailError.style.display = "block";
      isValid = false;
    } else {
      addEmailError.style.display = "none";
      duplicateEmailError.style.display = "none";
    }

    if (isValid) {
      const tableBody = document.querySelector("table tbody");

      const newRow = document.createElement("tr");

      const newId = Math.floor(Math.random() * 100000)
        .toString()
        .padStart(5, "0");

      newRow.innerHTML = `
                <td>${newId}</td>
                <td>${addNameInput.value.trim()}</td>
                <td>${addEmailInput.value.trim()}</td>
                <td><button class="View-Profile-btn"><i class='bx bx-user'></i></button></td>
                <td>
                    <button class="Edit-std-btn"><i class='bx bx-edit'></i></button>
                    <button class="Delete-std-btn"><i class='bx bx-trash'></i></button>
                </td>
            `;

      tableBody.appendChild(newRow);

      newRow
        .querySelector(".Edit-std-btn")
        .addEventListener("click", function () {
          rowToEdit = newRow;
          editNameInput.value = newRow.cells[1].textContent;
          editEmailInput.value = newRow.cells[2].textContent;
          editModal.style.display = "block";
        });

      newRow
        .querySelector(".Delete-std-btn")
        .addEventListener("click", function () {
          rowToDelete = newRow;
          deleteModal.style.display = "block";
        });

      addModal.style.display = "none";
    }
  });

  const addInstructorModal = document.getElementById("addInstructorModal");
  const addInstructorSaveBtn = document.getElementById("addInstructorSaveBtn");
  const cancelAddInstructorBtn = document.getElementById(
    "cancelAddInstructorBtn"
  );

  const instructorNameInput = document.getElementById("instructorName");
  const instructorNameError = document.getElementById("instructorNameError");

  document
    .querySelector(".add-instructor-btn")
    .addEventListener("click", function () {
      addInstructorModal.style.display = "block";
    });

  cancelAddInstructorBtn.addEventListener("click", function () {
    addInstructorModal.style.display = "none";
  });

  addInstructorSaveBtn.addEventListener("click", function () {
    let isValid = true;

    if (!validateName(instructorNameInput.value.trim())) {
      instructorNameError.style.display = "block";
      instructorNameError.textContent = "Please enter a valid name.";
      isValid = false;
    } else {
      instructorNameError.style.display = "none";
    }

    if (isValid) {
      const instructorSelection = document.querySelector(
        ".Instructor-Selection"
      );
      const newOption = document.createElement("option");
      newOption.value = instructorNameInput.value.trim().replace(/\s+/g, "");
      newOption.textContent = instructorNameInput.value.trim();

      instructorSelection.appendChild(newOption);
      addInstructorModal.style.display = "none";
    }
  });

  const removeInstructorModal = document.getElementById(
    "removeInstructorModal"
  );
  const confirmRemoveInstructorBtn = document.getElementById(
    "confirmRemoveInstructorBtn"
  );
  const cancelRemoveInstructorBtn = document.getElementById(
    "cancelRemoveInstructorBtn"
  );

  document
    .querySelector(".remove-instructor-btn")
    .addEventListener("click", function () {
      const instructorSelection = document.querySelector(
        ".Instructor-Selection"
      );
      instructorToRemove = instructorSelection.value;
      removeInstructorModal.style.display = "block";
    });

  confirmRemoveInstructorBtn.addEventListener("click", function () {
    if (instructorToRemove) {
      const instructorSelection = document.querySelector(
        ".Instructor-Selection"
      );
      instructorSelection
        .querySelector(`option[value="${instructorToRemove}"]`)
        .remove();
      removeInstructorModal.style.display = "none";
    }
  });

  cancelRemoveInstructorBtn.addEventListener("click", function () {
    removeInstructorModal.style.display = "none";
  });
})();
