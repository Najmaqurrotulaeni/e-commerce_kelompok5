<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST method is allowed']);
    exit;
}

if (!isset($input['name']) || !isset($input['description'])) {
    echo json_encode(['status' => 'error', 'message' => 'Name and Description are required']);
    exit;
}

$name = $input['name'];
$description = $input['description'];

try {
    $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Category added successfully']);
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add category: ' . $e->getMessage()]);
}
?>
