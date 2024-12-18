// // Function to fetch plagiarism detection results
// async function CallAPI(extractedText) {
//     const options = {
//         method: 'POST',
//         headers: {
//             accept: 'application/json',
//             'content-type': 'application/json',
//             authorization: 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiOWE5YTI2ZmYtNTJlOS00N2RkLWJmZDMtNmUxNzcwNDM5NjAwIiwidHlwZSI6ImFwaV90b2tlbiJ9.roZn8xBXOqwlQaBDmdxr5SAaIZtp0Sq6ETXar0clhF4'
//         },
//         body: JSON.stringify({
//             providers: 'originalityai',
//             text: extractedText,
//             title: 'Uploaded Assignment'
//         })
//     };

//     try {
//         const response = await fetch('https://api.edenai.run/v2/text/plagia_detection', options);
//         const result = await response.json();
//         document.getElementById('result').innerText = JSON.stringify(result, null, 2);
//     } catch (error) {
//         console.error('Error:', error);
//         document.getElementById('result').innerText = 'An error occurred. Please try again.';
//     }
// }

// // Wait for the DOM to load
// document.addEventListener('DOMContentLoaded', function () {
//     // Get the form and input elements
//     const form = document.getElementById('assignment-form-Add');
//     const pdfFileInput = document.getElementById('assignment-file');
//     const pdfTextContainer = document.getElementById('pdfText');

//     // Load the PDF.js library
//     const pdfjsLib = window['pdfjs-dist/build/pdf'];
//     pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

//     // Handle form submission
//     form.addEventListener('submit', function (e) {
//         e.preventDefault(); // Prevent the form from submitting normally

//         const file = pdfFileInput.files[0]; // Get the selected file

//         if (file && file.type === 'application/pdf') {
//             extractTextFromPDF(file);
//         } else {
//             pdfTextContainer.innerHTML = 'Please upload a valid PDF file.';
//         }
//     });

//     // Function to process and extract text from the PDF
//     async function extractTextFromPDF(file) {
//         try {
//             const fileReader = new FileReader();

//             fileReader.onload = async function (event) {
//                 const pdfData = new Uint8Array(event.target.result);

//                 const pdf = await pdfjsLib.getDocument(pdfData).promise;

//                 let extractedText = '';

//                 // Loop through all the pages and extract text
//                 for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
//                     const page = await pdf.getPage(pageNum);
//                     const textContent = await page.getTextContent();
                    
//                     // Combine the text content from the page
//                     const pageText = textContent.items.map(item => item.str).join(' ');
//                     extractedText += pageText + '\n';
//                 }

//                 // Display the extracted text
//                 pdfTextContainer.innerText = extractedText.trim();

//                 // Call the plagiarism detection API with the extracted text
//                 CallAPI(extractedText.trim());
//             };

//             // Read the PDF file as an ArrayBuffer
//             fileReader.readAsArrayBuffer(file);
//         } catch (error) {
//             pdfTextContainer.innerHTML = 'Error processing the PDF file: ' + error.message;
//         }
//     }
// });
