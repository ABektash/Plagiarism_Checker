<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/viewReport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="/Plagiarism_Checker/public/assets/js/PlagiarismDetectionAPI.js" defer></script> <!-- Include JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js"></script>
    <style>
        label {
            font-size: 1.1rem;
        }

        h5 {
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
            <form id="assignment-form-Add" onsubmit="handleFormSubmit(event)" method="POST" enctype="multipart/form-data">
                <div class="post-title">
                    <label for="assignment-title">Assignment Title:</label><br><br>
                    <h5 id="assignmentTitle"><?php echo htmlspecialchars($assignment['Title']); ?></h5><br>
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
                    <input type="file" id="assignment-file" name="assignment-file" accept="application/pdf"
                        required><br><br>
                </div>

                <button type="submit" class="Add-button">
                    <h5 style="font-size: 1.2rem;">Submit</h5>
                </button>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function handleFormSubmit(event) {
            event.preventDefault(); // Prevent default form submission

            // Get the file input
            const fileInput = document.getElementById('assignment-file');
            if (!fileInput) {
                alert('File input element not found!');
                return;
            }

            const file = fileInput.files[0];

            // Validate that the file is a PDF
            if (!file) {
                alert('Please upload a file!');
                return;
            }

            if (file.type !== 'application/pdf') {
                alert('Only PDF files are allowed. Please upload a valid PDF file.');
                return;
            }

            // Call the PDF extraction function
            extractTextFromPDF(file);

            // Construct the dynamic URL
            const assignmentID = "<?php echo $assignment['ID']; ?>";
            const url = "<?php url('/submit/index'); ?>" + "?assignmentID=" + assignmentID;

            // Submit the form programmatically
            const form = document.getElementById('assignment-form-Add');
            form.action = url; // Set action dynamically
            form.submit(); // Submit the form
        }

        // Attach the function to the form's onsubmit event
        const form = document.getElementById('assignment-form-Add');
        if (form) {
            form.onsubmit = handleFormSubmit;
        }


        async function extractTextFromPDF(file) {
            try {
                const fileReader = new FileReader();

                fileReader.onload = async function (event) {
                    const pdfData = new Uint8Array(event.target.result);
                    const pdf = await pdfjsLib.getDocument(pdfData).promise;

                    let extractedText = '';

                    // Loop through all the pages and extract text
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        const page = await pdf.getPage(pageNum);
                        const textContent = await page.getTextContent();

                        const pageText = textContent.items.map(item => item.str).join(' ');
                        extractedText += pageText + '\n';
                    }

                    // Display the extracted text in the div
                    // CallAPI(extractedText.trim(), extractedtitle.innerText.trim());
                };

                // Read the file as an ArrayBuffer
                fileReader.readAsArrayBuffer(file);
            } catch (error) {
                pdfTextContainer.innerHTML = 'Error processing the PDF file: ' + error.message;
            }
        }
    });
</script>

</html>