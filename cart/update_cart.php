<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(['status' => 'error', 'message' => 'Only PUT method is allowed']);
    exit;
}

if (!isset($input['cart_id']) || !isset($input['quantity'])) {
    echo json_encode(['status' => 'error', 'message' => 'Cart ID and new Quantity are required']);
    exit;
}

$cart_id = $input['cart_id'];
$quantity = $input['quantity'];

try {
    $stmt = $conn->prepare("UPDATE shopping_cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $quantity, $cart_id);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully']);
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update cart: ' . $e->getMessage()]);
}
?>
