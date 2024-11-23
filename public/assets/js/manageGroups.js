(() => {
  let rowToDelete;
  let instructorToRemove;

  const deleteButtons = document.querySelectorAll(".Delete-std-btn");
  const deleteModal = document.getElementById("deleteModal");
  const yesBtn = document.getElementById("yes-btn");
  const noBtn = document.getElementById("no-btn");
  const saveBtn = document.getElementById("save-btn");

  const tableBody = document.querySelector('#studentsTable tbody');
const groupSelection = document.getElementById('groupSelection');

// Global function to update the student table
function updateStudentTable(groupID) {
  fetch(`/Plagiarism_Checker/public/manageGroups/getStudentsByGroup/${groupID}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.text();  // Get raw response text first
    })
    .then(data => {
      console.log('Raw response data:', data);  // Log the raw response

      try {
        const parsedData = JSON.parse(data);  // Try parsing JSON
        console.log('Parsed JSON:', parsedData);

        tableBody.innerHTML = ''; // Clear the table

        if (parsedData.length > 0) {
          parsedData.forEach(student => {
            tableBody.innerHTML += `
              <tr>
                <td>${student.student_id}</td>
                <td>${student.student_name}</td>
                <td>${student.student_email}</td>
                <td><a class="a-link" href="/Plagiarism_Checker/adminProfile/index/${student.student_id}">
                        <i class='bx bxs-user'></i>
                    </a></td>
                <td><a class="delete-a-link" href="#" 
                       data-student-id="${student.student_id}" 
                       onclick="return confirmDelete(${student.student_id}, ${groupID});">
                        <i class='bx bx-trash'></i>
                    </a></td>
              </tr>`;
          });
        } else {
          tableBody.innerHTML = '<tr><td colspan="5">No students found for this group.</td></tr>';
        }
      } catch (error) {
        console.error('Error parsing JSON:', error);
        alert('Error parsing data while updating the student table.');
      }
    })
    .catch(error => {
      console.error('Error fetching data:', error);
      alert('Error fetching student data.');
    });
}

  (() => {
    let rowToDelete;
    const deleteButtons = document.querySelectorAll(".delete-a-link");
    const deleteModal = document.getElementById("deleteModal");
    const yesBtn = document.getElementById("yes-btn");
    const noBtn = document.getElementById("no-btn");
  
    deleteButtons.forEach((button) => {
      button.addEventListener("click", function (event) {
        event.preventDefault();  // Prevents immediate navigation
        rowToDelete = button.closest("tr");  // Save the row to delete
        deleteModal.style.display = "block"; // Show the delete confirmation modal
      });
    });
  
    // Confirm delete action
    yesBtn.addEventListener("click", function () {
      if (rowToDelete) {
        rowToDelete.remove(); // Remove the row from the table
        deleteModal.style.display = "none"; // Hide the modal after deletion
      }
    });
  
    // Cancel delete action
    noBtn.addEventListener("click", function () {
      deleteModal.style.display = "none"; // Hide the modal without deletion
    });
  
    // Close modal when clicking outside of it
    window.addEventListener("click", (event) => {
      if (event.target === deleteModal) {
        deleteModal.style.display = "none";
      }
    });
  })();
  

  const nameError = document.getElementById("nameError");
  const emailError = document.getElementById("emailError");

  const validateEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

  const validateName = (name) => /^[A-Za-z\s]+$/.test(name);

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      rowToDelete = button.closest("tr");
      deleteModal.style.display = "block";
    });
  });

  yesBtn.addEventListener("click", function () {
    if (rowToDelete) {
      rowToDelete.remove();
      deleteModal.style.display = "none";
    }
  });

  noBtn.addEventListener("click", function () {
    deleteModal.style.display = "none";
  });
  // <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
// Group Selection (unchanged)
document.addEventListener('DOMContentLoaded', function () {
 

  // Function to fetch and update the group selection dropdown
  const updateGroupSelection = () => {
      fetch('/Plagiarism_Checker/public/manageGroups/getAvailableGroups')  // Correct API endpoint
          .then(response => response.json())
          .then(groups => {
              groupSelection.innerHTML = ''; // Clear existing options

              // If there are groups, populate the dropdown
              if (groups.length > 0) {
                  groups.forEach((group, index) => {
                      const option = document.createElement('option');
                      option.value = group.group_id; // Correct reference to group_id
                      option.textContent = 'Group ' + group.group_id; // Correctly display the group ID
                      groupSelection.appendChild(option);

                      // Set the first group as the default selection
                      if (index === 0) {
                          option.selected = true;
                      }
                  });
              } else {
                  // If no groups, show a default message (optional)
                  const option = document.createElement('option');
                  option.textContent = 'No groups available';
                  option.disabled = true;
                  groupSelection.appendChild(option);
              }
          })
          .catch(error => {
              console.error('Error fetching groups:', error);
          });
  };

  // Call the function to update the group selection dropdown
  updateGroupSelection();
});

// <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

// Student Table (updated to show students for the default group when page loads)
document.addEventListener('DOMContentLoaded', function () {
 
  const deleteModal = document.getElementById('deleteModal');
  const yesBtn = document.getElementById('yes-btn');
  const noBtn = document.getElementById('no-btn');
  let studentToDelete = null; // Variable to store student info to delete

  // Show the confirmation modal when the delete button is clicked
  window.confirmDelete = (studentID, groupID) => {
      studentToDelete = { studentID, groupID }; // Store the student and group information
      deleteModal.style.display = "block"; // Show the modal
      return false;  // Prevent default link behavior
  };

  // Confirm delete action (Yes button)
  yesBtn.addEventListener("click", function () {
      if (studentToDelete) {
          const { studentID, groupID } = studentToDelete;
          // Send DELETE request to remove the student from the database
          fetch(`/Plagiarism_Checker/public/manageGroups/deleteStudentFromGroup`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ studentID, groupID })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  updateStudentTable(groupID); // Update the table after deletion
              } else {
                  alert('Failed to remove student');
              }
              deleteModal.style.display = "none"; // Hide the modal after action
          })
          .catch(error => {
              console.error('Error deleting student:', error);
              alert('Error deleting student');
              deleteModal.style.display = "none"; // Hide the modal even if error occurs
          });
      }
  });

  // Cancel delete action (No button)
  noBtn.addEventListener("click", function () {
      deleteModal.style.display = "none"; // Hide the modal without deletion
  });

  // Close modal when clicking outside of it
  window.addEventListener("click", (event) => {
      if (event.target === deleteModal) {
          deleteModal.style.display = "none";
      }
  });

  // Ensure there's a valid groupID when page loads
  const defaultGroupID = groupSelection.value || 1;  // Default to group ID 1 if no group is selected
  updateStudentTable(defaultGroupID);  // Load students for the default group

  // Add event listener for when the selection changes
  groupSelection.addEventListener('change', function () {
      updateStudentTable(this.value);  // Update the student table for the selected group
  });
});

// <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

// Instructor Dropdown (updated to show instructors for the default group when page loads)
document.addEventListener('DOMContentLoaded', function () {
 
  const instructorDropdown = document.querySelector('.Instructor-Selection');
  const removeInstructorModal = document.getElementById('removeInstructorModal');
  const confirmRemoveInstructorBtn = document.getElementById('confirmRemoveInstructorBtn');
  const cancelRemoveInstructorBtn = document.getElementById('cancelRemoveInstructorBtn');
  let instructorToRemove = null; // Store instructor to be removed

  // Function to fetch and update the instructors dropdown
  const updateInstructorsDropdown = (groupID) => {
      fetch(`/Plagiarism_Checker/public/manageGroups/getInstructorsByGroup/${groupID}`)
          .then(response => response.json())
          .then(data => {
              instructorDropdown.innerHTML = ''; // Clear the dropdown

              if (data.length > 0) {
                  data.forEach((instructor, index) => {
                      const option = document.createElement('option');
                      option.value = instructor.inst_id;
                      option.textContent = instructor.inst_name;

                      // If it's the first instructor, set it as the selected option
                      if (index === 0) {
                          option.selected = true; // Select the first instructor by default
                      }

                      instructorDropdown.appendChild(option);
                  });
              } else {
                  const option = document.createElement('option');
                  option.textContent = 'No instructors found';
                  option.disabled = true;
                  instructorDropdown.appendChild(option);
              }
          })
          .catch(error => {
              console.error('Error fetching instructors:', error);
          });
  };

  // Function to fetch the first available group and load the instructors
  const fetchFirstGroupAndLoadInstructors = () => {
      fetch('/Plagiarism_Checker/public/manageGroups/getAvailableGroups')
          .then(response => response.json())
          .then(groups => {
              if (groups.length > 0) {
                  const firstGroupID = groups[0].group_id; // Get the first group ID
                  groupSelection.value = firstGroupID; // Set the dropdown to the first group
                  updateInstructorsDropdown(firstGroupID); // Load instructors for the first group
              } else {
                  const option = document.createElement('option');
                  option.textContent = 'No groups available';
                  option.disabled = true;
                  instructorDropdown.appendChild(option);
              }
          })
          .catch(error => {
              console.error('Error fetching groups:', error);
          });
  };

  // Initial load for instructors based on the first group
  fetchFirstGroupAndLoadInstructors();

  // Add event listener for when the selection changes
  groupSelection.addEventListener('change', function () {
      updateInstructorsDropdown(this.value); // Update the instructor dropdown for the selected group
  });

  // Show the confirmation modal when the remove instructor button is clicked
  const removeInstructorBtn = document.querySelector('.remove-instructor-btn');
  removeInstructorBtn.addEventListener('click', function () {
      const instructorID = instructorDropdown.value; // Get the selected instructor's ID
      console.log('Selected instructor ID:', instructorID); // Debugging the selected value

      // Check if an instructor is selected
      if (instructorID && instructorID !== '') {
          instructorToRemove = instructorID; // Store instructor to be removed
          removeInstructorModal.style.display = "block"; // Show the modal
      } else {
          alert('Please select an instructor to remove');
      }
  });

  // Confirm remove action (Yes button)
  confirmRemoveInstructorBtn.addEventListener('click', function () {
      if (instructorToRemove) {
          const groupID = groupSelection.value; // Get the selected group ID
          // Send DELETE request to remove the instructor from the database
          fetch(`/Plagiarism_Checker/public/manageGroups/deleteInstructorFromGroup`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ instructorID: instructorToRemove, groupID })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  updateInstructorsDropdown(groupID); // Update the instructor dropdown
              } else {
                  alert('Failed to remove instructor');
              }
              removeInstructorModal.style.display = "none"; // Hide the modal after action
          })
          .catch(error => {
              console.error('Error removing instructor:', error);
              alert('Error removing instructor');
              removeInstructorModal.style.display = "none"; // Hide the modal even if error occurs
          });
      }
  });

  // Cancel remove action (No button)
  cancelRemoveInstructorBtn.addEventListener('click', function () {
      removeInstructorModal.style.display = "none"; // Hide the modal without deletion
  });

  // Close modal when clicking outside of it
  window.addEventListener("click", (event) => {
      if (event.target === removeInstructorModal) {
          removeInstructorModal.style.display = "none";
      }
  });
});
// <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

/////////////////////////////////////////////////////// ADD STUDENT MODAL ///////////////////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', function () {
  const addModal = document.getElementById('addModal');
  const addSaveBtn = document.getElementById('add-save-btn');
  const cancelAddBtn = document.getElementById('cancel-add-btn');
  const studentIDInput = document.getElementById('studentID');
  const groupIDInput = document.getElementById('groupID');

  // Fetch existing students in a group
  const fetchStudentsInGroup = async (groupID) => {
      try {
          const response = await fetch(`/Plagiarism_Checker/public/manageGroups/getStudentsByGroup/${groupID}`);
          const data = await response.json();
          return data.map(student => student.student_id); // Return array of student IDs
      } catch (error) {
          console.error('Error fetching students:', error);
          return [];
      }
  };

  // Add event listener to open modal button
  const openModalBtn = document.querySelector('.add-std-btn'); // Ensure this matches your button's class
  if (openModalBtn) {
      openModalBtn.addEventListener('click', function () {
          addModal.style.display = "block";
      });
  } else {
      console.error('Button to open modal not found.');
  }

  // Handle form submission
  addSaveBtn.addEventListener('click', async function () {
      const studentID = studentIDInput.value.trim();
      const groupID = groupIDInput.value.trim();

      // Validate inputs
      if (!studentID || !groupID || isNaN(studentID) || isNaN(groupID)) {
          alert('Both Student ID and Group ID are required and must be numeric');
          return;
      }

      // Check if student is already in the group
      const existingStudents = await fetchStudentsInGroup(groupID);
      if (existingStudents.includes(parseInt(studentID))) {
          alert('This student is already a member of the selected group.');
          return;
      }

      // Send data to the backend
      fetch('/Plagiarism_Checker/public/manageGroups/addStudentToGroup', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ studentID, groupID }),
    })
    .then(response => response.text())  // Get raw response text first
    .then(data => {
        console.log("Raw response data:", data);  // Log the raw data
    
        try {
            const jsonData = JSON.parse(data);  // Try parsing JSON
            console.log("Parsed JSON:", jsonData);  // Log parsed data
    
            if (jsonData.success) {
                alert(jsonData.message);
                updateStudentTable(groupID); // Refresh the student table
                addModal.style.display = "none";
                studentIDInput.value = '';
                groupIDInput.value = '';
            } else {
                alert(jsonData.message);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
            alert('An error occurred while adding the student.');
        }
    })
    .catch(error => {
        console.error('Error adding student:', error);
        alert('An error occurred while adding the student.');
    });
            
  });

  // Additional functionality to close modal
  cancelAddBtn.addEventListener('click', function () {
      addModal.style.display = "none";
      studentIDInput.value = '';
      groupIDInput.value = '';
  });
});
/////////////////////////////////////////////////////// ADD INSTRUCTOR MODAL ///////////////////////////////////////////////////////////

document.addEventListener('DOMContentLoaded', function () {
  const addInstructorModal = document.getElementById('addInstructorModal');
  const addInstructorSaveBtn = document.getElementById('add-instructor-save-btn');
  const cancelAddInstructorBtn = document.getElementById('cancel-add-instructor-btn');
  const instructorIDInput = document.getElementById('instructorID'); // Access the correct instructor select field
  const groupIDInput = document.getElementById('groupID');

  // Fetch existing instructors in a group
  const fetchInstructorsInGroup = async (groupID) => {
      try {
          const response = await fetch(`/Plagiarism_Checker/public/manageGroups/getInstructorsByGroup/${groupID}`);
          const data = await response.json();
          return data.map(instructor => instructor.instructor_id); // Return array of instructor IDs
      } catch (error) {
          console.error('Error fetching instructors:', error);
          return [];
      }
  };

  // Add event listener to open modal button
  const openInstructorModalBtn = document.querySelector('.add-instructor-btn'); // Ensure this matches your button's class
  if (openInstructorModalBtn) {
      openInstructorModalBtn.addEventListener('click', function () {
          addInstructorModal.style.display = "block";
      });
  } else {
      console.error('Button to open modal not found.');
  }

  // Handle form submission for adding an instructor
  addInstructorSaveBtn.addEventListener('click', async function () {
      const instructorID = instructorIDInput.value.trim();
      const groupID = groupIDInput.value.trim();

      // Log raw input values
      console.log("Raw input values:", instructorID, groupID); // Log raw input values

      // Log groupID directly
      console.log("Group ID value before parsing:", groupIDInput.value);

      // Validate that the groupID is not empty before parsing
      if (!groupID || isNaN(groupID)) {
          console.log("Invalid groupID:", groupID);
          alert('Group ID must be selected and numeric');
          return;
      }

      // Validate that instructorID is selected
      if (!instructorID || isNaN(instructorID)) {
          console.log("Invalid instructorID:", instructorID);
          alert('Instructor ID must be selected and numeric');
          return;
      }

      // Parse inputs to integers
      const parsedInstructorID = parseInt(instructorID, 10);
      const parsedGroupID = parseInt(groupID, 10);

      console.log("Parsed values:", parsedInstructorID, parsedGroupID); // Log parsed values

      // Validate inputs: ensure they're numeric and not empty
      if (isNaN(parsedInstructorID) || isNaN(parsedGroupID)) {
          console.log("Invalid input values:", parsedInstructorID, parsedGroupID); // Log values to debug
          alert('Both Instructor ID and Group ID are required and must be numeric');
          return;
      }

      // Proceed with further validation and sending data
      const existingInstructors = await fetchInstructorsInGroup(parsedGroupID);
      if (existingInstructors.includes(parsedInstructorID)) {
          alert('This instructor is already a member of the selected group.');
          return;
      }

      // Send data to the backend
      fetch('/Plagiarism_Checker/public/manageGroups/addInstructorToGroup', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify({ instructorID: parsedInstructorID, groupID: parsedGroupID }),
      })
      .then(response => response.text())  // Get raw response text first
      .then(data => {
          console.log("Raw response data:", data);  // Log the raw data
      
          try {
              const jsonData = JSON.parse(data);  // Try parsing JSON
              console.log("Parsed JSON:", jsonData);  // Log parsed data
      
              if (jsonData.success) {
                  alert(jsonData.message);
                  updateInstructorTable(parsedGroupID); // Refresh the instructor table
                  addInstructorModal.style.display = "none";
                  instructorIDInput.value = '';
                  groupIDInput.value = '';
              } else {
                  alert(jsonData.message);
              }
          } catch (error) {
              console.error('Error parsing JSON:', error);
              alert('An error occurred while adding the instructor.');
          }
      })
      .catch(error => {
          console.error('Error adding instructor:', error);
          alert('An error occurred while adding the instructor.');
      });
  });

  // Additional functionality to close modal
  cancelAddInstructorBtn.addEventListener('click', function () {
      addInstructorModal.style.display = "none";
      instructorIDInput.value = '';
      groupIDInput.value = '';
  });
});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // const addInstructorModal = document.getElementById("addInstructorModal");
  // const addInstructorSaveBtn = document.getElementById("addInstructorSaveBtn");
  // const cancelAddInstructorBtn = document.getElementById(
  //   "cancelAddInstructorBtn"
  // );

  // const instructorNameInput = document.getElementById("instructorName");
  // const instructorNameError = document.getElementById("instructorNameError");

  // document
  //   .querySelector(".add-instructor-btn")
  //   .addEventListener("click", function () {
  //     addInstructorModal.style.display = "block";
  //   });

  // cancelAddInstructorBtn.addEventListener("click", function () {
  //   addInstructorModal.style.display = "none";
  // });

  // addInstructorSaveBtn.addEventListener("click", function () {
  //   let isValid = true;

  //   if (!validateName(instructorNameInput.value.trim())) {
  //     instructorNameError.style.display = "block";
  //     instructorNameError.textContent = "Please enter a valid name.";
  //     isValid = false;
  //   } else {
  //     instructorNameError.style.display = "none";
  //   }

  //   if (isValid) {
  //     const instructorSelection = document.querySelector(
  //       ".Instructor-Selection"
  //     );
  //     const newOption = document.createElement("option");
  //     newOption.value = instructorNameInput.value.trim().replace(/\s+/g, "");
  //     newOption.textContent = instructorNameInput.value.trim();

  //     instructorSelection.appendChild(newOption);
  //     addInstructorModal.style.display = "none";
  //   }
  // });

  // const removeInstructorModal = document.getElementById(
  //   "removeInstructorModal"
  // );
  // const confirmRemoveInstructorBtn = document.getElementById(
  //   "confirmRemoveInstructorBtn"
  // );
  // const cancelRemoveInstructorBtn = document.getElementById(
  //   "cancelRemoveInstructorBtn"
  // );

  // document
  //   .querySelector(".remove-instructor-btn")
  //   .addEventListener("click", function () {
  //     const instructorSelection = document.querySelector(
  //       ".Instructor-Selection"
  //     );
  //     instructorToRemove = instructorSelection.value;
  //     removeInstructorModal.style.display = "block";
  //   });

  // confirmRemoveInstructorBtn.addEventListener("click", function () {
  //   if (instructorToRemove) {
  //     const instructorSelection = document.querySelector(
  //       ".Instructor-Selection"
  //     );
  //     instructorSelection
  //       .querySelector(`option[value="${instructorToRemove}"]`)
  //       .remove();
  //     removeInstructorModal.style.display = "none";
  //   }
  // });

  // cancelRemoveInstructorBtn.addEventListener("click", function () {
  //   removeInstructorModal.style.display = "none";
  // });
})();
