function validateAndSubmit() {
    const fileInput = document.getElementById("assignment-file");
    const fileError = document.getElementById("file-error");
    const allowedExtension = /(\.pdf)$/i; // Regex to allow only PDF files

    // Check if a file is selected
    if (fileInput.files.length === 0) {
        fileError.textContent = "Please upload a file before submitting.";
        return;
    } else if (!allowedExtension.test(fileInput.value)) {
        // Validate file format
        fileError.textContent = "Only PDF files are allowed.";
        return;
    } else {
        fileError.textContent = ""; // Clear any previous error messages
    }
    document.getElementById("assignment-section").style.display = "none";
    document.getElementById("success-section").style.display = "block";
    document.getElementById("goback").style.display = "none";
}
