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
                  <td><a class="a-link" href="/Plagiarism_Checker/public/adminProfile/index/${student.student_id}">
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
  
  // Global function to update the group selection dropdown
  function updateGroupSelection() {
      const groupSelection = document.getElementById('groupSelection'); // Ensure this element exists
  
      if (!groupSelection) {
          console.error('Group selection dropdown not found');
          return;
      }
  
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
  }
 // Global function to update the group selection dropdown At Ammar Assignment
 function updateGroupNumberEdit() {
    const groupSelection = document.getElementById('Group-Number-Edit'); // Ensure this element exists

    if (!groupSelection) {
        console.error('Group selection dropdown not found');
        return;
    }

    fetch('/Plagiarism_Checker/public/manageGroups/getAvailableGroups')  // Correct API endpoint
        .then(response => response.json())
        .then(groups => {
            groupSelection.innerHTML = ''; // Clear existing options

            // If there are groups, populate the dropdown
            if (groups.length > 0) {
                groups.forEach((group, index) => {
                    const option = document.createElement('option');
                    option.value = group.group_id; // Correct reference to group_id
                    option.textContent = group.group_id; // Correctly display the group ID
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
}

  // Global function to update the instructors dropdown
  function updateInstructorsDropdown(groupID) {
      console.log("Updating instructors dropdown for group ID:", groupID); // Debugging
  
      const instructorDropdown = document.querySelector('.Instructor-Selection');
      if (!instructorDropdown) {
          console.error('Instructor dropdown not found');
          return;
      }
  
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
  }
  // Global function to set the selected group in the group selection dropdown
  function setSelectedGroup(groupID) {
      const groupSelection = document.getElementById('groupSelection');  // Ensure this is the correct ID
      if (!groupSelection) {
          console.error('Group selection dropdown not found');
          return;
      }
  
      // Loop through the options to find the one that matches the groupID
      const options = groupSelection.options;
      for (let i = 0; i < options.length; i++) {
          if (options[i].value == groupID) {
              groupSelection.selectedIndex = i;  // Set the selected option by index
              break;  // Exit the loop once the matching option is found
          }
      }
  }
  
  
  
  // Ensure the function is available on page load
  document.addEventListener('DOMContentLoaded', function () {
      // Call the global function to update the group selection dropdown
      updateGroupSelection();
      updateGroupNumberEdit();

  });
  
  
  
  
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
  
    const FirstGroupID = groupSelection.options.length > 0 ? groupSelection.options[0].value : 1;
  
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
                  updateInstructorsDropdown(groupID);
                  setSelectedGroup(groupID);
  
  
                  
                  addModal.style.display = "none";
                  studentIDInput.value = '';
                  groupIDInput.value = '';
              } else {
                  alert(jsonData.message);
              }
          } catch (error) {
              console.error('Error parsing JSON:', error);
              alert('No Student Available with this ID.');
          }
      })
      .catch(error => {
          console.error('Error adding student:', error);
          alert('No Student Available with this ID.');
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
      const instructorIDInput = document.getElementById('instructorID');
      const groupIDInput = document.getElementById('inst-groupID');
  
      // Open modal button
      const openInstructorModalBtn = document.querySelector('.add-instructor-btn');
      openInstructorModalBtn.addEventListener('click', function () {
          addInstructorModal.style.display = 'block';
      });
  
      // Close modal on cancel
      cancelAddInstructorBtn.addEventListener('click', function () {
          addInstructorModal.style.display = 'none';
          instructorIDInput.value = '';
          groupIDInput.value = '';
      });
  
      // Save instructor logic
      addInstructorSaveBtn.addEventListener('click', function () {
          const instructorID = instructorIDInput.value.trim();
          const groupID = groupIDInput.value.trim();
  
          // Validate input
          if (!instructorID || !groupID || isNaN(instructorID) || isNaN(groupID)) {
              alert('Instructor ID and Group ID must be valid numeric values.');
              return;
          }
  
          // Send data to the backend to add instructor
          fetch('/Plagiarism_Checker/public/manageGroups/addInstructorToGroup', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ instructorID, groupID }),
          })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert(data.message);
  
                      // Refresh the instructor select tag with updated data for the selected group
                      updateInstructorsDropdown(groupID); // This now calls the global function
                      updateStudentTable(groupID); // Update the table after deletion
                      setSelectedGroup(groupID);  
  
                      // Reset form and close modal
                      instructorIDInput.value = '';
                      groupIDInput.value = '';
                      addInstructorModal.style.display = 'none';
                  } else {
                      alert(data.message);
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert('Failed to add instructor. Please try again.');
              });
      });
  
      // Initial population of instructors when the page loads
      const groupID = groupIDInput.value.trim();
      if (groupID) {
          updateInstructorsDropdown(groupID); // This now calls the global function
      }
  
      // Re-fetch instructor list when the group selection changes
      groupIDInput.addEventListener('change', function () {
          const newGroupID = groupIDInput.value.trim();
          if (newGroupID) {
              updateInstructorsDropdown(newGroupID); // This now calls the global function
          }
      });
  });
  
  //////////////////////////////////////////////////Create Group///////////////////////////////////////////////////////////////////////////
  document.addEventListener('DOMContentLoaded', function () {
      const createGroupBtn = document.querySelector('.create-group-btn');
  
      if (createGroupBtn) {
          createGroupBtn.addEventListener('click', function () {
              // Send request to create a new group with auto-generated ID
              fetch('/Plagiarism_Checker/public/manageGroups/addGroup', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({})  // No group name required
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('New group created successfully.');
  
                      // Refresh the group selection and instructor/student tables
                      updateGroupSelection();
                      // updateInstructorsDropdown(data.group_id);  // Use the new group ID
                      // updateStudentTable(data.group_id);
                  } else {
                      alert('Failed to create group: ' + data.message);
                  }
              })
              .catch(error => {
                  console.error('Error creating group:', error);
                  alert('An error occurred while creating the group.');
              });
          });
      }
  });
  //////////////////////////////////////////////////Delete Group///////////////////////////////////////////////////////////////////////////
  document.addEventListener('DOMContentLoaded', function () {
      const deleteGroupModal = document.getElementById('DeleteGroupModal');
      const confirmDeleteGroupBtn = document.getElementById('confirmDeleteGroupBtn');
      const cancelDeleteGroupBtn = document.getElementById('cancelDeleteGroupBtn');
      const deleteGroupBtn = document.querySelector('.delete-group-btn');
      let groupIDToDelete = null;
  
      // Show the modal when the "Delete Group" button is clicked
      deleteGroupBtn.addEventListener('click', function () {
          groupIDToDelete = getSelectedGroupID(); // Get the selected group ID
          if (groupIDToDelete) {
              deleteGroupModal.style.display = 'block'; // Show the modal
          } else {
              alert('Please select a group to delete');
          }
      });
  
      // Hide the modal when the "No" button is clicked
      cancelDeleteGroupBtn.addEventListener('click', function () {
          deleteGroupModal.style.display = 'none'; // Hide the modal
      });
  
      // Delete the group when the "Yes" button is clicked
      confirmDeleteGroupBtn.addEventListener('click', function () {
          if (groupIDToDelete) {
              deleteGroup(groupIDToDelete);
          } else {
              alert('No group selected for deletion');
          }
          deleteGroupModal.style.display = 'none'; // Hide the modal after action
      });
  
      // Function to get the selected group ID (implement according to your logic)
      function getSelectedGroupID() {
          const groupSelection = document.getElementById('groupSelection');
          return groupSelection ? groupSelection.value : null;  // Return selected group ID
      }
  
      // Function to delete the group and associated users
      function deleteGroup(groupID) {
          fetch('/Plagiarism_Checker/public/manageGroups/deleteGroup', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ groupID })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Group deleted successfully');
                  // Optionally, refresh the group selection dropdown and other UI elements
                  updateGroupSelection();  // Ensure this is defined globally
                  const FirstGroupID = groupSelection.options.length > 0 ? groupSelection.options[0].value : 1;
                  updateInstructorsDropdown(FirstGroupID);  // Update instructors if necessary
                  updateStudentTable(FirstGroupID);  // Update student table if necessary
              } else {
                  alert('Error deleting group: ' + data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Failed to delete group');
          });
      }
  });
  
  
  })();
  