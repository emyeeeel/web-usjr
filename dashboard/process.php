<?php
require_once '../db.php'; // Include your database connection file
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: /usjr/login/login.php"); 
    exit();
}

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $studentId = $_POST['student_id'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $collegeId = $_POST['college'];
    $programId = $_POST['program'];
    $year = $_POST['year'];

    // Basic validation
    if (empty($studentId) || empty($firstName) || empty($lastName) || empty($collegeId) || empty($programId) || empty($year)) {
        echo "All fields are required!";
        exit();
    }

    // Prepare SQL query to insert data
    $sql = "INSERT INTO students (studid, studfirstname, studmidname, studlastname, studprogid, studcollid, studyear) 
            VALUES (:studid, :studfirstname, :studmidname, :studlastname, :studprogid, :studcollid, :studyear)";
    
    // Prepare the statement
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':studid', $studentId);
    $stmt->bindParam(':studfirstname', $firstName);
    $stmt->bindParam(':studmidname', $middleName);
    $stmt->bindParam(':studlastname', $lastName);
    $stmt->bindParam(':studprogid', $programId);
    $stmt->bindParam(':studcollid', $collegeId);
    $stmt->bindParam(':studyear', $year);

    // Execute the statement
    try {
        $stmt->execute();
        header("Location: /usjr/dashboard/dashboard.php"); 
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
