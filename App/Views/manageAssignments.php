<!-- App/Views/manageAssignments.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css">
</head>

<body>

    <?php include 'inc/header.php'; ?>

    <div class="Group-Container">
        <div class="Left-Group-Container">
            <input type="text" placeholder="Search.." class="search-bar" id="search-bar" onkeyup="filterTable()">
        </div>

        <div class="Right-Group-Container">
            <button type="submit" class="Add-button" onclick="openForumADD()">Add Assignment</button>

        </div>
    </div>
    <?php
    if (isset($insertError)) {
        echo "<script>alert('Action was Unsuccessful!');</script>";
        echo "<script> window.location.href = '" . redirect('manageAssignments/index') . "'; </script>";
    }
    ?>

    <main class="manageAssignmentsMain">

        <section>
            <table id="assignment-table">
                <thead>
                    <tr>
                        <th style="display:none;">Assignment ID</th>
                        <th>Assignment Title</th>
                        <th style="display:none;">Assignment Description</th>
                        <th>Group</th>
                        <th>Due Date</th>
                        <th>Edit Assignment</th>
                        <th>Delete Assignment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($assignments)): ?>
                        <?php foreach ($assignments as $assignment): ?>
                            <tr data-assignment-id="<?php echo htmlspecialchars($assignment['ID']); ?>">
                                <td style="display:none;"><?php echo htmlspecialchars($assignment['ID']); ?></td>
                                <td><?php echo htmlspecialchars($assignment['Title']); ?></td>
                                <td style="display:none;"><?php echo htmlspecialchars($assignment['Description']); ?></td>
                                <td><?php echo htmlspecialchars($assignment['groupID']); ?></td>
                                <td><?php echo htmlspecialchars($assignment['DueDate']); ?></td>
                                <td><a class="a-link" href="#" onclick="openForumEdit(this)"><i class='bx bx-edit'></i></a></td>

                                <td>
                                    <form action="<?php url('ManageAssignments/deleteAssignment/' . $assignment['ID']) ?>"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                        <button type="submit" class="delete-button">
                                            <i class='bx bx-trash' style="font-size: 1.25em;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No assignments available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </main>

    <div id="forum-container-ADD">
        <div class="forum-post">
            <div class="header-container">
                <h1 id="HeaderManage">Add New Assignment</h1>
                <button type="reset" id="close-btn" onclick="closeForumADD()">X</button>
            </div>
            <form id="assignment-form-Add" method="post" action="<?php url('ManageAssignments/addAssignment'); ?>">
                <div class="post-title">
                    <label for="assignment-title">Assignment Title:</label><br>
                    <input type="text" id="assignment-title" name="assignment-title" required>
                </div>

                <div class="post-content">
                    <label for="assignment-description">Description:</label><br>
                    <textarea id="assignment-description" name="assignment-description" rows="5" required></textarea>
                </div>

                <div class="post-details">
                    <label for="due-date">Due Date:</label><br>
                    <input type="date" id="due-date" name="due-date" required>
                    <div id="date-error-Add" class="error"></div>
                </div>

                <div class="Choose-Group-Container">
                    <select name="groupID" class="Group-Selection" id="groupSelection">
                        <?php foreach ($data['groups'] as $group): ?>
                            <option value="<?= $group['groupID'] ?>" <?= $group['groupID'] == 1 ? 'selected' : '' ?>>
                                <?= $group['group_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="file-upload">
                    <label for="assignment-file">Upload Assignment (Not Functional atm):</label><br><br>
                    <input type="file" id="assignment-file" name="assignment-file">
                    <div id="file-error-Add" class="error"></div>
                </div>

                <button type="submit" class="submit-button">Submit Assignment</button>
            </form>
        </div>
    </div>




    <div id="forum-container-EDIT">
        <div class="forum-post">
            <div class="header-container">
                <h1 id="HeaderManage">Edit Assignment</h1>
                <button type="reset" id="close-btn" onclick="closeForumEdit()"><i class="fa-solid fa-x" style="color: #ff0000;"></i></button>
            </div>
            <form id="assignment-form-Edit" method="post">
                <div class="post-title">
                    <label for="assignment-title">Assignment Title:</label><br>
                    <input type="text" id="assignment-title-Edit" name="assignment-title" required>
                </div>

                <div class="post-content">
                    <label for="assignment-description">Description:</label><br>
                    <textarea id="assignment-description-Edit" name="assignment-description" rows="5"
                        required></textarea>
                </div>

                <div class="post-details">
                    <label for="due-date">Due Date:</label><br>
                    <input type="date" id="due-date-Edit" name="due-date" required>
                    <div id="date-error-Edit" class="error"></div>
                </div>

                <div class="Choose-Group-Container">
                    <label>Group:</label><br>
                    <select name="groupID" id="Group-Number-Edit" class="Group-Selection">
                        <?php foreach ($data['groups'] as $group): ?>
                            <option value="<?= $group['groupID'] ?>" <?= $group['groupID'] == 1 ? 'selected' : '' ?>>
                                <?= $group['group_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <button type="submit" class="edit-button">Edit Assignment</button>
            </form>
        </div>
    </div>

    <?php include 'inc/footer.php'; ?>

</body>

<script>
    <?php $editAssignmentUrl = redirect("ManageAssignments/editAssignment"); ?>
    const editAssignmentUrl = "<?php echo htmlspecialchars($editAssignmentUrl, ENT_QUOTES); ?>";
</script>
<script src="/Plagiarism_Checker/public/assets/js/manageAssignments.js"></script>
<script src="/Plagiarism_Checker/public/assets/js/manageGroups.js"></script>


</html>