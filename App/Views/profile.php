<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                                <i class="fa fa-file fs-6 d-block mb-2"></i>
                                <h4 class="mb-0 fw-semibold lh-1">8</h4>
                                <p class="mb-0 fs-4">Posts</p>
                            </div>
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
                                <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle" style="width: 110px; height: 110px;">
                                    <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;">
                                        <img src="/Plagiarism_Checker/public/assets/images/defaultpic.jpg" alt class="w-100 h-100">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <h5 class="fs-5 mb-0 fw-semibold">Youssef Abdelshahid</h5>
                                <p class="mb-0 fs-4">Instructor</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 order-last">
                        <ul class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start my-3 gap-3">
                            <li><button class="btn btn-primary" onclick="window.location.href='<?php url('editProfile/index'); ?>'">Edit profile</button></li>
                            <li><button class="btn btn-primary" onclick="window.location.href='<?php url('dashboard/index'); ?>'">Back</button></li>
                        </ul>
                    </div>
                </div>
                <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-posts-tab" data-bs-toggle="pill" data-bs-target="#pills-posts" type="button" role="tab" aria-controls="pills-posts" aria-selected="true">
                            <i class="fa fa-file-text me-2 fs-6"></i>
                            <span class="d-none d-md-block">Posts</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-assignments-tab" data-bs-toggle="pill" data-bs-target="#pills-assignments" type="button" role="tab" aria-controls="pills-assignments" aria-selected="false">
                            <i class="fa fa-check-square me-2 fs-6"></i>
                            <span class="d-none d-md-block">Assignments</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-groups-tab" data-bs-toggle="pill" data-bs-target="#pills-groups" type="button" role="tab" aria-controls="pills-groups" aria-selected="false">
                            <i class="fa fa-users me-2 fs-6"></i>
                            <span class="d-none d-md-block">Groups</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="pills-tabContent">
            <!-- Posts Tab -->
            <div class="tab-pane fade show active" id="pills-posts" role="tabpanel" aria-labelledby="pills-posts-tab">
                <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                    <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Posts </h3>
                    <form class="position-relative">
                        <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search Posts">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                    </form>
                </div>
                <div class="row">
                    <!-- Card 1 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Post Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Post Title</h5>
                                <p class="card-text">This is a card with content related to a post.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Post Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Post Title</h5>
                                <p class="card-text">This is a card with content related to a post.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Post Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Post Title</h5>
                                <p class="card-text">This is a card with content related to a post.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignments Tab -->
            <div class="tab-pane fade" id="pills-assignments" role="tabpanel" aria-labelledby="pills-assignments-tab">
                <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                    <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Assignments </h3>
                    <form class="position-relative">
                        <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search Assignments">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                    </form>
                </div>
                <div class="row">
                    <!-- Card 1 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Assignment Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Assignment Title</h5>
                                <p class="card-text">This is a card with content related to an assignment.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Assignment Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Assignment Title</h5>
                                <p class="card-text">This is a card with content related to an assignment.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Assignment Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Assignment Title</h5>
                                <p class="card-text">This is a card with content related to an assignment.</p>
                                <a href="#" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Groups Tab -->
            <div class="tab-pane fade" id="pills-groups" role="tabpanel" aria-labelledby="pills-groups-tab">
                <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                    <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Groups </h3>
                    <form class="position-relative">
                        <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search Groups">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                    </form>
                </div>
                <div class="row">
                    <!-- Card 1 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Group Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Group Title</h5>
                                <p class="card-text">This is a card with content related to a group.</p>
                                <a href="#" class="btn btn-primary">View Group</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Group Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Group Title</h5>
                                <p class="card-text">This is a card with content related to a group.</p>
                                <a href="#" class="btn btn-primary">View Group</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">Group Title</div>
                            <div class="card-body">
                                <h5 class="card-title">Group Title</h5>
                                <p class="card-text">This is a card with content related to a group.</p>
                                <a href="#" class="btn btn-primary">View Group</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    
    <?php include 'inc/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>