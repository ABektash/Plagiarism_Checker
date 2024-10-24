<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/file.css"> -->
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/editProfile.css">
</head>
<body class="body">
<!-- <?php include 'inc/header.php'; ?> -->
<div class="container-xl px-4 mt-4">

    <br><br><br>

    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center">
                    <img class="img-account-profile rounded-circle mb-2" src="/Plagiarism_Checker/public/assets/images/defaultpic.jpg" alt="Profile Image">
                    <div class="small font-italic text-muted mb-4"></div>
                    <button class="btn btn-primary" type="button">Upload new image</button>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                    <form>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputFirstName">First name</label>
                                <input class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLastName">Last name</label>
                                <input class="form-control" id="inputLastName" type="text" placeholder="Enter your last name">
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputOrgName">Organization name</label>
                                <input class="form-control" id="inputOrgName" type="text" placeholder="Enter your organization name" >
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputLocation">Location</label>
                                <input class="form-control" id="inputLocation" type="text" placeholder="Enter your location" >
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                            <input class="form-control" id="inputEmailAddress" type="email" placeholder="Enter your email address" >
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputPhone">Phone number</label>
                                <input class="form-control" id="inputPhone" type="tel" placeholder="Enter your phone number" >
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="inputBirthday">Birthday</label>
                                <input class="form-control" id="inputBirthday" type="text" placeholder="Enter your birthday" >
                            </div>
                        </div>
                        <button class="btn btn-primary" type="button">Save changes</button>
                        <button class="btn btn-primary" type="button" onclick="window.location.href='<?php url('profile/index'); ?>'">Back to profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <?php include 'inc/footer.php'; ?> -->
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
