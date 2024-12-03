document.getElementById("login-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    if (!event.target.checkValidity()) {
        return;
    }

    const username = document.getElementById("username").value;
    const password = document.getElementById("pass").value;

    const formData = {
        username: username,
        pass: password
    };

    // Show loader before making the request
    document.getElementById("loader").style.display = "flex";

    // Send POST request to verify-login.php
    axios.post('verify-login.php', formData, {
        headers: {
            'Content-Type': 'application/json'  
        }
    })
    .then(function(response) {
        // Check if the response is successful
        if (response.data.success) {
            // Reset the form and hide loader
            document.getElementById("login-form").reset();
            setTimeout(function() {
                document.getElementById("loader").style.display = "none";
                // Redirect to the dashboard page
                window.location.href = "/usjr/dashboard/dashboard.php";  
            }, 2000); // Give a small delay before redirecting
        } else {
            // Hide loader and show error modal
            document.getElementById("loader").style.display = "none";
            showModal("Error: " + response.data.message, "error");
        }
    })
    .catch(function(error) {
        // Hide loader and show error modal in case of error
        document.getElementById("loader").style.display = "none";
        showModal("There was an error processing your request.", "error");
    });
});

// Function to show a modal with a message
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
