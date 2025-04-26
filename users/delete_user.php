<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['status' => 'error', 'message' => 'Only DELETE method is allowed']);
    exit;
}

if (!isset($input['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit;
}

$id = $input['id'];

try {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete user: ' . $e->getMessage()]);
}
?>
