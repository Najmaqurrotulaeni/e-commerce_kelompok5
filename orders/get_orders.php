<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

// Cek apakah user_id dikirim
if (!isset($_GET['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing user ID']);
    exit;
}

$user_id = $_GET['user_id'];

try {
    // Ambil semua orders user
    $stmt = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    // Beri response
    echo json_encode([
        'status' => 'success',
        'data' => $orders
    ]);
} catch(mysqli_sql_exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch orders: ' . $e->getMessage()
    ]);
}
?>
