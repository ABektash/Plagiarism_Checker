<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>

    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/StudentManagement.css">

</head>

<body>


    <main>

        <!-- Students Dashboard -->
        <section id="Groups">
            <div id="Group-Container">
                <div id="Left-Group-Container">
                    <h2>Group:</h2>

                    <select name="Group Number" id="Group-Selection">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>

                <div id="Right-Group-Container">
                    <button id="create-group-btn">Create Group</button>
                    <button id="edit-group-btn">Edit Group</button>
                    <button id="delete-group-btn">Delete Group</button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Profile</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ahmed Mohamed</td>
                        <td>ghazouly@gmail.com</td>
                        <td><button class="View-Profile-btn">View</button></td>
                    </tr>
                    <tr>
                        <td>Ammar Bektash</td>
                        <td>Abektash@gmail.com</td>
                        <td><button class="View-Profile-btn">View</button></td>
                    </tr>
                </tbody>
            </table>

        </section>

    </main>

    <?php include 'inc/footer.php'; ?>

</body>

</html>