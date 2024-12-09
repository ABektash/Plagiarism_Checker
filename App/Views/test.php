<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php
?>

<body class="body">
    <?php include 'inc/header.php'; ?>

    <form id="plagiarismForm" action="<?php url('test/detectPlagiarism'); ?>">
        <label for="title-input">Title:</label>
        <input type="text" id="title-input" name="title" required>

        <label for="text-input">Text:</label>
        <textarea id="text-input" name="text" rows="5" required></textarea>

        <button type="submit">Check Plagiarism</button>
    </form>

    <div id="result"></div>


    <?php include 'inc/footer.php'; ?>
    <script src="public/assets/js/PlagiarismDetectionAPI.js"></script>
</body>

</html>