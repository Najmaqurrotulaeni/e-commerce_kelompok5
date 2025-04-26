<?php
require_once('../config/connect_db.php');

header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($data['id']) && isset($data['user_id'])) {
        $cart_id = $data['id'];
        $user_id = $data['user_id'];

        try {
            $stmt = $GLOBALS['conn']->prepare("SELECT id FROM shopping_cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$cart_id, $user_id]);
            
            if (!$stmt->fetch()) {
                throw new Exception('Cart item not found or you do not have permission to delete it');
            }

            $stmt = $GLOBALS['conn']->prepare("DELETE FROM shopping_cart WHERE id = ? AND user_id = ?");
            $stmt->execute([$cart_id, $user_id]);

            echo json_encode([
                'status' => 'success',
                'message' => 'Item removed from cart successfully'
            ]);
        } catch(Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Cart item ID and user ID are required'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Only DELETE method is allowed'
    ]);
}
?> 