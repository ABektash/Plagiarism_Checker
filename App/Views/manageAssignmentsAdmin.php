<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignmentsAdmin.css">

    <title>Plagiarism Detection</title>
</head>


<body>
    <?php
    if (isset($insertError)) {
        echo "<script>alert('Action was Unsuccessful!');</script>";
        echo "<script>
                            window.location.href = '" . redirect('manageAssignmentsAdmin/index') . "';
                            </script>";
    }


    ?>
    <?php include 'inc/sidebar.php'; ?>

    <section id="content">
        <?php include 'inc/navbar.php'; ?>




        <main>
            <div class="head-title">
                <h1>Manage Assignments</h1>
            </div>
            <div class="Group-Container">
                <div class="Left-Group-Container">
                    <input type="text" placeholder="Search..." class="search-bar" id="search-bar"
                        onkeyup="filterTable()" />
                </div>

                <div class="Right-Group-Container">
                    <button type="submit" class="Add-button" onclick="openForumADD()">Add Assignment</button>
                </div>
            </div>

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
                                    <td><a class="a-link" href="#" onclick="openForumEdit(this)"><i class='bx bx-edit'></i></a>
                                    </td>
                                    <td>
                                        <form
                                            action="<?php url('ManageAssignmentsAdmin/deleteAssignment/' . $assignment['ID']) ?>"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                            <button type="submit" class="delete-button">
                                                <i class='bx bx-trash'></i> Delete
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

            <div id="forum-container-ADD">
                <div class="forum-post">
                    <div class="header-container">
                        <h1 id="HeaderManage">Add New Assignment</h1>
                        <button type="reset" id="close-btn" onclick="closeForumADD()">X</button>
                    </div>
                    <form id="assignment-form-Add" method="post"
                        action="<?php url('ManageAssignmentsAdmin/addAssignment'); ?>">
                        <div class="post-title">
                            <label for="assignment-title">Assignment Title:</label><br>
                            <input type="text" id="assignment-title" name="assignment-title" required>
                        </div>

                        <div class="post-content">
                            <label for="assignment-description">Description:</label><br>
                            <textarea id="assignment-description" name="assignment-description" rows="5"
                                required></textarea>
                        </div>

                        <div class="post-details">
                            <label for="due-date">Due Date:</label><br>
                            <input type="date" id="due-date" name="due-date" required>
                            <div id="date-error-Add" class="error"></div>
                        </div>

                        <div class="Choose-Group-Container">
                            <label>Group:</label>

                            <select name="groupID" class="Group-Selection">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <div class="file-upload">
                            <label for="assignment-file">Upload Assignment (Optional):</label><br><br>
                            <input type="file" id="assignment-file" name="assignment-file">
                            <div id="file-error" class="error"></div>
                        </div>

                        <button type="submit" class="submit-button">Submit Assignment</button>
                    </form>
                </div>
            </div>




            <div id="forum-container-EDIT">
        <div class="forum-post">
            <div class="header-container">
                <h1 id="HeaderManage">Edit Assignment</h1>
                <button type="reset" id="close-btn" onclick="closeForumEdit()">X</button>
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
                    <select name="groupID" id="Group-Number-Edit" class="Group-Selection" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">5</option>
                        <option value="1">165</option>
                        <option value="2">265</option>
                        <option value="3">354</option>
                        <option value="4">4456</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        
                    </select>
                </div>

                <button type="submit" class="edit-button">Edit Assignment</button>
            </form>
        </div>
    </div>




        </main>

    </section>

</body>

<script src="/Plagiarism_Checker/public\assets\js\manageAssignmentsAdmin.js"></script>

</html>