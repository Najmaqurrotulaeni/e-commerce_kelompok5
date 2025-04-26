<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing category ID']);
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT id, name, description, created_at FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $category = $result->fetch_assoc();

    if ($category) {
        echo json_encode(['status' => 'success', 'data' => $category]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Category not found']);
    }
} catch(mysqli_sql_exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch category: ' . $e->getMessage()]);
}
?>
