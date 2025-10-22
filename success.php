<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>

<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'cart.php';
require_once 'Order.php';

$cart = new Cart();
$orderObj = new Order();

$user_id = $_SESSION['user_id'];
$payment_id = $_GET['payment_id'] ?? '';
$amount = $_GET['amount'] ?? 0;

if(empty($payment_id) || empty($amount)) {
    die("Invalid payment data!");
}

// Convert amount from paise to rupees if coming from Razorpay
$amount = (float) ($amount / 100);

// Get cart items
$cartItems = $cart->getCartItems();
if(empty($cartItems)) {
    die("Your cart is empty.");
}

// Place the order (this will insert payment_id and total_amount)
$order_id = $orderObj->placeOrder($user_id, $cartItems, $payment_id, $amount);

// Clear the cart
$cart->clearCart();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Successful</title>
    <style>
        body { font-family: Arial, sans-serif; text-align:center; padding:50px; background:#f9f9f9; }
        .success-box { display:inline-block; padding:30px 40px; background:white; border-radius:10px; box-shadow:0 5px 20px rgba(0,0,0,0.1); }
        h1 { color:#28a745; }
        a { display:inline-block; margin-top:20px; padding:10px 20px; background:#2874f0; color:white; text-decoration:none; border-radius:5px; }
        a:hover { background:#0b5ed7; }
    </style>
</head>
<body>
    <div class="success-box">
        <h1>🎉 Payment Successful!</h1>
        <p>Order ID: <strong>#<?php echo $order_id; ?></strong></p>
        <p>Payment ID: <strong><?php echo htmlspecialchars($payment_id); ?></strong></p>
        <p>Amount Paid: <strong>₹<?php echo number_format($amount,2); ?></strong></p>
        <a href="orders.php">View Your Orders</a><br><br>
        <a href="index.php">Continue Shopping</a>
    </div>
</body>
</html>

