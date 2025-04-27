<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

// Cek apakah order_id dikirim dari URL
if (!isset($_GET['order_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing order ID']);
    exit;
}

$order_id = $_GET['order_id'];

try {
    // Ambil semua produk yang ada dalam 1 order
    $stmt = $conn->prepare("
        SELECT 
            oi.product_id, 
            p.name AS product_name, 
            oi.quantity, 
            oi.price, 
            oi.subtotal
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $order_details = [];
    while ($row = $result->fetch_assoc()) {
        $order_details[] = $row;
    }

    // Cek apakah order detail ditemukan
    if (empty($order_details)) {
        echo json_encode(['status' => 'error', 'message' => 'Order not found or has no items']);
    } else {
        echo json_encode(['status' => 'success', 'data' => $order_details]);
    }
} catch(mysqli_sql_exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch order details: ' . $e->getMessage()
    ]);
}
?>
