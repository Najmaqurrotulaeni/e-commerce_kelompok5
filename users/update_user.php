<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(['status' => 'error', 'message' => 'Only PUT method is allowed']);
    exit;
}

if (!isset($input['id']) || !isset($input['username']) || !isset($input['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID, Username and Email are required']);
    exit;
}

$id = $input['id'];
$username = $input['username'];
$email = $input['email'];

try {
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $email, $id]);

    echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update user: ' . $e->getMessage()]);
}
?>
