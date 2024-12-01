<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/login.css">
</head>
<body>
    <br>
    <div class="container">
        <div class="login form">
            <header>Enter your email</header>
            <form method="POST" action="<?php url('forgotPassword/submit'); ?>">
                <input name="email" type="text" placeholder="Enter your email" value="<?php echo isset($emailError) ? '' : htmlspecialchars($_POST['email'] ?? ''); ?>">
                <?php if (isset($emailError)): ?>
                    <div class="error-message" id="email-error"><?php echo $emailError; ?></div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="error-message" id="email-error" style="color: green;"><?php echo $success; ?></div>
                <?php endif; ?>

                <input type="submit" name="submit" class="button" value="Enter">
            </form>
            <br>
            <a class="back-link" href="<?php url('login') ?>">Back to login</a>
        </div>
    </div>
</body>
</html>
