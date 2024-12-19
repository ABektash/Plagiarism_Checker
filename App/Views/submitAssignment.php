<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Assignment</title>

    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/viewReport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="/Plagiarism_Checker/public/assets/js/PlagiarismDetectionAPI.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js"></script>

</head>

<body>
    <?php include 'inc/header.php'; ?>

    <button onclick="history.back()" class="GOBACK" id="goback">Go Back</button>

    <main class="manageAssignmentsMain" id="assignment-section">
        <h2 id="h2Assignments">Assignment Details</h2>

        <?php if (isset($assignment)): ?>
            <form id="assignment-form-Add" onsubmit="handleFormSubmit(event)" method="POST" enctype="multipart/form-data">
                <div class="post-title">
                    <label for="assignment-title">Assignment Title:</label><br><br>
                    <h5 id="assignmentTitle"><?php echo htmlspecialchars($assignment['Title']); ?></h5><br>
                </div>

                <div class="post-content">
                    <label for="assignment-description">Description:</label><br><br>
                    <div class="grade-container">
                        <h5><?php echo htmlspecialchars($assignment['Description']); ?></h5>
                    </div>
                </div>

                <div class="post-details">
                    <label for="due-date">Due Date:</label><br><br>
                    <h5><?php echo htmlspecialchars($assignment['DueDate']); ?></h5>
                </div>

                <div class="file-upload">
                    <label for="assignment-file">Upload Assignment File (PDF only):</label><br><br>
                    <input type="file" id="assignment-file" name="assignment-file" accept="application/pdf" required><br><br>
                </div>

                <button type="submit" class="Add-button">
                    <span style="font-size: 1.2rem;">Submit</span>
                </button>
            </form>

        <?php elseif (isset($error)): ?>
            <div class="grade-container">
                <h5 style="font-size: 1.5rem;"><?php echo htmlspecialchars($error); ?></h5>
            </div>
        <?php else: ?>
            <div class="grade-container">
                <h5 style="font-size: 1.5rem;">Unexpected error occurred. Please try again later.</h5>
            </div>
        <?php endif; ?>

    </main>

    <?php include 'inc/footer.php'; ?>
</body>

<script>
    <?php
    require_once 'autoload.php';
    ?>
    const ENV = {
        API_KEY: "<?php echo $_ENV['Plagiarism_API_KEY'] ?? ''; ?>",
    };

    document.addEventListener('DOMContentLoaded', function() {
        async function handleFormSubmit(event) {
            event.preventDefault();

            const fileInput = document.getElementById('assignment-file');
            if (!fileInput) {
                alert('File input element not found!');
                return;
            }

            const file = fileInput.files[0];

            if (!file) {
                alert('Please upload a file!');
                return;
            }

            if (file.type !== 'application/pdf') {
                alert('Only PDF files are allowed. Please upload a valid PDF file.');
                return;
            }

            const extractedTitle = document.getElementById('assignmentTitle').innerText.trim();
            const assignmentID = "<?php echo $assignment['ID']; ?>";

            if (!assignmentID) {
                alert('Assignment ID is missing.');
                return;
            }

            const extractedText = await extractTextFromPDF(file);
            if (!extractedText) {
                alert('Failed to extract text from the PDF file.');
                return;
            }

            const submissionID = await submitAssignment(assignmentID, extractedText);

            if (submissionID) {
                await CallAPI(extractedText, extractedTitle, submissionID);
            } else {
                alert('Failed to submit the assignment.');
            }
        }

        const form = document.getElementById('assignment-form-Add');
        if (form) {
            form.onsubmit = handleFormSubmit;
        }
    });
</script>

</html>