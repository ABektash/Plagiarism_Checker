function openEditForm(user) {
  document.getElementById("editID").value = user.ID;
  document.getElementById("editFirstName").value = user.FirstName;
  document.getElementById("editLastName").value = user.LastName;
  document.getElementById("editEmail").value = user.Email;
  document.getElementById("editOrganization").value = user.Organization;
  document.getElementById("editAddress").value = user.Address;
  document.getElementById("editPhoneNumber").value = user.PhoneNumber;
  document.getElementById("editBirthday").value = user.Birthday;
  document.getElementById("editPassword").value = user.Password;
  document.getElementById("editUserType").value = user.UserType_id;
  document.getElementById("editFormPopup").style.display = "block";
}
function openAddForm(user) {
  document.getElementById("addID").value = '';
  document.getElementById("addFirstName").value = '';
  document.getElementById("addLastName").value = '';
  document.getElementById("addEmail").value = '';
  document.getElementById("addOrganization").value = '';
  document.getElementById("addAddress").value = '';
  document.getElementById("addPhoneNumber").value = '';
  document.getElementById("addBirthday").value = '';
  document.getElementById("addPassword").value = '';
  document.getElementById("addUserType").value = '4';
  document.getElementById("addFormPopup").style.display = "block";
}

function closeEditForm() {
  document.getElementById("editFormPopup").style.display = "none";
}
function closeAddForm() {
  document.getElementById("addFormPopup").style.display = "none";
}




function filterTable() {
  const filter = document.getElementById("search-bar").value.toUpperCase();
  const userTypeFilter = document.getElementById("user-type-filter").value;
  const table = document.getElementById("users-table");
  const rows = table.getElementsByTagName("tr");

  for (let i = 1; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName("td");
    let match = false;

    if (
      (cells[0] && cells[0].innerText.toUpperCase().includes(filter)) ||
      (cells[1] && cells[1].innerText.toUpperCase().includes(filter)) ||
      (cells[2] && cells[2].innerText.toUpperCase().includes(filter)) ||
      (cells[1] && cells[2] &&(cells[1].innerText.toUpperCase() + " " + cells[2].innerText.toUpperCase()).includes(filter)) ||
      (cells[3] && cells[3].innerText.toUpperCase().includes(filter))) {
      match = true;
    }

    if (userTypeFilter &&cells[9] &&cells[9].innerText.includes(userTypeFilter)) {
      match = match && true;
    } else if (userTypeFilter &&cells[9] &&!cells[9].innerText.includes(userTypeFilter)
    ) {
      match = false;
    }

    rows[i].style.display = match ? "" : "none";
  }
}



document.getElementById('addForm').addEventListener('submit', function (event) {
  event.preventDefault(); 
  let formData = new FormData(this);

  fetch('/Plagiarism_Checker/App/Controllers/addUserValidation.php', { 
      method: 'POST',
      body: formData
  })
  .then(response => response.json()) 
  .then(data => {

      document.getElementById('add-fname-error').textContent = '';
      document.getElementById('add-lname-error').textContent = '';
      document.getElementById('add-email-error').textContent = '';
      document.getElementById('add-organizationName-error').textContent = '';
      document.getElementById('add-address-error').textContent = '';
      document.getElementById('add-phone-error').textContent = '';
      document.getElementById('add-birthday-error').textContent = '';
      document.getElementById('add-password-error').textContent = '';
      document.getElementById('add-userType-error').textContent = '';

      if (data.errors) {
          if (data.errors.firstNameError) {
              document.getElementById('add-fname-error').textContent = data.errors.firstNameError;
          }
          if (data.errors.lastNameError) {
              document.getElementById('add-lname-error').textContent = data.errors.lastNameError;
          }
          if (data.errors.emailError) {
              document.getElementById('add-email-error').textContent = data.errors.emailError;
          }
          if (data.errors.organizationNameError) {
              document.getElementById('add-organizationName-error').textContent = data.errors.organizationNameError;
          }
          if (data.errors.addressError) {
              document.getElementById('add-address-error').textContent = data.errors.addressError;
          }
          if (data.errors.phoneError) {
              document.getElementById('add-phone-error').textContent = data.errors.phoneError;
          }
          if (data.errors.birthdayError) {
              document.getElementById('add-birthday-error').textContent = data.errors.birthdayError;
          }
          if (data.errors.passwordError) {
              document.getElementById('add-password-error').textContent = data.errors.passwordError;
          }
          if (data.errors.userTypeError) {
              document.getElementById('add-userType-error').textContent = data.errors.userTypeError;
          }
      } else if (data.success) {
          closeEditForm(); 
          location.reload(); 
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
});

document.getElementById('editForm').addEventListener('submit', function (event) {
  event.preventDefault(); 

  let formData = new FormData(this);

  fetch('/Plagiarism_Checker/App/Controllers/editUserValidation.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json()) 
  .then(data => {
      document.getElementById('edit-fname-error').textContent = '';
      document.getElementById('edit-lname-error').textContent = '';
      document.getElementById('edit-email-error').textContent = '';
      document.getElementById('edit-organizationName-error').textContent = '';
      document.getElementById('edit-address-error').textContent = '';
      document.getElementById('edit-phone-error').textContent = '';
      document.getElementById('edit-birthday-error').textContent = '';
      document.getElementById('edit-password-error').textContent = '';
      document.getElementById('edit-userType-error').textContent = '';

      if (data.errors) {
          if (data.errors.firstNameError) {
              document.getElementById('edit-fname-error').textContent = data.errors.firstNameError;
          }
          if (data.errors.lastNameError) {
              document.getElementById('edit-lname-error').textContent = data.errors.lastNameError;
          }
          if (data.errors.emailError) {
              document.getElementById('edit-email-error').textContent = data.errors.emailError;
          }
          if (data.errors.organizationNameError) {
              document.getElementById('edit-organizationName-error').textContent = data.errors.organizationNameError;
          }
          if (data.errors.addressError) {
              document.getElementById('edit-address-error').textContent = data.errors.addressError;
          }
          if (data.errors.phoneError) {
              document.getElementById('edit-phone-error').textContent = data.errors.phoneError;
          }
          if (data.errors.birthdayError) {
              document.getElementById('edit-birthday-error').textContent = data.errors.birthdayError;
          }
          if (data.errors.passwordError) {
              document.getElementById('edit-password-error').textContent = data.errors.passwordError;
          }
          if (data.errors.userTypeError) {
              document.getElementById('edit-userType-error').textContent = data.errors.userTypeError;
          }
      } else if (data.success) {
          closeEditForm(); 
          location.reload(); 
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
});
