<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/editProfile.css">
</head>
<?php
// $_SESSION['user']['firstName']
?>

<body class="body">
<?php include 'inc/header.php'; ?>
    <div class="container-xl px-4 mt-4">

        <br><br><br><br>

        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header"></div>
                    <div class="card-body text-center">
                        <img class="img-account-profile rounded-circle mb-2" src="/Plagiarism_Checker/public/assets/images/editPassword.jpg" alt="Profile Image">
                        <div class="small font-italic text-muted mb-4"></div>
                        <button class="btn btn-primary" type="button">Change Password</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">Account Details</div>
                    <div class="card-body">
                        <form action="<?php url('editProfile/submit'); ?>" method="POST">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputFirstName">First name</label>
                                    <input class="form-control" id="inputFirstName" name="firstName" type="text" value="<?php echo $_SESSION['user']['FirstName'] ?? ''; ?>" placeholder="Enter your first name">
                                    <?php if (isset($firstNameError)): ?>
                                        <div class="error-message" id="fname-error"><?php echo $firstNameError; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputLastName">Last name</label>
                                    <input class="form-control" id="inputLastName" name="lastName" type="text" value="<?php echo $_SESSION['user']['LastName'] ?? ''; ?>" placeholder="Enter your last name">
                                    <?php if (isset($lastNameError)): ?>
                                        <div class="error-message" id="lname-error"><?php echo $lastNameError; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputOrgName">Organization name</label>
                                    <input class="form-control" id="inputOrgName" name="organizationName" type="text" value="<?php echo $_SESSION['user']['Organization'] ?? ''; ?>" placeholder="Enter your organization name">
                                    <?php if (isset($organizationNameError)): ?>
                                        <div class="error-message" id="organizationName-error"><?php echo $organizationNameError; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputAddress">Address</label>
                                    <input class="form-control" id="inputAddress" name="address" type="text" value="<?php echo $_SESSION['user']['Address'] ?? ''; ?>" placeholder="Enter your address">
                                    <?php if (isset($addressError)): ?>
                                        <div class="error-message" id="address-error"><?php echo $addressError; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="inputEmailAddress">Email address</label>
                                <input class="form-control" id="inputEmailAddress" name="email" type="email" value="<?php echo $_SESSION['user']['Email'] ?? ''; ?>" placeholder="Enter your email address">
                                <?php if (isset($emailError)): ?>
                                    <div class="error-message" id="email-error"><?php echo $emailError; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputPhone">Phone number</label>
                                    <input class="form-control" id="inputPhone" name="phone" type="tel" value="<?php echo $_SESSION['user']['PhoneNumber'] ?? ''; ?>" placeholder="Enter your phone number">
                                    <?php if (isset($phoneError)): ?>
                                        <div class="error-message" id="phone-error"><?php echo $phoneError; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputBirthday">Birthday</label>
                                    <?php if ($_SESSION['user']['Birthday'] == "0000-00-00") $_SESSION['user']['Birthday'] = "";?>
                                    <input class="form-control" id="inputBirthday" name="birthday" type="text" value="<?php echo $_SESSION['user']['Birthday']?? ''; ?>" placeholder="MM/DD/YYYY">
                                    <?php if (isset($birthdayError)): ?>
                                        <div class="error-message" id="birthday-error"><?php echo $birthdayError; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">Save changes</button>
                            <button class="btn btn-primary" type="button" onclick="window.location.href='<?php url('profile/index'); ?>'">Back to profile</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
