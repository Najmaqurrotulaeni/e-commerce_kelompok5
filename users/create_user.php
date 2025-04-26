<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST method is allowed']);
    exit;
}

if (!isset($input['username']) || !isset($input['password']) || !isset($input['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

$username = $input['username'];
$password = password_hash($input['password'], PASSWORD_DEFAULT);
$email = $input['email'];

try {
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $email]);
    
    echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Registration failed: ' . $e->getMessage()]);
}
?>
