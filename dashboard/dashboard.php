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
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="process-dashboard.js" defer></script>
    <style>
        body, html {
            width: 100%;
            height: 100%; 
            margin: 0; 
            font-family: 'Arial', sans-serif; 
            display: flex; 
            flex-direction: column; /* Stack children vertically */
            justify-content: flex-start; /* Align content from top */
            background-color: #f0f4f8; 
        }

        #header-container {
            width: 100%;
            height: 10%;
            background-color: white;
            padding: 15px 30px;
            color: #6200ea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            position: fixed; /* Fix to the top */
            top: 0; /* Position at the very top */
            left: 0; /* Ensure it starts from the left edge */
            z-index: 1000; /* Ensure it stays above other content */
        }

        #header-container h1 {
            margin: 0; /* Remove default margin */
            font-size: 24px; /* Adjust size */
            color: #6200ea; 
        }

        #header-container span {
            font-size: 16px; 
        }

        #header-container form {
            margin: 0; /* Remove form margin */
        }

        button {
            padding: 10px 20px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"] {
            background-color: #6200ea;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #3700b3;
        }

        #form-container {
            width: 25vw;
            padding: 20px;
            background-color: #ffffff;  
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            border: 1px solid #e0e0e0; 
            margin-top: 80px; /* Push down content to avoid header overlap */
        }

        /* Table Styles */
        .table-container {
            width: 80%;
            max-width: 1200px; /* Set a max width for the table container */
            margin: 160px auto 20px; /* Add margin-top for the header space (160px for the header) */
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative; /* Allow absolute positioning of button inside this container */
        }

        .add-student-button {
            position: absolute;
            top: 20px; /* Position the button 20px from the top */
            left: 20px; /* Position the button 20px from the left */
            padding: 12px 24px;
            font-size: 16px;
            background-color: #03a9f4; /* Light blue color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            z-index: 1; /* Make sure it's above other elements */
        }

        .add-student-button:hover {
            background-color: #0288d1; /* Darker blue on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 50px; /* Add top margin to push the table content below the button */
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0; /* Subtle border for rows */
        }

        th {
            background-color: #6200ea; /* Purple background for header */
            color: white; /* White text for header */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Light gray for even rows */
        }

        tr:hover {
            background-color: #f1f1f1; /* Slightly darker background on hover */
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-buttons button {
            padding: 6px 12px;
            background-color: #6200ea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .action-buttons button:hover {
            background-color: #3700b3;
        }

        .action-buttons .edit-btn {
            background-color: #03a9f4; /* Light blue for edit button */
        }

        .action-buttons .delete-btn {
            background-color: #f44336; /* Red for delete button */
        }

        .action-buttons button:hover {
            background-color: #3700b3; /* Darker hover effect for action buttons */
        }
    </style>
</head>
<body>
    <div id="header-container">
        <h1>Dashboard Page</h1> <!-- Leftmost -->
        <div style="display: flex; align-items: center; gap: 10px;">
            <span>Welcome, <?php echo $_SESSION['username']; ?></span> <!-- Rightmost text -->
            <form action="../logout.php" method="POST">
                <button type="submit">Log Out</button>
            </form>
        </div>
    </div>

    <!-- Table Container with Table -->
    <div class="table-container">
        <!-- Add New Student Button -->
        <button class="add-student-button">Add New Student Entry</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>College</th>
                    <th>Program Enrolled</th>
                    <th>Year</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Table Row -->
                <tr>
                    <!-- Generate dynamically through query -->
                    <td class="action-buttons">
                        <button class="edit-btn">Edit</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</body>
</html>
