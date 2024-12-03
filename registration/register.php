<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

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

$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$userCount = $stmt->fetchColumn();

if ($userCount > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Username already exists.'
    ]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful!'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error during registration: ' . $e->getMessage()
    ]);
}
?>
