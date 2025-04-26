<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST method is allowed']);
    exit;
}

if (!isset($input['name']) || !isset($input['description']) || !isset($input['price']) || !isset($input['stock']) || !isset($input['category_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

$name = $input['name'];
$description = $input['description'];
$price = $input['price'];
$stock = $input['stock'];
$category_id = $input['category_id'];

try {
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdii", $name, $description, $price, $stock, $category_id);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add product: ' . $e->getMessage()]);
}
?>
