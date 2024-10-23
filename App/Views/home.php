<!-- App/Views/inc/home.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/home.css">
    <link rel="stylesheet" href="/Plagiarism_Checker/public/assets/css/LoginPopup.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuItems = document.querySelectorAll('.explore-menu-list-item img');

            const headerTitle = document.querySelector('.feature h2');
            const headerParagraph = document.querySelector('.feature p');
            const headerButton = document.querySelector('.feature button');
            const featureSection = document.querySelector('.feature');

            const headerData = [{
                    title: "Student Account",
                    description: "Create and manage your student account to submit assignments for plagiarism analysis. Track your submissions and view detailed reports to improve your writing.",
                    buttonText: "Sign Up",
                    backgroundImage: "/Plagiarism_Checker/public/assets/images/yy22.jpg"
                },
                {
                    title: "Instructor Account",
                    description: "Manage your instructor account to review submissions, access plagiarism reports, and guide students on improving their originality and writing skills.",
                    buttonText: "Get Started",
                    backgroundImage: "/Plagiarism_Checker/public/assets/images/yy33.jpg"
                },
                {
                    title: "Plagiarism Detection",
                    description: "Our advanced plagiarism detection algorithms ensure that submissions are thoroughly analyzed against a vast database of sources to detect copied content.",
                    buttonText: "Check Now",
                    backgroundImage: "/Plagiarism_Checker/public/assets/images/yy4.jpg"
                },
                {
                    title: "Discussion Forum",
                    description: "Join the discussion forum to collaborate with peers, ask questions, and seek advice on how to maintain originality and improve writing quality.",
                    buttonText: "Join the Forum",
                    backgroundImage: "/Plagiarism_Checker/public/assets/images/yy5.jpg"
                }
            ];


            headerTitle.textContent = headerData[0].title;
            headerParagraph.textContent = headerData[0].description;
            headerButton.textContent = headerData[0].buttonText;
            featureSection.style.backgroundImage = `url(${headerData[0].backgroundImage})`;
            menuItems[0].classList.add('active'); 

            menuItems.forEach((item, index) => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));

                    this.classList.add('active');

                    headerTitle.textContent = headerData[index].title;
                    headerParagraph.textContent = headerData[index].description;
                    headerButton.textContent = headerData[index].buttonText;

                    featureSection.style.backgroundImage = `url(${headerData[index].backgroundImage})`;
                });
            });
        });
    </script>
</head>

<body>

    <?php include 'inc/header.php'; ?>
    <br>

    <div class="header">
        <div class="header-contents">
            <h2>Maintain Academic Integrity</h2>
            <p>Our Plagiarism Detection System is designed to uphold academic standards by identifying instances of copied content in student submissions. With robust algorithms and comprehensive reporting features, we assist educators in fostering originality and providing constructive feedback to students, helping them enhance their writing skills and understand the importance of academic integrity.</p>
            <button onclick="window.location.href='<?php url('signup/index'); ?>'">Sign Up</button>
        </div>
    </div>
    <br><br>

    <div class="explore-menu" id="explore-menu">
        <h1>Explore our features</h1>
        <!-- <p class="explore-menu-text">
            Our Plagiarism Detection System offers a comprehensive set of tools designed to ensure academic integrity and originality. From user-friendly submission management to advanced plagiarism detection algorithms, this system helps educators and students alike in maintaining high standards. With detailed reports, progress tracking, and a discussion forum, the system fosters a collaborative and insightful environment for improving writing quality and understanding proper citation practices.
        </p> -->

        <div class="explore-menu-list">
            <div class="explore-menu-list-item">
                <img class="" src="/Plagiarism_Checker/public/assets/images/student.jpg" alt="Menu Item 1" />
                <p>Student Account</p>
            </div>
            <div class="explore-menu-list-item">
                <img class="" src="/Plagiarism_Checker/public/assets/images/instructor.png" alt="Feature 2" />
                <p>Instructor Account</p>
            </div>
            <div class="explore-menu-list-item">
                <img class="" src="/Plagiarism_Checker/public/assets/images/plagiarism.png" alt="Feature 3" />
                <p>Plagiarism Detection</p>
            </div>
            <div class="explore-menu-list-item">
                <img class="" src="/Plagiarism_Checker/public/assets/images/h-removebg-preview.png" alt="Feature 4" />
                <p>Discussion Forum</p>
            </div>
        </div>

        <hr />
    </div>

    <div class="feature">
        <div class="feature-contents">
            <h2>Student Account</h2>
            <p>Create and manage your student account to submit assignments for plagiarism analysis. Track your submissions and view detailed reports to improve your writing.</p>
            <button onclick="window.location.href='<?php url('signup/index'); ?>'">Sign Up</button>
        </div>
    </div>


   

    <?php include 'inc/footer.php'; ?>

</body>

</html>