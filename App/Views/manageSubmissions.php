<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageSubmissions.css">
    
    <title>Plagiarism Detection</title>

</head>

<body>
    <?php include 'inc/sidebar.php'; ?>

    <section id="content">
        <?php include 'inc/navbar.php'; ?>

        <main>
            <div class="head-title">
                <h1>Manage Submissions</h1>
            </div>
            <div class="containerMA">
                <input type="text" placeholder="Search.." class="search-bar" id="search-bar" onkeyup="filterTable()">
            </div>

            <table id="submission-table">
                <thead>
                    <tr>
                        <th>student ID</th>
                        <th>Assignment Title</th>
                        <th>Submission</th>
                        <th>Submission Date</th>
                        <th>Plagiarism Report</th>
                        <th>Feedback Discussion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>3</td>
                        <td>Essay on Climate Change</td>
                        <td><a class="a-link" href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Research Paper on AI Ethics</td>
                        <td><a class="a-link"href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Introduction to Machine Learning</td>
                        <td><a class="a-link"href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Essay on Climate Change</td>
                        <td><a class="a-link"href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Research Paper on AI Ethics</td>
                        <td><a class="a-link"href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Introduction to Machine Learning</td>
                        <td><a class="a-link"href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Essay on Climate Change</td>
                        <td><a class="a-link"href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Research Paper on AI Ethics</td>
                        <td><a class="a-link"href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Introduction to Machine Learning</td>
                        <td><a class="a-link"href="#" onclick="openForumEdit(this)"><i class='bx bx-upload'></i></a></td>
                        <td>2024-10-27 15:30:00</td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"><i class='bx bx-file'></i></a></td>
                        <td><a class="a-link"href="#" onclick="deleteAssignment(this)"> <i class='bx bx-message'></i></a></td>
                    </tr>
                </tbody>

            </table>
        </main>

    </section>

</body>

</html>