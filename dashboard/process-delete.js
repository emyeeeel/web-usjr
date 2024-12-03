document.addEventListener("DOMContentLoaded", function() {
    // Retrieve student data from sessionStorage
    const studentData = JSON.parse(sessionStorage.getItem('studentData'));
    
    // If data exists, pre-fill the form
    if (studentData) {
        document.getElementById('student_id').value = studentData.student_id;
        document.getElementById('first_name').value = studentData.first_name;
        document.getElementById('middle_name').value = studentData.middle_name;
        document.getElementById('last_name').value = studentData.last_name;
        document.getElementById('year').value = studentData.year;

        // Handle 'college' dropdown
        const collegeSelect = document.getElementById('college');
        if (collegeSelect) {
            const collegeOption = document.createElement('option');
            collegeOption.value = studentData.college;
            collegeOption.textContent = studentData.college;  // This can be changed to a more descriptive name if needed
            collegeSelect.appendChild(collegeOption);
            collegeSelect.value = studentData.college;  // Set the default selected value
        }

        // Handle 'program' dropdown
        const programSelect = document.getElementById('program');
        if (programSelect) {
            const programOption = document.createElement('option');
            programOption.value = studentData.program;
            programOption.textContent = studentData.program;  // Similarly, you can change the text if needed
            programSelect.appendChild(programOption);
            programSelect.value = studentData.program;  // Set the default selected value
        }

        console.log(studentData.college);  // Logging to check the value
        console.log(studentData.program);  // Logging to check the value

    } else {
        // If no data in sessionStorage, redirect to the dashboard
        window.location.href = 'dashboard.php';
    }
});

// Function to show a modal with a message
document.querySelector(".delete-btn").addEventListener("click", function(event) {
    event.preventDefault();  // Prevent form submission
    showModal('Are you sure you want to delete?', 'warning');
    
});

// Function to show a confirmation modal
function showModal(message, type) {
    const modal = document.getElementById("id01");
    const modalMessage = document.getElementById("modal-message");

    modalMessage.textContent = message;

    const modalContent = document.querySelector(".modal-content");
    modalContent.classList.remove("error", "success");
    modalContent.classList.add(type);

    modal.style.display = "block";
}

// Function to close the modal
function closeModal() {
    const modal = document.getElementById("id01");
    modal.style.display = "none";
}

// Get the form container and delete button
const formContainer = document.getElementById('form-container');
const deleteBtn = document.querySelector('.delete-btn');

// Handle form submission and send the DELETE request using axios
// Handle form submission and send the DELETE request using axios
document.querySelector('#confirmDelete').addEventListener("click", function(event) {
    event.preventDefault(); // Prevent form submission

    // Get the student_id from the form
    const studentId = document.getElementById('student_id').value;

    // Send the DELETE request to the server
    axios.post('delete-student.php', new URLSearchParams({
        'student_id': studentId
    }), {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'  // Ensure the request is sent as URL-encoded
        }
    })
    .then(function(response) {
        // Handle the response from the server
        const data = response.data;

        // Show success or failure message
        if (data.status === 'success') {
            alert(data.message); // Show success message
            window.location.href = 'dashboard.php'; // Redirect after successful deletion
        } else {
            alert(data.message); // Show error message
        }
    })
    .catch(function(error) {
        // Handle any error that occurred during the request
        console.error('Error occurred while deleting student:', error);
        alert('An error occurred while deleting the student record.');
    });
});

