<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
	<link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/adminDashboard.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css"> 

	<title>Plagiarism Detection</title>
</head>
<script>
    document.getElementById('assignment-form').addEventListener('submit', function(event) {
        let isValid = true;

        // Clear previous errors
        document.getElementById('date-error').innerText = '';
        document.getElementById('file-error').innerText = '';

        // Validate due date
        const dueDate = new Date(document.getElementById('due-date').value);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Set time to midnight for accurate comparison

        if (dueDate < today) {
            document.getElementById('date-error').innerText = 'Due date cannot be in the past.';
            isValid = false;
        }
       

        // Prevent form submission if invalid
        if (!isValid) {
            event.preventDefault();
        }
    });

    function closeForumADD() {
        document.getElementById('assignment-form-Add').reset();
        document.getElementById('forum-container-ADD').style.display = 'none';
        forum.style.animation = 'slideOut 0.5s forwards';
        setTimeout(() => {
            forum.style.display = 'none';
        }, 500);
    }
    function openForumADD() {
        document.getElementById('forum-container-ADD').style.display = 'block';
        forum.style.animation = 'slideIn 0.5s forwards';
        
    }

    function closeForumEdit() {
        document.getElementById('assignment-form-Edit').reset();
        document.getElementById('forum-container-EDIT').style.display = 'none';
        forum.style.animation = 'slideOut 0.5s forwards';
        setTimeout(() => {
            forum.style.display = 'none';
        }, 500);
    }
    function openForumEdit() {
        document.getElementById('forum-container-EDIT').style.display = 'block';
        forum.style.animation = 'slideIn 0.5s forwards';
        
    }

    function filterTable() {
        const filter = document.getElementById("search-bar").value.toUpperCase();
        const table = document.getElementById("assignment-table");
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {  // Start at 1 to skip header row
            let cells = rows[i].getElementsByTagName("td");
            let match = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j].innerText.toUpperCase().includes(filter)) {
                    match = true;
                    break;
                }
            }
            rows[i].style.display = match ? "" : "none";
        }
    }
    function deleteAssignment(element) {
        // Confirm before deleting
        const confirmed = confirm("Are you sure you want to delete this assignment?");
        if (confirmed) {
            // Access the row containing the clicked delete link
            const row = element.closest("tr");
            // Remove the row from the table
            row.remove();
        }
    }
    


</script>

<body>
	<?php include 'inc/sidebar.php'; ?>

	<section id="content">
		<?php include 'inc/navbar.php'; ?>


		<main>
			<div class="head-title">
				<h1>Manage Assignments</h1>
			</div>

            <section>
        
            <div class = "containerMA">            
                        <input type="text" placeholder="Search.." class="search-bar" id="search-bar" onkeyup="filterTable()">
                        <button type="submit" class="Add-button" onclick ="openForumADD()">Add Assignment</button>       
            </div>
            </section>

            <section>
                <table id="assignment-table">
                    <thead>
                        <tr>
                            <th>Assignment Title</th>
                            <th>Group</th>
                            <th>Due Date</th>
                            <th>Edit Assignment</th>
                            <th>Delete Assignment</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Essay on Climate Change</td>
                        <td>3</td>
                        <td>2024-12-01</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Introduction to Machine Learning</td>
                        <td>5</td>
                        <td>2024-11-20</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Research Paper on AI Ethics</td>
                        <td>2</td>
                        <td>2024-11-15</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>History of Renaissance Art</td>
                        <td>4</td>
                        <td>2024-12-10</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Essay on Climate Change</td>
                        <td>3</td>
                        <td>2024-12-01</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Introduction to Machine Learning</td>
                        <td>5</td>
                        <td>2024-11-20</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Research Paper on AI Ethics</td>
                        <td>2</td>
                        <td>2024-11-15</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>History of Renaissance Art</td>
                        <td>4</td>
                        <td>2024-12-10</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Essay on Climate Change</td>
                        <td>3</td>
                        <td>2024-12-01</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Introduction to Machine Learning</td>
                        <td>5</td>
                        <td>2024-11-20</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Research Paper on AI Ethics</td>
                        <td>2</td>
                        <td>2024-11-15</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>History of Renaissance Art</td>
                        <td>4</td>
                        <td>2024-12-10</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Essay on Climate Change</td>
                        <td>3</td>
                        <td>2024-12-01</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Introduction to Machine Learning</td>
                        <td>5</td>
                        <td>2024-11-20</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Research Paper on AI Ethics</td>
                        <td>2</td>
                        <td>2024-11-15</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>History of Renaissance Art</td>
                        <td>4</td>
                        <td>2024-12-10</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Essay on Climate Change</td>
                        <td>3</td>
                        <td>2024-12-01</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Introduction to Machine Learning</td>
                        <td>5</td>
                        <td>2024-11-20</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>Research Paper on AI Ethics</td>
                        <td>2</td>
                        <td>2024-11-15</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                    <tr>
                        <td>History of Renaissance Art</td>
                        <td>4</td>
                        <td>2024-12-10</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)">Edit</a></td>
                        <td><a class="a-link" href="#" onclick="deleteAssignment(this)">Delete Permanently</a></td>
                    </tr>
                </tbody>

                </table>
            </section>

            
            <div id="forum-container-ADD">
            <div class="forum-post">
            <div class="header-container">
            <h1 id="HeaderManage">Add New Assignment</h1>
            <button type ="reset" id ="close-btn" onclick="closeForumADD()">X</button>
            </div>
            <form id="assignment-form-Add">
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
                    <div id="date-error" class="error"></div>
                </div>

                <div class="Choose-Group-Container">
                            <label>Group:</label>

                            <select name="Group Number" class="Group-Selection">
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
            <button type ="reset" id ="close-btn" onclick="closeForumEdit()">X</button>
            </div>
            <form id="assignment-form-Edit">
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
                    <div id="date-error" class="error"></div>
                </div>

                <div class="Choose-Group-Container">
                            <label>Group:</label>

                            <select name="Group Number" class="Group-Selection">
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

                <button type="submit" class="edit-button">Edit Assignment</button>
            </form>
            </div>
            </div>




		</main>

	</section>

</body>

</html>