<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(['status' => 'error', 'message' => 'Only PUT method is allowed']);
    exit;
}

if (!isset($input['id']) || !isset($input['name']) || !isset($input['description']) || !isset($input['price']) || !isset($input['stock']) || !isset($input['category_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

$id = $input['id'];
$name = $input['name'];
$description = $input['description'];
$price = $input['price'];
$stock = $input['stock'];
$category_id = $input['category_id'];

try {
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssdiis", $name, $description, $price, $stock, $category_id, $id);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update product: ' . $e->getMessage()]);
}
?>
