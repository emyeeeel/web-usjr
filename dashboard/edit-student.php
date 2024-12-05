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
        $collegeFullName = $inputData['college']; // College name
        $programFullName = $inputData['program']; // Program name
        $year = $inputData['year'];

        // Debugging: Check if college exists based on full name
        $collegeQuery = $pdo->prepare("SELECT collid FROM colleges WHERE collfullname = :collfullname");
        $collegeQuery->bindParam(':collfullname', $collegeFullName, PDO::PARAM_STR);
        $collegeQuery->execute();
        $collegeResult = $collegeQuery->fetch(PDO::FETCH_ASSOC);
        
        if (!$collegeResult) {
            echo json_encode(["success" => false, "message" => "Invalid college name."]);
            exit();
        }
        $collegeId = $collegeResult['collid']; // Retrieve collid from query result

        // Debugging: Check if program exists based on full name and college ID
        $programQuery = $pdo->prepare("SELECT progid FROM programs WHERE progfullname = :progfullname AND progcollid = :collid");
        $programQuery->bindParam(':progfullname', $programFullName, PDO::PARAM_STR);
        $programQuery->bindParam(':collid', $collegeId, PDO::PARAM_INT);
        $programQuery->execute();
        $programResult = $programQuery->fetch(PDO::FETCH_ASSOC);

        if (!$programResult) {
            echo json_encode(["success" => false, "message" => "Invalid program name for the selected college."]);
            exit();
        }
        $programId = $programResult['progid']; // Retrieve progid from query result

        // Basic validation
        if (empty($studentId) || empty($firstName) || empty($lastName) || empty($collegeId) || empty($programId) || empty($year)) {
            // Respond with an error message if any required field is empty
            echo json_encode(["success" => false, "message" => "All fields are required!"]);
            exit();
        }

        // Ensure the program ID is an integer (this is important to avoid SQL errors)
        $programId = (int)$programId;  // Ensure programId is an integer (force cast)

        // Prepare SQL query to update student data in the database
        $sql = "UPDATE students 
                SET studfirstname = :studfirstname, 
                    studmidname = :studmidname, 
                    studlastname = :studlastname, 
                    studprogid = :studprogid, 
                    studcollid = :studcollid, 
                    studyear = :studyear 
                WHERE studid = :studid"; // Update based on the student_id

        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters (making sure the program ID is bound as an integer)
        $stmt->bindParam(':studid', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':studfirstname', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':studmidname', $middleName, PDO::PARAM_STR);
        $stmt->bindParam(':studlastname', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':studprogid', $programId, PDO::PARAM_INT);  // Bind program ID as integer
        $stmt->bindParam(':studcollid', $collegeId, PDO::PARAM_INT);
        $stmt->bindParam(':studyear', $year, PDO::PARAM_STR);

        // Try executing the SQL statement
        try {
            $stmt->execute();

            // Send a success response back to the client
            echo json_encode(["success" => true, "message" => "Student data updated successfully."]);

        } catch (PDOException $e) {
            // In case of an error, send the error message back
            echo json_encode(["success" => false, "message" => "Error updating data: " . $e->getMessage()]);
        }

    } else {
        // If the required fields are not present in the request
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
    }
} else {
    // If the request is not POST, return a 405 Method Not Allowed
    echo json_encode(["success" => false, "message" => "Method not allowed."]);
}
