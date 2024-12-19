<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/manageAssignments.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/header.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/footer.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/viewReport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$userType = $_SESSION['user']['UserType_id'];
?>

<body>

    <?php include 'inc/header.php'; ?>

    <?php if ($userType != 1) { ?>
        <button onclick="window.location.href='<?php echo url('dashboard/index'); ?>'" class="GOBACK">Go Back</button>
    <?php } else { ?>
        <button onclick="window.location.href='<?php echo url('manageSubmissions/index'); ?>'" class="GOBACK">Go Back</button>
    <?php } ?>



    <main class="manageAssignmentsMain">
        <h2 id="h2Assignments">Your Report</h2>

        <section>
            <div class="chartContainer">
                <h3>Similarity Score</h3>
                <div class="piechart"></div>

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
                    <h1>Your Grade: 93/100</h1>
                </div>
                <div>
                    <button onclick="window.location.href='<?php url('forums/index'); ?>'" class="GOBACK">Contact Intructor</button>
                </div>
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

        <div class="grade-container">
            <h5>Instructor Comments: While you did reference external materials, be cautious about how much you rely on
                these sources. Aim to synthesize your findings and present them in your own words to deepen your
                understanding and improve your writing style.</h5>
        </div>
    </main>

    <?php include 'inc/footer.php'; ?>

</body>

</html>