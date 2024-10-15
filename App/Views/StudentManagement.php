<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>

    <link rel="stylesheet" href="/Plagarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagarism_Checker/public/assets/css/footer.css">
    <link rel="stylesheet" href="/Plagarism_Checker/public/assets/css/StudentManagement.css">

</head>
<body>

    <?php include 'inc/header.php'; ?>

    <main>

    <!-- Students Dashboard -->
        <section id="Groups">
            <div id = "Group-Container">
            <h2>Group:</h2>
            
            <select name="Group Number" id="Group-Selection" >
             <option value="1">1</option>
             <option value="2">2</option>
             <option value="3">3</option>
             <option value="4">4</option>
            </select>

            </div>
            <table>
                <thead>
                    <tr>
                        <th>Assignment</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Essay on Climate Change</td>
                        <td>2024-10-10</td>
                        <td>Reviewed</td>
                        <td><a class="a-link" href="#">View</a></td>
                    </tr>
                    <tr>
                        <td>History Assignment</td>
                        <td>2024-10-12</td>
                        <td>Pending</td>
                        <td><a class="a-link" href="#">Submit</a></td>
                    </tr>
                </tbody>
            </table>

        </section>

        <br>
        <section id="reports">
            <h2>Plagiarism Reports</h2>

            <table>
                <thead>
                    <tr>
                        <th>Assignment</th>
                        <th>Similarity Score</th>
                        <th>Report Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Essay on Climate Change</td>
                        <td class="similarity-progress">12%</td>
                        <td>2024-10-11</td>
                        <td><a class="a-link" href="#">View Report</a></td>
                    </tr>
                </tbody>
            </table>

        </section>

        <br>
        <section id="progress">
            <h2>Progress Tracking</h2>
            <p>Track your improvement over time:</p>
            <br>
            <div class="progress-bar">
                <div class="progress" style="width: 70%;">70%</div>
            </div>
            <p>Last submission: Essay on Climate Change - 12% similarity.</p>
        </section>


        <!-- Insturactor Dashboard -->
        <section id="groups">
            <h2>Groups</h2>

            <table>
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>Total Students</th>
                        <th>Submissions Received</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Group A</td>
                        <td>30</td>
                        <td>28</td>
                        <td><a class="a-link" href="#">View</a></td>

                    </tr>
                    <tr>
                        <td>Group B</td>
                        <td>25</td>
                        <td>20</td>
                        <td><a class="a-link" href="#">View</a></td>

                    </tr>
                </tbody>
            </table>

        </section>

        <br>
        <section id="submissions">
            <h2>Submissions</h2>

            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Assignment</th>
                        <th>Submission Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>Essay on Climate Change</td>
                        <td>2024-10-10</td>
                        <td>Pending</td>
                        <td><a class="a-link" href="#">Review</a></td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>History Assignment</td>
                        <td>2024-10-12</td>
                        <td>Reviewed</td>
                        <td><a class