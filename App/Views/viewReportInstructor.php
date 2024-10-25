<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/viewReport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <?php include 'inc/header.php'; ?>

    <button onclick="history.back()" class="GOBACK">Go Back</button>


    <main class="manageAssignmentsMain">
        <h2 id="h2Assignments">Your Report</h2>

        <section>
            <div class="chartContainer">
                <h3>Similarity Score</h3>
                <div class="piechart" style="margin-left: 15%;"></div>

                <!-- Legend section -->
                <div class="legend-container">
                    <div class="legend">
                        <div class="legend-color green-box"></div>
                        <span>13% Similarity</span>
                    </div>
                    <div class="legend">
                        <div class="legend-color grey-box"></div>
                        <span>87%</span>
                    </div>
                </div>
                <div class="grade-container">
                    <h1>Your Grade: <input type="text" id="grade" name="grade" oninput="validateGrade()" min="0"
                            max="100">/100</h1>
                </div>
                <p id="error-message" style="color: red; display: none;">Please enter a grade between 0 and 100.</p>
            </div>
            <div class="textContainer">
                <h1 style="margin-left:2%; font-size:2rem; color:#027e6f;">AI Response:</h1>
                <h5>Your similarity score was 13%, suggesting that you referenced external material to help complete
                    your assignment. While it’s fine to consult resources for learning, we encourage you to focus on
                    understanding the concepts instead of relying too heavily on online sources. This way, you'll deepen
                    your knowledge and build confidence in tackling similar problems on your own. Keep up the great
                    effort!</h5>
            </div>
        </section>

        <div class="grade-container" style="width:90%;">
            <textarea id="instructor-comment" name="instrucor-comment" rows="5" required
                placeholder="Please Enter your comments..." style="width:90%;"></textarea>
            <button onclick="submitReport(this);" class="GOBACK">Finalize Report</button>
        </div>

    </main>

    <?php include 'inc/footer.php'; ?>

    <script>
        function validateGrade() {
            const gradeInput = document.getElementById("grade");
            const errorMessage = document.getElementById("error-message");

            // Check if the value is within range
            if (gradeInput.value < 0 || gradeInput.value > 100) {
                errorMessage.style.display = "block"; // Show error message
                gradeInput.style.borderColor = "red";
            } else {
                errorMessage.style.display = "none";  // Hide error message if valid
                gradeInput.style.borderColor = "";    // Reset border color
            }
        }

        function submitReport(element) {
            const gradeInput = document.getElementById("grade").value;

            // Check if grade is a valid number and within range
            if (isNaN(gradeInput) || gradeInput < 0 || gradeInput > 100 || gradeInput === "") {
                alert("Please enter a valid numeric grade between 0 and 100 before submitting the report.");
                return; 
            }

            // Confirm submission
            const confirmed = confirm("Are you sure you want to submit this report to the student: XXXXX?");
            if (confirmed) {
                history.back();
            }
        }

    </script>
</body>

</html>