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

function closeEditForm() {
  document.getElementById("editFormPopup").style.display = "none";
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





document.getElementById('editForm').addEventListener('submit', function (event) {
  event.preventDefault(); 

  let formData = new FormData(this);

  fetch('/Plagiarism_Checker/App/Controllers/editUserValidation.php', {
      method: 'POST',
      body: formData
  })
  .then(response => response.json()) 
  .then(data => {
      document.getElementById('fname-error').textContent = '';
      document.getElementById('lname-error').textContent = '';
      document.getElementById('email-error').textContent = '';
      document.getElementById('organizationName-error').textContent = '';
      document.getElementById('address-error').textContent = '';
      document.getElementById('phone-error').textContent = '';
      document.getElementById('birthday-error').textContent = '';
      document.getElementById('password-error').textContent = '';
      document.getElementById('userType-error').textContent = '';

      if (data.errors) {
          if (data.errors.firstNameError) {
              document.getElementById('fname-error').textContent = data.errors.firstNameError;
          }
          if (data.errors.lastNameError) {
              document.getElementById('lname-error').textContent = data.errors.lastNameError;
          }
          if (data.errors.emailError) {
              document.getElementById('email-error').textContent = data.errors.emailError;
          }
          if (data.errors.organizationNameError) {
              document.getElementById('organizationName-error').textContent = data.errors.organizationNameError;
          }
          if (data.errors.addressError) {
              document.getElementById('address-error').textContent = data.errors.addressError;
          }
          if (data.errors.phoneError) {
              document.getElementById('phone-error').textContent = data.errors.phoneError;
          }
          if (data.errors.birthdayError) {
              document.getElementById('birthday-error').textContent = data.errors.birthdayError;
          }
          if (data.errors.passwordError) {
              document.getElementById('password-error').textContent = data.errors.passwordError;
          }
          if (data.errors.userTypeError) {
              document.getElementById('userType-error').textContent = data.errors.userTypeError;
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
