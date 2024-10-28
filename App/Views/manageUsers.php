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
            <div class="containerMA">
                <input type="text" placeholder="Search.." class="search-bar" id="search-bar" onkeyup="filterTable()">
                <button type="submit" class="Add-button" onclick="openForumADD()">Add user</button>
                <select id="user-type-filter" onchange="filterTable()">
                    <option value="">All User Types</option>
                    <option value="1">Admin</option>
                    <option value="2">Instructor</option>
                    <option value="3">Student</option>
                    <option value="4">Visitor</option>
                </select>
            </div>
            <table id="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Password</th>
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
                                <td><?= htmlspecialchars($row['Password']) ?></td>
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
                                    <a class="delete-a-link" href="deleteUser.php?id=<?= htmlspecialchars($row['ID']) ?>" onclick="return confirm('Are you sure you want to delete this user with ID <?= htmlspecialchars($row['ID']) ?>?');">
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

            <div id="editFormPopup" class="popup-form">
                <div class="popup-content">
                    <span class="close" onclick="closeEditForm()">&times;</span>
                    <h2>Edit User</h2>
                    <form id="editForm" action="" method="">
                        <input type="hidden" name="ID" id="editID">

                        <label for="editFirstName">First Name</label>
                        <input type="text" name="FirstName" id="editFirstName" required>
                        <div class="error-message" id="fname-error"></div>

                        <label for="editLastName">Last Name</label>
                        <input type="text" name="LastName" id="editLastName" required>
                        <div class="error-message" id="lname-error"></div>

                        <label for="editEmail">Email</label>
                        <input type="email" name="Email" id="editEmail" required>
                        <div class="error-message" id="email-error"></div>

                        <label for="editOrganization">Organization</label>
                        <input type="text" name="Organization" id="editOrganization">
                        <div class="error-message" id="organizationName-error"></div>

                        <label for="editAddress">Address</label>
                        <input type="text" name="Address" id="editAddress">
                        <div class="error-message" id="address-error"></div>

                        <label for="editPhoneNumber">Phone Number</label>
                        <input type="text" name="PhoneNumber" id="editPhoneNumber">
                        <div class="error-message" id="phone-error"></div>

                        <label for="editBirthday">Birthday</label>
                        <input type="date" name="Birthday" id="editBirthday">
                        <div class="error-message" id="birthday-error"></div>

                        <label for="editPassword">Password</label>
                        <input type="text" name="Password" id="editPassword" required>
                        <div class="error-message" id="password-error"></div>

                        <label for="editUserType">User Type</label>
                        <select name="UserType_id" id="editUserType" required>
                            <option value="1">Admin</option>
                            <option value="2">Instructor</option>
                            <option value="3">Student</option>
                            <option value="4">Visitor</option>
                        </select>
                        <div class="error-message" id="userType-error"></div>

                        <button type="submit">Save Changes</button>
                    </form>

                </div>
            </div>

        </main>
    </section>

</body>

<script src="/Plagiarism_Checker/public/assets/js/manageUsers.js"></script>

</html>