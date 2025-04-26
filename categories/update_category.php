<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(['status' => 'error', 'message' => 'Only PUT method is allowed']);
    exit;
}

if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID, Name and Description are required']);
    exit;
}

$id = $input['id'];
$name = $input['name'];
$description = $input['description'];

try {
    $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Category updated successfully']);
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update category: ' . $e->getMessage()]);
}
?>
