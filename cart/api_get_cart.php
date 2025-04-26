<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

if (!isset($_GET['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing user ID']);
    exit;
}

$user_id = $_GET['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT 
            c.id,
            p.name AS product_name,
            p.price AS unit_price,
            c.quantity,
            (p.price * c.quantity) AS subtotal
        FROM shopping_cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $cart_items = [];
    $total = 0;

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['subtotal'];
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            'items' => $cart_items,
            'total' => $total,
            'item_count' => count($cart_items)
        ]
    ]);
    } catch(mysqli_sql_exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch cart items: ' . $e->getMessage()]);
    }
?>
