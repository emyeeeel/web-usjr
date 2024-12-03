document.getElementById("registration-form").addEventListener("submit", function(event) {
    if (!event.target.checkValidity()) {
        return;
    }

    const username = document.getElementById("username").value;
    const password = document.getElementById("pass").value;
    const verifyPassword = document.getElementById("verify-pass").value;

    if (password !== verifyPassword) {
        showModal("Passwords do not match. Please try again.", "error");
        return;
    }

    const formData = {
        username: username,
        pass: password
    };

    // Show loader before making the request
    document.getElementById("loader").style.display = "flex";

    axios.post('register.php', formData, {
        headers: {
            'Content-Type': 'application/json'  
        }
    })
    .then(function(response) {
        if (response.data.success) {
            showModal("Registration successful!", "success");

            document.getElementById("registration-form").reset();

            setTimeout(function() {
                document.getElementById("loader").style.display = "none";
                window.location.href = "/usjr/login/login.php";  
            }, 2000); 
        } else {
            document.getElementById("loader").style.display = "none";
            showModal("Error: " + response.data.message, "error");
        }
    })
    .catch(function(error) {
        document.getElementById("loader").style.display = "none";
        showModal("There was an error processing your request.", "error");
    });

    event.preventDefault(); 
});

function showModal(message, type) {
    const modal = document.getElementById("id01");
    const modalMessage = document.getElementById("modal-message");

    modalMessage.textContent = message;

    const modalContent = document.querySelector(".modal-content");
    modalContent.classList.remove("error", "success");
    modalContent.classList.add(type);

    modal.style.display = "block";
}

function closeModal() {
    const modal = document.getElementById("id01");
    modal.style.display = "none";
}