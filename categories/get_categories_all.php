<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT id, name, description, created_at FROM categories");

    $categories = [];
    while ($row = $stmt->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $categories]);
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch categories: ' . $e->getMessage()]);
}
?>
