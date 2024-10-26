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

window.onclick = function (event) {
  if (event.target == document.getElementById("editFormPopup")) {
    closeEditForm();
  }
};

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
