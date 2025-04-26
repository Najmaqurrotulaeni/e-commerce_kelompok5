<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT p.id, p.name, p.description, p.price, p.stock, c.name AS category_name, p.created_at
                          FROM products p
                          LEFT JOIN categories c ON p.category_id = c.id");

    $products = [];
    while ($row = $stmt->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $products]);
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch products: ' . $e->getMessage()]);
}
?>
