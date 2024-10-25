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