
async function CallAPI(extractedText, extractedTitle) {
    const options = {
        method: 'POST',
        headers: {
            accept: 'application/json',
            'content-type': 'application/json',
            authorization: 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiOWE5YTI2ZmYtNTJlOS00N2RkLWJmZDMtNmUxNzcwNDM5NjAwIiwidHlwZSI6ImFwaV90b2tlbiJ9.roZn8xBXOqwlQaBDmdxr5SAaIZtp0Sq6ETXar0clhF4'
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
        document.getElementById('result').innerText = JSON.stringify(result, null, 2);
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('result').innerText = 'An error occurred. Please try again.';
    }
}


