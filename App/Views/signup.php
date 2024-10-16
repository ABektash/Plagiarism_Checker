<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="/Plagarism_Checker/public/assets/css/login.css">
</head>
<body>
    <br>
    <div class="container">
        <div class="registration form">
            <header>Sign Up</header>
            <form method="POST" action="<?php url('signup/submit'); ?>">
                <input name="fname" type="text" placeholder="Enter your first name" value="<?php echo isset($fnameError) ? '' : htmlspecialchars($_POST['fname'] ?? ''); ?>">
                <?php if (isset($fnameError)): ?>
                    <div class="error-message" id="fname-error"><?php echo $fnameError; ?></div>
                <?php endif; ?>

                <input name="lname" type="text" placeholder="Enter your last name" value="<?php echo isset($lnameError) ? '' : htmlspecialchars($_POST['lname'] ?? ''); ?>">
                <?php if (isset($lnameError)): ?>
                    <div class="error-message" id="lname-error"><?php echo $lnameError; ?></div>
                <?php endif; ?>

                <input name="email" type="text" placeholder="Enter your email" value="<?php echo isset($emailError) ? '' : htmlspecialchars($_POST['email'] ?? ''); ?>">
                <?php if (isset($emailError)): ?>
                    <div class="error-message" id="email-error"><?php echo $emailError; ?></div>
                <?php endif; ?>

                <input name="password" type="password" placeholder="Create a password" value="<?php echo isset($passwordError) ? '' : htmlspecialchars($_POST['password'] ?? ''); ?>">
                <?php if (isset($passwordError)): ?>
                    <div class="error-message" id="password-error"><?php echo $passwordError; ?></div>
                <?php endif; ?>

                <input name="confirmPassword" type="password" placeholder="Confirm your password" value="<?php echo isset($passwordError) ? '' : htmlspecialchars($_POST['confirmPassword'] ?? ''); ?>">
                <?php if (isset($confirmPasswordError)): ?>
                    <div class="error-message" id="confirmPassword-error"><?php echo $confirmPasswordError; ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="accountType">Account Type</label>
                    <select id="accountType" name="accountType" class="account-type">
                        <option value="student" selected>Student</option>
                        <option value="instructor">Instructor</option>
                    </select>
                </div>
                <?php if (isset($accountTypeError)): ?>
                    <div class="error-message" id="account-type-error"><?php echo $accountTypeError; ?></div>
                <?php endif; ?>

                <input type="submit" name="submit" class="button" value="Signup">
            </form>

            <div class="signup">
                <span class="signup">Already have an account?
                    <a href="<?php url('login') ?>">Login</a>
                </span>
            </div>
            <br>
            <a class="back-link" href="<?php url('home') ?>">Back to home</a>
        </div>
    </div>
</body>
</html>
