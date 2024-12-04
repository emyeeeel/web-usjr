<?php 
require_once '../db.php'; 
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: /usjr/login/login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Entry</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body, html {
            width: 100%;
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8; 
        }

        form {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 80px; 
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #6200ea;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"], select {
            width: 100%;  
            padding: 10px; 
            font-size: 14px; 
            border: 1px solid #ccc; 
            border-radius: 5px;  
            box-sizing: border-box; 
            margin-top: 5px;  
        }

        .select-style {
            -webkit-appearance: none;  /* Custom styling for select elements */
            -moz-appearance: none;
            appearance: none;
            padding-right: 30px; /* Space for the dropdown arrow */
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .save-btn {
            background-color: #6200ea; /* Purple color */
            color: white;
        }

        .save-btn:hover {
            background-color: #3700b3; /* Darker purple on hover */
        }

        .clear-btn {
            background-color: #f44336; /* Red color */
            color: white;
        }

        .clear-btn:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }

        .cancel-btn {
            background-color: #9e9e9e; /* Grey color */
            color: white;
        }

        .cancel-btn:hover {
            background-color: #757575; /* Darker grey on hover */
        }

        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #6200ea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

    </style>
</head>
<body>
    <form id="entry-form">
        <h2>Student Information Data Entry</h2>

        <!-- Student ID -->
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" required>
        </div>

        <!-- First Name -->
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>

        <!-- Middle Name -->
        <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" required>
        </div>

        <!-- Last Name -->
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>

        <!-- College Selection -->
        <div class="form-group">
            <label for="college">College</label>
            <select id="college" name="college" required>
                <option value="" disabled selected>Select College</option>
                <!-- Will be dynamically generated -->
            </select>
        </div>

        <!-- Program Selection -->
        <div class="form-group">
            <label for="program">Program Enrolled</label>
            <select id="program" name="program" required>
                <option value="" disabled selected>Select Program</option>
                <!-- Will be dynamically generated -->
            </select>
        </div>

        <!-- Year -->
        <div class="form-group">
            <label for="year">Year</label>
            <input type="text" id="year" name="year" required>
        </div>

        <!-- Button Group -->
        <div class="button-group">
            <button type="submit" class="save-btn">Save</button>
            <button type="reset" class="clear-btn">Clear Entries</button>
            <button type="button" class="cancel-btn">Cancel</button>
        </div>
    </form>

    <div id="loader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div>

    <script src="process-entry.js"></script>
    <script>
        const saveButton = document.querySelector('.save-btn');
        const cancelButton = document.querySelector('.cancel-btn');
        function submitForm(event) {
            console.log("Save button clicked!");

            // Prevent the default form submission
            event.preventDefault();

            // Get the values of each input field individually
            const studentId = document.getElementById('student_id').value;
            const firstName = document.getElementById('first_name').value;
            const middleName = document.getElementById('middle_name').value;
            const lastName = document.getElementById('last_name').value;
            const college = document.getElementById('college').value;
            const program = document.getElementById('program').value;
            const year = document.getElementById('year').value;

            // Verify if all fields are filled
            let isValid = true;
            const fields = [studentId, firstName, middleName, lastName, college, program, year];
            
            fields.forEach((field) => {
                if (!field.trim()) {  // If any field is empty
                    isValid = false;
                }
            });

            // Get the "Save" button element and disable it if the form is invalid
            const saveButton = document.querySelector('.save-btn');
            if (!isValid) {
                saveButton.disabled = true;
                alert('Please fill in all fields!');
                return;
            } else {
                saveButton.disabled = false;
            }

            // Prepare the data to send to process.php
            const data = {
                student_id: studentId,
                first_name: firstName,
                middle_name: middleName,
                last_name: lastName,
                college: college,
                program: program,
                year: year
            };

            document.getElementById("loader").style.display = "flex";

            // If the form is valid, send the data to process.php via POST using axios
            if (isValid) {
                axios.post('process.php', data)
                    .then(function(response) {
                        // Handle the response from the server
                        if (response.data.success) {
                            console.log("Form data submitted successfully!");
                            document.getElementById("entry-form").reset();
                            setTimeout(function() {
                                document.getElementById("loader").style.display = "none";
                                // Redirect to the dashboard page
                                window.location.href = "dashboard.php";  
                            }, 2000); // Give a small delay before redirecting
                        } else {
                            console.error("Error during submission:", response.data.message);
                            alert(response.data.message);
                        }
                    })
                    .catch(function(error) {
                        console.error("Error during axios request:", error);
                        alert("An error occurred while submitting the form.");
                    });
            }
        }

        function cancelForm(){
            console.log("Cancel button clicked!");  
            window.location.href = 'dashboard.php'; 
        }
        saveButton.addEventListener('click', submitForm);
        cancelButton.addEventListener('click', cancelForm);
    </script>
</body>
</html>
