<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageUsers.css">
    <title>Plagiarism Detection</title>

</head>

<body>
    <?php include 'inc/sidebar.php'; ?>

    <section id="content">
        <?php include 'inc/navbar.php'; ?>

        <main>
            <div class="head-title">
                <h1>Manage Users</h1>
            </div>

            <div class="Group-Container">
                <div class="Left-Group-Container">
                    <input type="text" placeholder="Search..." class="search-bar" id="search-bar" onkeyup="filterTable()" />
                </div>

                <div class="Right-Group-Container">
                    <button type="submit" class="Add-button" onclick="openAddForm()">Add User</button>
                    <select id="user-type-filter" onchange="filterTable()">
                        <option value="">All User Types</option>
                        <option value="1">Admin</option>
                        <option value="2">Instructor</option>
                        <option value="3">Student</option>
                        <option value="4">Visitor</option>
                    </select>
                </div>
            </div>


            <table id="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Organization</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>Birthday</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php foreach ($users as $row) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['ID']) ?></td>
                                <td><?= htmlspecialchars($row['FirstName']) ?></td>
                                <td><?= htmlspecialchars($row['LastName']) ?></td>
                                <td><?= htmlspecialchars($row['Email']) ?></td>
                                <td><?= htmlspecialchars($row['Organization']) ?></td>
                                <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                                <td><?= htmlspecialchars($row['Address']) ?></td>
                                <td><?= htmlspecialchars($row['Birthday']) ?></td>
                                <td><?= htmlspecialchars($row['UserType_id']) ?></td>
                                <td>
                                    <a class="a-link" href="<?= url('adminProfile/index/' . htmlspecialchars($row['ID'])) ?>">
                                        <i class='bx bxs-user'></i>
                                    </a> |
                                    <a class="a-link" href="javascript:void(0);" onclick="openEditForm(<?= htmlspecialchars(json_encode($row)) ?>)">
                                        <i class='bx bx-edit'></i>
                                    </a>|
                                    <a class="delete-a-link" href="javascript:void(0);" onclick="confirmDeletion(<?= htmlspecialchars($row['ID']) ?>)">
                                        <i class='bx bx-trash'></i>
                                    </a>
                                </td>

                            </tr>

                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="11">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php
            if (isset($deleteError)) {
                echo "$deleteError";
            }
            ?>

            <div id="editFormPopup" class="popup-form">
                <div class="popup-content">
                    <span class="close" onclick="closeEditForm()">&times;</span>
                    <h2>Edit User</h2>
                    <form id="editForm" action="" method="">
                        <input type="hidden" name="ID" id="editID">

                        <label for="editFirstName">First Name</label>
                        <input type="text" name="FirstName" id="editFirstName" required>
                        <div class="error-message" id="edit-fname-error"></div>

                        <label for="editLastName">Last Name</label>
                        <input type="text" name="LastName" id="editLastName" required>
                        <div class="error-message" id="edit-lname-error"></div>

                        <label for="editEmail">Email</label>
                        <input type="email" name="Email" id="editEmail" required>
                        <div class="error-message" id="edit-email-error"></div>

                        <label for="editOrganization">Organization</label>
                        <input type="text" name="Organization" id="editOrganization">
                        <div class="error-message" id="edit-organizationName-error"></div>

                        <label for="editAddress">Address</label>
                        <input type="text" name="Address" id="editAddress">
                        <div class="error-message" id="edit-address-error"></div>

                        <label for="editPhoneNumber">Phone Number</label>
                        <input type="text" name="PhoneNumber" id="editPhoneNumber">
                        <div class="error-message" id="edit-phone-error"></div>

                        <label for="editBirthday">Birthday</label>
                        <input type="date" name="Birthday" id="editBirthday">
                        <div class="error-message" id="edit-birthday-error"></div>

                        <label for="editPassword">Password</label>
                        <input type="text" name="Password" id="editPassword" >
                        <div class="error-message" id="edit-password-error"></div>

                        <label for="editUserType">User Type</label>
                        <select name="UserType_id" id="editUserType" required>
                            <option value="1">Admin</option>
                            <option value="2">Instructor</option>
                            <option value="3">Student</option>
                            <option value="4">Visitor</option>
                        </select>
                        <div class="error-message" id="edit-userType-error"></div>

                        <button type="submit">Save Changes</button>
                    </form>

                </div>
            </div>




            <div id="addFormPopup" class="popup-form">
                <div class="popup-content">
                    <span class="close" onclick="closeAddForm()">&times;</span>
                    <h2>Add User</h2>
                    <form id="addForm" action="" method="">
                        <input type="hidden" name="ID" id="addID">

                        <label for="addFirstName">First Name</label>
                        <input type="text" name="FirstName" id="addFirstName" required>
                        <div class="error-message" id="add-fname-error"></div>

                        <label for="addLastName">Last Name</label>
                        <input type="text" name="LastName" id="addLastName" required>
                        <div class="error-message" id="add-lname-error"></div>

                        <label for="addEmail">Email</label>
                        <input type="email" name="Email" id="addEmail" required>
                        <div class="error-message" id="add-email-error"></div>

                        <label for="addOrganization">Organization</label>
                        <input type="text" name="Organization" id="addOrganization">
                        <div class="error-message" id="add-organizationName-error"></div>

                        <label for="addAddress">Address</label>
                        <input type="text" name="Address" id="addAddress">
                        <div class="error-message" id="add-address-error"></div>

                        <label for="addPhoneNumber">Phone Number</label>
                        <input type="text" name="PhoneNumber" id="addPhoneNumber">
                        <div class="error-message" id="add-phone-error"></div>

                        <label for="addBirthday">Birthday</label>
                        <input type="date" name="Birthday" id="addBirthday">
                        <div class="error-message" id="add-birthday-error"></div>

                        <label for="addPassword">Password</label>
                        <input type="text" name="Password" id="addPassword" required>
                        <div class="error-message" id="add-password-error"></div>

                        <label for="addUserType">User Type</label>
                        <select name="UserType_id" id="addUserType" required>
                            <option value="1">Admin</option>
                            <option value="2">Instructor</option>
                            <option value="3">Student</option>
                            <option value="4" selected>Visitor</option>
                        </select>

                        <div class="error-message" id="add-userType-error"></div>

                        <button type="submit">Save User</button>
                    </form>

                </div>
            </div>

        </main>
    </section>

</body>

<script src="/Plagiarism_Checker/public/assets/js/manageUsers.js"></script>
<script>
    function confirmDeletion(userID) {
        if (confirm('Are you sure you want to delete this user with ID ' + userID + '?')) {
            if (userID != 1) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php url('manageUsers/delete'); ?>';

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'userID';
                input.value = userID;

                form.appendChild(input);

                document.body.appendChild(form);

                form.submit();
            }
        }
    }
</script>

</html>