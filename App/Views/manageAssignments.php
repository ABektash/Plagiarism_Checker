<!-- App/Views/manageAssignments.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css"> 
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css"> 
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css"> 
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
</script>
<body>

    <?php include 'inc/header.php'; ?> 

    <section id="reports">
                <h2 id="h2Assignments">Assignments </h2>
                <table>
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
                            <td>John Doe</td>
                            <td>7</td>
                            <td>2024-10-11</td>            
                            <td><a class="a-link" href="#">Edit</a></td>               
                            <td><a class="a-link" href="#">Delete Permenantly</a></td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>7</td>
                            <td>2024-10-11</td>            
                            <td><a class="a-link" href="#">Edit</a></td>               
                            <td><a class="a-link" href="#">Delete Permenantly</a></td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>7</td>
                            <td>2024-10-11</td>            
                            <td><a class="a-link" href="#">Edit</a></td>               
                            <td><a class="a-link" href="#">Delete Permenantly</a></td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>7</td>
                            <td>2024-10-11</td>            
                            <td><a class="a-link" href="#">Edit</a></td>               
                            <td><a class="a-link" href="#">Delete Permenantly</a></td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>7</td>
                            <td>2024-10-11</td>            
                            <td><a class="a-link" href="#">Edit</a></td>               
                            <td><a class="a-link" href="#">Delete Permenantly</a></td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>7</td>
                            <td>2024-10-11</td>            
                            <td><a class="a-link" href="#">Edit</a></td>               
                            <td><a class="a-link" href="#">Delete Permenantly</a></td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>7</td>
                            <td>2024-10-11</td>            
                            <td><a class="a-link" href="#">Edit</a></td>               
                            <td><a class="a-link" href="#">Delete Permenantly</a></td>
                        </tr>
                        <tr>
                            <td>John Doe</td>
                            <td>7</td>
                            <td>2024-10-11</td>            
                            <td><a class="a-link" href="#">Edit</a></td>               
                            <td><a class="a-link" href="#">Delete Permenantly</a></td>
                        </tr>
                        
                    </tbody>
                </table>
            </section>






    <div id="forum-container">
    <div class="forum-post">
    <h1 id="HeaderManage">Add New Assignment</h1>
    <form id="assignment-form">
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

        <div class="file-upload">
            <label for="assignment-file">Upload Assignment (Optional):</label><br>
            <input type="file" id="assignment-file" name="assignment-file">
            <div id="file-error" class="error"></div>
        </div>

        <button type="submit" class="submit-button">Submit Assignment</button>
    </form>
    </div>
    </div>




    <div id="forum-container">
    <div class="forum-post">
    <h1 id="HeaderManage">Edit Assignment</h1>
    <form id="assignment-form">
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

        <div class="file-upload">
            <label for="assignment-file">Upload Assignment (Optional):</label><br>
            <input type="file" id="assignment-file" name="assignment-file">
            <div id="file-error" class="error"></div>
        </div>

        <button type="submit" class="edit-button">Edit Assignment</button>
    </form>
    </div>
    </div>
    
    <?php include 'inc/footer.php'; ?>

</body>

</html>