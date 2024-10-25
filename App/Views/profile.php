<!DOCTYPE html>
<html lang="en">

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/file.css"> -->
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/profile.css">



</head>

<body>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <?php include 'inc/header.php'; ?>
    <br>
    <div class="container">
        <div class="card overflow-hidden">
            <div class="card-body p-0">
                <img src="/Plagiarism_Checker/public/assets/images/yy66.jpg" alt class="img-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-4 order-lg-1 order-2">
                        <div class="d-flex align-items-center justify-content-around m-4">
                            <div class="text-center">
                                <i class="fa fa-user fs-6 d-block mb-2"></i>
                                <h4 class="mb-0 fw-semibold lh-1">3</h4>
                                <p class="mb-0 fs-4">Groups</p>
                            </div>
                            <div class="text-center">
                                <i class="fa fa-check fs-6 d-block mb-2"></i>
                                <h4 class="mb-0 fw-semibold lh-1">6</h4>
                                <p class="mb-0 fs-4">Assignments done</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-n3 order-lg-2 order-1">
                        <div class="mt-n5">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 110px; height: 110px;">
                                    <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                                        style="width: 100px; height: 100px;">
                                        <img src="/Plagiarism_Checker/public/assets/images/defaultpic.jpg" alt
                                            class="w-100 h-100">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="fs-5 mb-0 fw-semibold"><?php echo $_SESSION['user']['FirstName'] . " " . $_SESSION['user']['LastName'] ?></h5>
                                <?php
                                if ($_SESSION['user']['UserType_id'] == 1) {
                                    echo "<p class='mb-0 fs-4'>Admin</p>";
                                } elseif ($_SESSION['user']['UserType_id'] == 2) {
                                    echo "<p class='mb-0 fs-4'>Instructor</p>";
                                } elseif ($_SESSION['user']['UserType_id'] == 3) {
                                    echo "<p class='mb-0 fs-4'>Student</p>";
                                } else {
                                    echo "<p class='mb-0 fs-4'>Visitor</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 order-last">
                        <ul class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start my-3 gap-3">
                            <li><button class="btn btn-primary" onclick="window.location.href='<?php url('editProfile/index'); ?>'">Edit profile</button></li>
                            <li><button class="btn btn-primary" onclick="history.back()">Back</button></li>
                        </ul>
                    </div>
                </div>
                <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
                    <?php
                        if ($_SESSION['user']['UserType_id'] == 1 || $_SESSION['user']['UserType_id'] == 4) {

                        } elseif ($_SESSION['user']['UserType_id'] == 2) {
                            echo '<li class="nav-item" role="presentation">';
                            echo '<button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-assignments-tab" data-bs-toggle="pill" data-bs-target="#pills-assignments" type="button" role="tab" aria-controls="pills-assignments" aria-selected="false">';
                            echo '<i class="fa fa-check-square me-2 fs-6"></i>';
                            echo '<span class="d-none d-md-block">Assignments</span>';
                            echo '</button>';
                            echo '</li>';
                        } elseif ($_SESSION['user']['UserType_id'] == 3) {
                            echo '<li class="nav-item" role="presentation">';
                            echo '<button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-assignments-submitted-tab" data-bs-toggle="pill" data-bs-target="#pills-assignments-submitted" type="button" role="tab" aria-controls="pills-assignments-submitted" aria-selected="false">';
                            echo '<i class="fa fa-check-square me-2 fs-6"></i>';
                            echo '<span class="d-none d-md-block">Assignments Submitted</span>';
                            echo '</button>';
                            echo '</li>';
                        }
                    ?>
                    </ul>

            </div>
        </div>

        <!-- Start of the tab content area -->
        <div class="tab-content" id="pills-tabContent">
            <?php
            $userType = $_SESSION['user']['UserType_id'];

            if ($userType == 1 || $userType == 4) {

            } elseif ($userType == 2) {
            ?>
                <div class="tab-pane fade show active" id="pills-assignments" role="tabpanel" aria-labelledby="pills-assignments-tab">
                    <h3 class="mb-3 fw-semibold">Assignments Made</h3>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header">12 DEC 2024, 18:00 pm</div>
                                <div class="card-body">
                                    <h5 class="card-title">Assignment Title</h5>
                                    <p class="card-text">This is a card with content related to an assignment made by the instructor.</p>
                                    <a href="#" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } elseif ($userType == 3) {
            ?>
                <div class="tab-pane fade show active" id="pills-assignments" role="tabpanel" aria-labelledby="pills-assignments-tab">
                    <h3 class="mb-3 fw-semibold">Assignments Submitted</h3>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header">12 DEC 2024, 18:00 pm</div>
                                <div class="card-body">
                                    <h5 class="card-title">Assignment Title</h5>
                                    <p class="card-text">This is a card with content related to an assignment submitted by the student.</p>
                                    <a href="#" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>



    </div>


    <?php include 'inc/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>