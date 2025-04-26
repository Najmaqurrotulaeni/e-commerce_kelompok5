<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing product ID']);
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT p.id, p.name, p.description, p.price, p.stock, c.name AS category_name, p.created_at
                            FROM products p
                            LEFT JOIN categories c ON p.category_id = c.id
                            WHERE p.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        echo json_encode(['status' => 'success', 'data' => $product]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    }
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch product: ' . $e->getMessage()]);
}
?>
