<?php 
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    header("Location: /usjr/dashboard/dashboard.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="process-registration.js" defer></script>

    <style>
        body, html {
            height: 100%; 
            margin: 0; 
            font-family: 'Arial', sans-serif; 
            display: flex; 
            justify-content: center;
            align-items: center; 
            background-color: #f0f4f8; 
        }

        #form-container {
            width: 25vw;
            padding: 20px;
            background-color: #ffffff;  
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            border: 1px solid #e0e0e0; 
        }

        h1 {
            color: #6200ea; 
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px; 
        }

        table {
            width: 100%; 
            border-collapse: collapse;
            margin-bottom: 20px; 
        }

        td {
            padding: 12px;
            vertical-align: middle;
        }

        td:first-child {
            width: 40%; 
            font-weight: bold;
            color: #333; 
        }

        td:last-child {
            width: 60%;
        }

        label {
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            margin-top: 5px;
            transition: all 0.3s ease-in-out;
        }

        input:focus {
            border-color: #6200ea; 
            outline: none;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px; 
            margin-top: 20px; 
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

        button[type="reset"] {
            background-color: #e0e0e0;
            color: #333;
        }

        button[type="reset"]:hover {
            background-color: #c7c7c7;
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

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #6200ea;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
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

        @media (max-width: 600px) {
            #form-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div id="form-container">
        <h1>Registration Form</h1> 
        <form id="registration-form" method="POST">
            <table>
                <tr>
                    <td><label for="username">Username</label></td>
                    <td><input type="text" id="username" name="username" required></td>
                </tr>
                <tr>
                    <td><label for="pass">Password</label></td>
                    <td><input type="password" id="pass" name="pass" required></td>
                </tr>
                <tr>
                    <td><label for="verify-pass">Verify Password</label></td>
                    <td><input type="password" id="verify-pass" name="verify-pass" required></td>
                </tr>
            </table>
            <div class="button-container">
                <button type="submit">Register</button>
                <button type="reset">Clear</button>
            </div>

            <div class="login-link">
                <p>Already have an account? <a href="/usjr/login/login.php">Login here</a></p>
            </div>
        </form>
    </div>
    
    <div id="id01" class="modal">
        <form class="modal-content animate">
            <div class="notification title">
                <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
            </div>

            <div class="container">
                <p id="modal-message"></p>
                <button type="button" onclick="closeModal()">Okay</button>
            </div>
        </form>
    </div>

    <div id="loader" class="loader" style="display: none;">
        <div class="spinner"></div>
    </div>
</body>
</html>
