<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$error_code = $error_code ?? 404;
$error_message = $error_message ?? "We're sorry, but the page you're looking for doesn't exist.";
$page_to_redirect = $page_to_redirect ?? "home";

$error_description = "";
switch ($error_code) {
    case 404:
        $error_description = "The page you're looking for doesn't exist or may have been moved.";
        break;
    case 403:
        $error_description = "You do not have permission to access this page.";
        break;
    default:
        $error_description = "An unexpected error occurred. Please try again later.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?php echo htmlspecialchars($error_code); ?></title>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/errorPage.css">
</head>
<body>
    <div class="container">
        <div class="error-content">
            <h1><?php echo htmlspecialchars($error_code); ?></h1>
            <h2><?php echo htmlspecialchars($error_message); ?></h2>
            <p><?php echo $error_description; ?></p>

            <a href="<?php echo htmlspecialchars(redirect($page_to_redirect)); ?>" class="back-home">Go Back Home</a>
        </div>
    </div>
</body>
</html>
