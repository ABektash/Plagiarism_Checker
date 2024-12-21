function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

const CreateForum = async (event) => {
    event.preventDefault();

    const urlParams = new URLSearchParams(window.location.search);
    const reportID = urlParams.get("reportID");

    if (!reportID) {
        alert("Report ID is missing.");
        return;
    }

    const formData = new FormData();

    formData.append("reportID", reportID);
    formData.append("submitCreateForum", "true");

    try {
        const response = await fetch("/Plagiarism_Checker/public/Forums/submit", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error("Server Response Error:", errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.status === "success") {
            window.location.href = `/Plagiarism_Checker/public/forums/index/${data.forumID}`;
        } else {
            alert(data.message || "Error creating the forum. Please try again.");
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        alert("An error occurred. Please try again.");
    }
};

document.addEventListener("DOMContentLoaded", () => {
    const contactInstructorBtn = document.getElementById("contact-instructor-btn");
    if (contactInstructorBtn) {
        contactInstructorBtn.addEventListener("click", CreateForum);
    }
});

document.getElementById("instructor-Finalize-btn").addEventListener("click", function() {
    const reportID = getQueryParam("reportID");
    const feedback = document.getElementById("instructor-comment").value.trim();
    const grade = parseInt(document.getElementById("instructor-grade").value, 10);

    if (isNaN(grade) || grade < 0 || grade > 100) {
        alert("Grade must be inserted a number between 0 and 100.");
        return;
    }

    if (!feedback) {
        alert("Please provide feedback.");
        return;
    }

    fetch('/Plagiarism_Checker/public/ViewReport/FinalizeReport', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                reportID: reportID,
                feedback: feedback,
                grade: grade,
            }),
        })
        .then(response => {
            if (response.headers.get('Content-Type').includes('application/json')) {
                return response.json();
            } else {
                return response.text().then(html => {
                    throw new Error("Unexpected response format. Check the server for issues.");
                });
            }
        })
        .then(data => {
            if (data.status === 'success') {
                window.location.href = '/Plagiarism_Checker/public/dashboard';
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            alert("An error occurred. Please try again.");
        });
});
