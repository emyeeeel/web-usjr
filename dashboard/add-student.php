<?php
require_once '../db.php'; // Include your database connection file

// Set content-type to JSON to handle JSON requests
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data (which is sent as JSON from axios)
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Check if the necessary fields are present in the JSON request
    if (isset($inputData['student_id'], $inputData['first_name'], $inputData['middle_name'], $inputData['last_name'], $inputData['college'], $inputData['program'], $inputData['year'])) {
        
        // Collect the form data
        $studentId = $inputData['student_id'];
        $firstName = $inputData['first_name'];
        $middleName = $inputData['middle_name'];
        $lastName = $inputData['last_name'];
        $collegeId = $inputData['college'];
        $programId = $inputData['program'];
        $year = $inputData['year'];

        // Basic validation
        if (empty($studentId) || empty($firstName) || empty($lastName) || empty($collegeId) || empty($programId) || empty($year)) {
            // Respond with an error message if any required field is empty
            echo json_encode(["success" => false, "message" => "All fields are required!"]);
            exit();
        }

        // Prepare SQL query to insert data into the database
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

        // Try executing the SQL statement
        try {
            $stmt->execute();

            // Send a success response back to the client
            echo json_encode(["success" => true, "message" => "Student data saved successfully."]);

        } catch (PDOException $e) {
            // In case of an error, send the error message back
            echo json_encode(["success" => false, "message" => "Error saving data: " . $e->getMessage()]);
        }

    } else {
        // If the required fields are not present in the request
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
    }
} else {
    // If the request is not POST, return a 405 Method Not Allowed
    echo json_encode(["success" => false, "message" => "Method not allowed."]);
}
?>
