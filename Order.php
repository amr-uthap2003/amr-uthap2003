<?php
require_once 'db.php'; // Database connection

class Order {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Place a new order
    public function placeOrder($user_id, $cartItems, $payment_id, $amount) {
        try {
            $this->conn->beginTransaction();

            // Insert into orders table with new columns
            $stmt = $this->conn->prepare("
                INSERT INTO orders (user_id, payment_id, total_amount, created_at)
                VALUES (:user_id, :payment_id, :total_amount, NOW())
            ");
            $stmt->execute([
                ':user_id' => $user_id,
                ':payment_id' => $payment_id,
                ':total_amount' => $amount
            ]);

            $order_id = $this->conn->lastInsertId();

            // Insert items into order_items table
            $stmtItem = $this->conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (:order_id, :product_id, :quantity, :price)
            ");
            foreach($cartItems as $item) {
                $stmtItem->execute([
                    ':order_id' => $order_id,
                    ':product_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            $this->conn->commit();
            return $order_id;

        } catch(PDOException $e) {
            $this->conn->rollBack();
            die("Order placement failed: " . $e->getMessage());
        }
    }

    // Fetch orders for a user
    public function getOrdersByUser($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id=:user_id ORDER BY created_at DESC");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($orders as &$order) {
            $stmtItems = $this->conn->prepare("
                SELECT oi.*, p.name, p.price 
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id=:order_id
            ");
            $stmtItems->bindParam(':order_id', $order['id']);
            $stmtItems->execute();
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }
}
?>
