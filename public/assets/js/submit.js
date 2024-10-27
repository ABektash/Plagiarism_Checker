function validateAndSubmit() {
    const fileInput = document.getElementById("assignment-file");
    const fileError = document.getElementById("file-error");
    
    // Check if a file is selected
    if (fileInput.files.length === 0) {
        fileError.textContent = "Please upload a file before submitting.";
        return;
    } else {
        fileError.textContent = ""; // Clear any previous error messages
    }

    // Hide the assignment section and display the success message
    document.getElementById("assignment-section").style.display = "none";
    document.getElementById("success-section").style.display = "block";
    document.getElementById("goback").style.display = "none";

}