<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['status' => 'error', 'message' => 'Only DELETE method is allowed']);
    exit;
}

if (!isset($input['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Category ID is required']);
    exit;
}

$id = $input['id'];

try {
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Category not found']);
    }
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete category: ' . $e->getMessage()]);
}
?>
