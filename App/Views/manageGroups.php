<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plagiarism Detection</title>
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageGroups.css">
</head>

<body>
    <?php include 'inc/sidebar.php'; ?>

    <section id="content">
        <?php include 'inc/navbar.php'; ?>

        <main>
            <div class="head-title">
                <h1>Manage Groups</h1>
            </div>

            <div class="Group-Container">
                <div class="Left-Group-Container">
                    <h2 class="Group-Selection-Title">Group:</h2>
<select name="group_id" class="Group-Selection" id="groupSelection">
    <?php foreach ($data['groups'] as $group): ?>
        <option value="<?= $group['group_id'] ?>" <?= $group['group_id'] == 1 ? 'selected' : '' ?>>
            <?= $group['group_name'] ?>
        </option>
    <?php endforeach; ?>
</select>
                </div>
                <div class="Right-Group-Container">
                    <button class="add-std-btn">Add Student</button>
                    <button class="create-group-btn">Add Group</button>
                    <button class="delete-group-btn">Delete Group</button>
                </div>
            </div>

            <div class="Group-Container">
                <div class="Left-Group-Container">
                    <h2 class="Insturctor-Selection-Title">Instructors:</h2>
                    <select name="Instructor" class="Instructor-Selection">
                        <?php if (!empty($data['instructors'])): ?>
                            <?php foreach ($data['instructors'] as $instructor): ?>
                                <option value="<?= $instructor['inst_id'] ?>"><?= $instructor['inst_name'] ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option disabled>No instructors found</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="Right-Group-Container">
                    <button class="add-instructor-btn">Add Instructor</button>
                    <button class="remove-instructor-btn">Remove Instructor</button>
                </div>
            </div>

            <table class="Group-table" id="studentsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Profile</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <!-- Add Instructor Modal -->
            <div id="addInstructorModal" class="modal">
    <div class="modal-content">
        <h2>Add Instructor to Group</h2>
        <form id="addInstructorForm">
            <label for="instructorID">Instructor ID:</label>
            <input type="text" id="instructorID" name="instructorID" required>
            <br>
            <label for="inst-groupID">Group ID:</label>
            <input type="text" id="inst-groupID" name="inst-groupID" required>
            <br>
            <button type="button" id="add-instructor-save-btn">Add</button>
            <button type="button" id="cancel-add-instructor-btn">Cancel</button>
        </form>
    </div>
</div>

            <!-- Remove Instructor Confirmation Modal -->
            <div id="removeInstructorModal" class="modal">
                <div class="modal-content">
                    <p>Are you sure you want to remove this instructor?</p>
                    <button id="confirmRemoveInstructorBtn">Yes</button>
                    <button id="cancelRemoveInstructorBtn">No</button>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="modal">
                <div class="modal-content">
                    <p>Are you sure you want to remove this student?</p>
                    <button id="yes-btn">Yes</button>
                    <button id="no-btn">No</button>
                </div>
            </div>

            <!-- Add Student Modal -->
            <div id="addModal" class="modal">
    <div class="modal-content">
        <h2>Add Student</h2>
        <form id="addForm">
            <label for="studentID">Student ID:</label>
            <input type="text" id="studentID" name="studentID" required>
            <br>
            <label for="groupID">Group ID:</label>
            <input type="text" id="groupID" name="groupID" required>
            <br>
            <button type="button" id="add-save-btn">Add</button>
            <button type="button" id="cancel-add-btn">Cancel</button>
        </form>
    </div>
</div>

            <!-- Remove Group Confirmation Modal -->
            <div id="DeleteGroupModal" class="modal">
                <div class="modal-content">
                    <p>Are you sure you want to delete this Group?</p>
                    <button id="confirmDeleteGroupBtn">Yes</button>
                    <button id="cancelDeleteGroupBtn">No</button>
                </div>
            </div>



        </main>
    </section>

    <script src="/Plagiarism_Checker/public/assets/js/manageGroups.js"></script>
</body>

</html>
