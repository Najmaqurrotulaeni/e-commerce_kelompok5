<?php
require_once('../config/connect_db.php');
header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT id, username, email, created_at FROM users");

    $users = [];
    while ($row = $stmt->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $users
    ]);
} catch(mysqli_sql_exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch users: ' . $e->getMessage()
    ]);
}
?>
