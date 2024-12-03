<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

session_start(); 

require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);

$username = isset($data['username']) ? $data['username'] : '';
$password = isset($data['pass']) ? $data['pass'] : '';

// Basic validation
if (empty($username) || empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'Username and password are required.'
    ]);
    exit;
}

// Query to find the user by username
$query = "SELECT * FROM users WHERE username = :username LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();

// Check if user exists
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Verify password
    if (password_verify($password, $user['password'])) {

        // Set session variables
        $_SESSION['user_id'] = $user['userid'];  
        $_SESSION['username'] = $user['username'];  

        // Send success response (no redirection here)
        echo json_encode([
            'success' => true,
            'message' => 'Login successful.'
        ]);
    } else {
        // Incorrect password
        echo json_encode([
            'success' => false,
            'message' => 'Incorrect username or password.'
        ]);
    }
} else {
    // User not found
    echo json_encode([
        'success' => false,
        'message' => 'User not found.'
    ]);
}
?>
