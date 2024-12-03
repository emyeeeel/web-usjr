<?php
require_once '../db.php'; // Include your database connection file

// Ensure the 'student_id' is coming in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    // Log the incoming student_id for debugging purposes
    error_log('Deleting student with ID: ' . $_POST['student_id']);
    
    $student_id = $_POST['student_id'];

    // Prepare the SQL query to delete the student record
    $sql = "DELETE FROM students WHERE studid = :student_id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            // Return a JSON response indicating success
            echo json_encode(['status' => 'success', 'message' => 'Student record deleted successfully!']);
        } else {
            // Return a JSON response indicating failure
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete student record']);
        }
    } else {
        // Return a JSON response indicating failure to prepare the query
        echo json_encode(['status' => 'error', 'message' => 'Database query preparation failed']);
    }
} else {
    // Return a JSON response indicating invalid request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
