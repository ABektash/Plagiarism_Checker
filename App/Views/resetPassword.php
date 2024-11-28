<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/login.css">
</head>

<body>
    <br>
    <div class="container">
        <div class="registration form">
            <header>Change password</header>
            <form method="POST" action="<?php url('resetPassword/submit'); ?>">
                <input name="password" type="password" placeholder="Enter your new password" value="<?php echo isset($passwordError) ? '' : htmlspecialchars($_POST['password'] ?? ''); ?>">
                <?php if (isset($passwordError)): ?>
                    <div class="error-message" id="password-error"><?php echo $passwordError; ?></div>
                <?php endif; ?>

                <input name="confirmPassword" type="password" placeholder="Confirm your new password" value="<?php echo isset($passwordError) ? '' : htmlspecialchars($_POST['confirmPassword'] ?? ''); ?>">
                <?php if (isset($confirmPasswordError)): ?>
                    <div class="error-message" id="confirmPassword-error"><?php echo $confirmPasswordError; ?></div>
                <?php endif; ?>
                <?php if (isset($generalError)): ?>
                    <div class="error-message" id="confirmPassword-error"><?php echo $generalError; ?></div>
                <?php endif; ?>

                <input type="submit" name="submit" class="button" value="Reset">
            </form>


            <br>
            <a class="back-link" href="<?php url('login') ?>">Back to login</a>
        </div>
    </div>
</body>

</html>