<?php
require_once '../db.php'; // Include your database connection file
session_start();

// Check if user is logged in
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
    <script src="process-delete.js" defer></script> <!-- Make sure the JS file is deferred -->
    <style>
        /* CSS Styles */
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

        .delete-btn {
            background-color: #f44336; /* Red color */
            color: white;
        }

        .delete-btn:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }

        #confirmDelete{
            background-color: #f44336; /* Red color */
            color: white;
        }

        #confirmDelete:hover{
            background-color: #d32f2f; /* Darker red on hover */
        }

        .btn-div {
            display: flex;         /* Use Flexbox for layout */
            justify-content: center; /* Center the buttons horizontally */
            gap: 10px;             /* Optional: Adds space between the buttons */
            margin-top: 20px;      /* Optional: Adds space above the buttons */
        }


        .cancel-btn {
            background-color: #9e9e9e; /* Grey color */
            color: white;
        }

        .cancel-btn:hover {
            background-color: #757575; /* Darker grey on hover */
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            border: 1px solid #888;
            width: 25%;
            border-radius: 10px;
            position: relative;
            padding: 20px;
        }

        #modal-message {
            font-size: 20px;
            font-weight: bold;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #000;
            font-size: 35px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: red;
            cursor: pointer;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            color: #6200ea;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form id="form-container">
        <h2>Delete Student Information</h2>

        <!-- Student ID -->
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" required readonly>
        </div>

        <!-- First Name -->
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required readonly>
        </div>

        <!-- Middle Name -->
        <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" required readonly>
        </div>

        <!-- Last Name -->
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required readonly>
        </div>

        <!-- College Selection -->
        <div class="form-group">
            <label for="college">College</label>
            <select id="college" name="college" required disabled>
                <!-- Options will be populated by the server-side code -->
            </select>
        </div>

        <!-- Program Selection -->
        <div class="form-group">
            <label for="program">Program Enrolled</label>
            <select id="program" name="program" required disabled>
                <!-- Options will be populated by the server-side code -->
            </select>
        </div>

        <!-- Year -->
        <div class="form-group">
            <label for="year">Year</label>
            <input type="text" id="year" name="year" required readonly>
        </div>

        <!-- Button Group -->
        <div class="button-group">
            <button type="button" class="delete-btn">Delete</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='dashboard.php';">Cancel</button>
        </div>
    </form>

    <!-- Modal Structure -->
    <div id="id01" class="modal">
        <form class="modal-content animate">
            <div class="notification title">
                <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
            </div>

            <div class="container">
                <p id="modal-message"></p>
                <div class="btn-div">
                    <form method="POST">
                        <button type="button" onclick="closeModal()">Cancel</button>
                        <button type="submit" id="confirmDelete">Delete</button>
                    </form>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
