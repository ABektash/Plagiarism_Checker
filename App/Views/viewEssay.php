<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>

    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/viewEssay.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
</head>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$userType = $_SESSION['user']['UserType_id'];
?>

<body class="StudentManagementBody">

    <?php include 'inc/header.php'; ?>

    <?php if ($userType != 1) { ?>
        <button onclick="window.location.href='<?php echo url('dashboard/index'); ?>'" class="GOBACK">Go Back</button>
    <?php } else { ?>
        <button onclick="window.location.href='<?php echo url('manageSubmissions/index'); ?>'" class="GOBACK">Go Back</button>
    <?php } ?>


    <main class="StudentManagementMain">

        <section class="Groups">
            <h2>Student Name: <span class="student-name">Youssef Amer</span></h2>
            <h4>Essay</h4>

            <p>Our Plagiarism Detection System is designed to uphold academic standards by identifying instances of copied content in student submissions. With robust algorithms and comprehensive reporting features, we assist educators in fostering originality and providing constructive feedback to students, helping them enhance their writing skills and understand the importance of academic integrity.
                Our Plagiarism Detection System is designed to uphold academic standards by identifying instances of copied content in student submissions. With robust algorithms and comprehensive reporting features, we assist educators in fostering originality and providing constructive feedback to students, helping them enhance their writing skills and understand the importance of academic integrity.
                Our Plagiarism Detection System is designed to uphold academic standards by identifying instances of copied content in student submissions. With robust algorithms and comprehensive reporting features, we assist educators in fostering originality and providing constructive feedback to students, helping them enhance their writing skills and understand the importance of academic integrity.
                Our Plagiarism Detection System is designed to uphold academic standards by identifying instances of copied content in student submissions. With robust algorithms and comprehensive reporting features, we assist educators in fostering originality and providing constructive feedback to students, helping them enhance their writing skills and understand the importance of academic integrity.
                Our Plagiarism Detection System is designed to uphold academic standards by identifying instances of copied content in student submissions. With robust algorithms and comprehensive reporting features, we assist educators in fostering originality and providing constructive feedback to students, helping them enhance their writing skills and understand the importance of academic integrity.
                Our Plagiarism Detection System is designed to uphold academic standards by identifying instances of copied content in student submissions. With robust algorithms and comprehensive reporting features, we assist educators in fostering originality and providing constructive feedback to students, helping them enhance their writing skills and understand the importance of academic integrity.
            </p>

            <a class="view-report-btn" href="<?php url('viewReportInstructor/index'); ?>">View Plagiarism Report</a>

            <a href="/Plagiarism_Checker/public/uploads/essay.pdf" download class="download-essay-btn">Download Essay PDF</a>

        </section>
    </main>

    <?php include 'inc/footer.php'; ?>

</body>

<script src="/Plagiarism_Checker/public/assets/js/manageStudent.js"></script>

</html>