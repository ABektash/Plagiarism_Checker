async function detectPlagiarism() {
    const text = document.getElementById("text-input").value; // Get text input
  
    const payload = {
      response_as_dict: true,
      attributes_as_list: false,
      show_base_64: true,
      show_original_response: false,
      providers: 'amazon,microsoft,google',
      text: "Develop a robust plagiarism detection system that ensures academic integrity by identifying and highlighting instances of copied content in student submissions. This tool assists educators in maintaining standards of originality and provides students with feedback to improve their writing skills.",
    };
  
    try {
      // Make a POST request to your backend
      const response = await fetch('/test/detectPlagiarism', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
  
      if (!response.ok) {
        throw new Error("Failed to call the API");
      }
  
      const result = await response.json();
      console.log(result);
  
      // Display results in the DOM
      document.getElementById("result").innerHTML =
        `<pre>${JSON.stringify(result, null, 2)}</pre>`;
    } catch (error) {
      console.error(error);
      document.getElementById("result").innerText = "Error detecting plagiarism.";
    }
  }
  