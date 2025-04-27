<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

// Ambil input JSON
$input = json_decode(file_get_contents("php://input"), true);

// Validasi method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST method is allowed']);
    exit;
}

// Validasi input
if (!isset($input['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit;
}

$user_id = $input['user_id'];

try {
    // Mulai transaksi
    $conn->begin_transaction();

    // 1. Ambil semua isi cart user
    $stmt = $conn->prepare("
        SELECT c.product_id, c.quantity, p.price 
        FROM shopping_cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = [];
    $total_amount = 0;

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_amount += $row['quantity'] * $row['price'];
    }

    if (empty($cart_items)) {
        throw new Exception('Cart is empty. Cannot create order.');
    }

    // 2. Insert ke tabel orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("id", $user_id, $total_amount);
    $stmt->execute();
    $order_id = $conn->insert_id;

    // 3. Insert setiap item cart ke tabel order_items
    foreach ($cart_items as $item) {
        $subtotal = $item['quantity'] * $item['price'];
        $stmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiidd", $order_id, $item['product_id'], $item['quantity'], $item['price'], $subtotal);
        $stmt->execute();
    }

    // 4. Kosongkan shopping_cart user
    $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // 5. Commit transaksi
    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Order created successfully',
        'order_id' => $order_id
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to create order: ' . $e->getMessage()
    ]);
}
?>
