<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once '../db.php'; // Ensure the path is correct and db.php establishes the PDO connection

if (!$pdo) {
    die(json_encode(array("error" => "Database connection failed")));
}

// Function to get colleges
function getColleges() {
    global $pdo;

    $sql = "SELECT collid, collfullname FROM colleges";
    try {
        $stmt = $pdo->query($sql);
        if ($stmt) {
            $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($colleges);
        } else {
            echo json_encode(array("message" => "No colleges found"));
        }
    } catch (PDOException $e) {
        error_log($e->getMessage()); // Log error to server-side logs
        echo json_encode(array("error" => "An error occurred while fetching colleges."));
    }
}

// Function to get programs
function getPrograms() {
    global $pdo;

    $sql = "SELECT progid, progcollid, progfullname FROM programs";
    try {
        $stmt = $pdo->query($sql);
        if ($stmt) {
            $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($programs);
        } else {
            echo json_encode(array("message" => "No programs found"));
        }
    } catch (PDOException $e) {
        error_log($e->getMessage()); // Log error to server-side logs
        echo json_encode(array("error" => "An error occurred while fetching programs."));
    }
}

// Check if the 'type' parameter is set to either 'colleges' or 'programs'
if (isset($_GET['type'])) {
    if ($_GET['type'] === 'colleges') {
        getColleges(); // Respond with college data
    } elseif ($_GET['type'] === 'programs') {
        getPrograms(); // Respond with program data
    } else {
        echo json_encode(array("error" => "Invalid 'type' parameter"));
    }
} else {
    echo json_encode(array("error" => "Missing 'type' parameter"));
}
?>

