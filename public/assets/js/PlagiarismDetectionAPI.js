// // Function to fetch plagiarism detection results
// async function testAPIIIII() {
//   const options = {
//       method: 'POST',
//       headers: {
//           accept: 'application/json',
//           'content-type': 'application/json',
//           authorization: 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiOWE5YTI2ZmYtNTJlOS00N2RkLWJmZDMtNmUxNzcwNDM5NjAwIiwidHlwZSI6ImFwaV90b2tlbiJ9.roZn8xBXOqwlQaBDmdxr5SAaIZtp0Sq6ETXar0clhF4'
//       },
//       body: JSON.stringify({
//           providers: 'originalityai',
//           text: 'essay, an analytic, interpretative, or critical literary composition usually much shorter and less systematic and formal than a dissertation or thesis and usually dealing with its subject from a limited and often personal point of view.Some early treatises—such as those of Cicero on the pleasantness of old age or on the art of divination, Seneca on anger or clemency, and Plutarch on the passing of oracles—presage to a certain degree the form and tone of the essay, but not until the late 16th century was the flexible and deliberately nonchalant and versatile form of the essay perfected by the French writer Michel de Montaigne. Choosing the name essai to emphasize that his compositions were attempts or endeavours, a groping toward the expression of his personal thoughts and experiences, Montaigne used the essay as a means of self-discovery. His Essais, published in their final form in 1588, are still considered among the finest of their kind. Later writers who most nearly recall the charm of Montaigne include, in England, Robert Burton, though his whimsicality is more erudite, Sir Thomas Browne, and Laurence Sterne, and in France, with more self-consciousness and pose, André Gide and Jean Cocteau.',
//           title: 'essay'
//       })
//   };

//   try {
//       const response = await fetch('https://api.edenai.run/v2/text/plagia_detection', options);
//       const result = await response.json();
//       document.getElementById('result').innerText = JSON.stringify(result, null, 2);
//   } catch (error) {
//       console.error('Error:', error);
//       document.getElementById('result').innerText = 'An error occurred. Please try again.';
//   }
// }

// // Attach the event listener to the form
// document.getElementById('plagiarismForm').addEventListener('submit', (event) => {
//   event.preventDefault(); // Prevent form submission from reloading the page
//   testAPIIIII(); // Call the function
// });
