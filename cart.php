<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<?php
require_once 'Product.php';

class Cart {
    public function __construct() {
        if(!isset($_SESSION)) session_start();
        if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    }

    public function addItem($product_id, $quantity = 1) {
        $productObj = new Product();
        $product = $productObj->getById($product_id);
        if(!$product) return false;

        if(isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }
        return true;
    }

    public function removeItem($product_id) {
        if(isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function clearCart() {
        $_SESSION['cart'] = [];
    }

    public function getCartItems() {
        return $_SESSION['cart'];
    }
}
?>

