async function CallAPI(extractedText, extractedTitle, submissionID) {
    const options = {
        method: 'POST',
        headers: {
            accept: 'application/json',
            'content-type': 'application/json',
            //authorization: ENV.API_KEY,
        },

        body: JSON.stringify({
            providers: 'originalityai',
            text: extractedText,
            title: extractedTitle
        })
    };

    try {
        const response = await fetch('https://api.edenai.run/v2/text/plagia_detection', options);
        const result = await response.json();

        await savePlagiarismReport(submissionID, result);

    } catch (error) {
        console.error('Error:', error);
        document.getElementById('result').innerText = 'An error occurred. Please try again.';
    }
}

async function savePlagiarismReport(submissionID, apiResponse) {
    const response = await fetch('/Plagiarism_Checker/public/ViewReport/saveReport', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            submissionID: submissionID,
            responseAPI: JSON.stringify(apiResponse),
        }),
    });

    const result = await response.json();
    if (result.success) {
        console.log('Report saved successfully.');
    } else {
        console.error('Failed to save the report:', result.message);
    }
}

async function submitAssignment(assignmentID, extractedText) {
    try {
        const response = await fetch('/Plagiarism_Checker/public/SubmitAssignment/submitAssignment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                assignmentID: assignmentID,
                extractedText: extractedText,
            }),
        });

        const result = await response.json();

        if (result.success) {
            return result.submissionID;
        } else {
            return null;
        }
    } catch (error) {
        alert('Failed to submit the assignment due to a network error. Please check your connection and try again.');
        return null;
    }
}

async function extractTextFromPDF(file) {
    try {
        const fileReader = new FileReader();

        return new Promise((resolve, reject) => {
            fileReader.onload = async function (event) {
                const pdfData = new Uint8Array(event.target.result);
                const pdf = await pdfjsLib.getDocument(pdfData).promise;

                let extractedText = '';

                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                    const page = await pdf.getPage(pageNum);
                    const textContent = await page.getTextContent();

                    const pageText = textContent.items.map(item => item.str).join(' ');
                    extractedText += pageText + '\n';
                }

                resolve(extractedText.trim());
            };

            fileReader.onerror = (error) => reject(error);
            fileReader.readAsArrayBuffer(file);
        });
    } catch (error) {
        return null;
    }
}