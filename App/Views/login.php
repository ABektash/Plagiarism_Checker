<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/Plagarism_Checker/public/assets/css/login.css">
</head>
<body>
    <br>
    <div class="container">
        <div class="login form">
            <header>Login</header>
            <form method="POST" action="<?php url('login/submit'); ?>">
                <input name="email" type="text" placeholder="Enter your email" value="<?php echo isset($emailError) ? '' : htmlspecialchars($_POST['email'] ?? ''); ?>">
                <?php if (isset($emailError)): ?>
                    <div class="error-message" id="email-error"><?php echo $emailError; ?></div>
                <?php endif; ?>

                <input name="password" type="password" placeholder="Enter your password" value="<?php echo isset($passwordError) ? '' : htmlspecialchars($_POST['password'] ?? ''); ?>">
                <?php if (isset($passwordError)): ?>
                    <div class="error-message" id="password-error"><?php echo $passwordError; ?></div>
                <?php endif; ?>

                <a href="#">Forgot password?</a>
                <input type="submit" name="submit" class="button" value="Login">
            </form>
            <div class="signup">
                <span class="signup">Don't have an account?
                    <a href="<?php url('signup') ?>">Sign Up</a>
                </span>
            </div>
            <br>
            <a class="back-link" href="<?php url('home') ?>">Back to home</a>
        </div>
    </div>
</body>
</html>
