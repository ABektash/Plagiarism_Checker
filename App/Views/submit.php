<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/viewReport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="/Plagiarism_Checker/public/assets/js/PlagiarismDetectionAPI.js" defer></script> <!-- Include JS -->
    <style>
        label{
            font-size: 1.1rem;
        }
        h5{
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>

    <button onclick="history.back()" class="GOBACK" id="goback">Go Back</button>

    <main class="manageAssignmentsMain" id="assignment-section">
        <h2 id="h2Assignments">Assignment Details</h2>

        <?php if (isset($assignment)): ?>
            <form id="assignment-form-Add" action=<?php url('/submit/submit'  . "?assignmentID=" . $assignment['ID']); ?> method="POST" enctype="multipart/form-data">
                <div class="post-title">
                    <label for="assignment-title">Assignment Title:</label><br><br>
                    <h5><?php echo htmlspecialchars($assignment['Title']); ?></h5><br>
                </div>

                <div class="post-content">
                    <label for="assignment-description">Description:</label><br><br>
                    <div class="grade-container" style="margin-top: 0;">
                        <h5><?php echo htmlspecialchars($assignment['Description']); ?></h5>
                    </div>
                </div>

                <div class="post-details">
                    <label for="due-date">Due Date:</label><br><br>
                    <h5><?php echo htmlspecialchars($assignment['DueDate']); ?></h5>
                </div>

                <!-- File Upload Field -->
                <div class="file-upload">
                    <label for="assignment-file">Upload Assignment File (PDF only):</label><br><br>
                    <input type="file" id="assignment-file" name="assignment-file" accept="application/pdf" required><br><br>
                </div>

                <button type="submit" class="Add-button"><h5 style="font-size: 1.2rem;">Submit</h5></button>
            </form>
        <?php elseif (isset($error)): ?>
            <div class="grade-container" style="margin-top: 0;">
                <h5 style="font-size: 1.5rem;"><?php echo htmlspecialchars($error); ?></h5>
            </div>
        <?php else: ?>
            <div class="grade-container" style="margin-top: 0;">
                <h5 style="font-size: 1.5rem;">Unexpected error occurred.</h5>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'inc/footer.php'; ?>
</body>
</html>
