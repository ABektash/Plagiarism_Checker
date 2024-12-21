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
            <div class="Group-Container">
                <div class="Left-Group-Container">
                    <input type="text" placeholder="Search..." class="search-bar" id="search-bar" onkeyup="filterTable()" />
                </div>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($submissions)): ?>
                        <?php foreach ($submissions as $submission): ?>
                            <tr>
                                <td><?= htmlspecialchars($submission['studentID']) ?></td>
                                <td><?= htmlspecialchars($submission['assignmentTitle']) ?></td>
                                <td><a class="a-link" href="<?php url('viewreport/index?reportID=' . $submission['reportID']); ?>"><i class='bx bx-upload'></i></a></td>
                                <td><?= htmlspecialchars($submission['submissionDate']) ?></td>
                                <td><a class="a-link" href="<?php url('viewreport/index?reportID=' . $submission['reportID']); ?>"><i class='bx bx-file'></i></a></td>
                                <td><a class="a-link" href="<?php url('forums/index/' . $submission['forumID']); ?>"><i class='bx bx-message'></i></a></td>
                                <td><a class="a-link delete-a-link" href="#" onclick="confirmDeletion(<?= $submission['submissionID'] ?>)"><i class='bx bx-trash'></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No submissions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>


            </table>
        </main>

    </section>

</body>
<script src="/Plagiarism_Checker/public\assets\js\manageSubmissions.js"></script>
<script>
    function confirmDeletion(submissionID) {
        if (confirm('Are you sure you want to delete this submission with ID ' + submissionID + '?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php url('manageSubmissions/delete'); ?>';

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'submissionID';
                input.value = submissionID;

                form.appendChild(input);

                document.body.appendChild(form);

                form.submit();
            }
    }
</script>


</html>