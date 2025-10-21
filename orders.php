<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'Order.php';
include 'header.php';

$orderObj = new Order();
$orders = $orderObj->getOrdersByUser($_SESSION['user_id']);
?>

<div style="width:90%; margin:20px auto; font-family:Arial, sans-serif;">
    <h2 style="color:#2874f0;">📝 Your Orders</h2>

    <?php if(empty($orders)): ?>
        <p>You have no past orders. <a href="index.php" style="color:#2874f0;">Shop now</a></p>
    <?php else: ?>
        <?php foreach($orders as $order): ?>
            <div style="border:1px solid #ddd; padding:15px; margin-bottom:20px; border-radius:5px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                <h3>Order #<?php echo $order['id']; ?> 
                    <span style="font-size:14px; color:#555;">
                        (Placed on <?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?>)
                    </span>
                </h3>
                <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse:collapse; text-align:center;">
                    <tr style="background:#2874f0; color:white;">
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    <?php 
                    $total = 0;
                    foreach($order['items'] as $item):
                        $itemTotal = $item['price'] * $item['quantity'];
                        $total += $itemTotal;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>₹<?php echo number_format($item['price'],2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹<?php echo number_format($itemTotal,2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:bold;">Order Total:</td>
                        <td style="font-weight:bold;">₹<?php echo number_format($total,2); ?></td>
                    </tr>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

