<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css">

    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/viewReport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <?php include 'inc/header.php'; ?>

    <button onclick="history.back()" class="GOBACK" id="goback">Go Back</button>

    <main class="manageAssignmentsMain" id="assignment-section">
        <h2 id="h2Assignments">Assignment Due</h2>

        <form id="assignment-form-Add">
            <div class="post-title">
                <label for="assignment-title">Assignment Title:</label><br><br>
                <h2 style="font-size: 1.5rem;">The Role of Artificial Intelligence in Modern Medicine: Benefits,
                    Challenges, and Future Prospects</h2><br>
            </div>

            <div class="post-content">
                <label for="assignment-description">Description:</label><br><br>
                <div class="grade-container" style="margin-top: 0;">
                    <h5 style="font-size: 1.5rem;">In this essay, you will explore the impact of artificial intelligence
                        (AI) on the field of modern medicine. AI is rapidly transforming healthcare, from diagnostic
                        tools to personalized treatment plans and patient care automation. Your task is to analyze the
                        benefits AI brings to healthcare, the challenges it faces, and the potential future developments
                        in the field.</h5>
                </div>
            </div>

            <div class="post-details">
                <label for="due-date">Due Date:</label><br><br>
                <label for="assignment-duedate">
                    <h1 style="font-size: 1.4rem;">2-11-2024</h1>
                </label><br><br>
            </div>

            <div class="file-upload">
                <label for="assignment-file">Upload Assignment:</label><br><br>
                <input type="file" id="assignment-file" name="assignment-file">
                <div id="file-error" class="error" style="color: red; font-size: 0.9rem;"></div>
            </div>

            <button type="button" onclick="validateAndSubmit()" class="submit-button">Submit Assignment</button>
        </form>
    </main>

    <main class="manageAssignmentsMain" id="success-section" style="display: none;">
        <h2 id="h2Assignments">Submission Successful</h2>
        <img src="/Plagiarism_Checker/public/assets/images/checkmark-256.png" alt="Check-Mark" style="display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 2%;
            width: 15%;">
            
        <div style="display: flex; justify-content: center; margin-top:2%;">
            <a href="dashboard" class="submit-button">Return to Dashboard</a>
        </div>
    </main>

    <?php include 'inc/footer.php'; ?>

    <script src="/Plagiarism_Checker/public\assets\js\submit.js"></script>

</body>
</html>
