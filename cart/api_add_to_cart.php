<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST method is allowed']);
    exit;
}

if (!isset($input['user_id']) || !isset($input['product_id']) || !isset($input['quantity'])) {
    echo json_encode(['status' => 'error', 'message' => 'User ID, Product ID, and Quantity are required']);
    exit;
}

$user_id = $input['user_id'];
$product_id = $input['product_id'];
$quantity = $input['quantity'];

try {
    $conn->begin_transaction();

    //Check product
    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        throw new Exception('Product not found');
    }

    if ($product['stock'] < $quantity) {
        throw new Exception('Not enough stock available');
    }

    //Insert product
    $stmt = $conn->prepare("INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();

    //Update product
    $new_stock = $product['stock'] - $quantity;
    $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_stock, $product_id);
    $stmt->execute();

    $conn->commit();

    echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
}
?>