<?php
// Set headers for CORS and JSON response
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once '../db.php'; // Ensure the path to your db.php is correct

if (!$pdo) {
    die(json_encode(array("error" => "Database connection failed")));
}

// Function to get students from the database
function getStudents() {
    global $pdo;

    // SQL query to fetch student details along with college and program
    $sql = "SELECT 
                students.studid, 
                students.studfirstname, 
                students.studlastname, 
                students.studmidname, 
                students.studyear, 
                colleges.collfullname AS college_name, 
                programs.progfullname AS program_name 
            FROM students
            LEFT JOIN colleges ON students.studcollid = colleges.collid
            LEFT JOIN programs ON students.studprogid = programs.progid";

    try {
        // Execute the query
        $stmt = $pdo->query($sql);

        if ($stmt) {
            // Fetch all results as an associative array
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Debugging: Log the students data to the error log
            error_log("Fetched students: " . print_r($students, true));

            // If students are found, return the data as JSON
            if (!empty($students)) {
                echo json_encode($students);
            } else {
                // If no students are found, return a message
                echo json_encode(array("message" => "No students found"));
            }
        } else {
            // If the query fails, return an error message
            echo json_encode(array("error" => "Failed to execute query"));
        }
    } catch (PDOException $e) {
        // Log the error for debugging
        error_log("Error: " . $e->getMessage());

        // Return error response as JSON
        echo json_encode(array("error" => "An error occurred while fetching students."));
    }
}

// Call the function to fetch students and output the result
getStudents();
?>
