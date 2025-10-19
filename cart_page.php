<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");

require_once 'Cart.php';
require_once 'Order.php';
require_once 'Product.php';
include 'header.php';

$cart = new Cart();
$orderObj = new Order();
$productObj = new Product();
$total = 0;
$items = $cart->getCartItems();

// Remove item
if(isset($_GET['remove'])) {
    $cart->removeItem($_GET['remove']);
    header("Location: cart_page.php");
    exit();
}

// Update quantities
if(isset($_POST['update'])) {
    foreach($_POST['quantities'] as $id => $qty) {
        $cart->updateQuantity($id, $qty);
    }
    header("Location: cart_page.php");
    exit();
}
?>

<div style="width:90%; margin:20px auto;">
    <h2>🛍️ Your Cart</h2>

    <?php if(empty($items)): ?>
        <p>Your cart is empty! <a href="index.php" style="color:#2874f0;">Go Shopping</a></p>
    <?php else: ?>
        <form method="POST" id="cartForm">
            <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse:collapse; text-align:center;">
                <tr style="background:#2874f0; color:white;">
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php foreach($items as $item): 
                    $itemTotal = $item['price'] * $item['quantity'];
                    $total += $itemTotal;
                ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td class="price">₹<?php echo $item['price']; ?></td>
                    <td>
                        <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" style="width:60px;text-align:center;" class="qtyInput">
                    </td>
                    <td class="itemTotal">₹<?php echo $itemTotal; ?></td>
                    <td><a href="?remove=<?php echo $item['id']; ?>" style="color:red;text-decoration:none;">Remove</a></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="text-align:right; font-weight:bold;">Total:</td>
                    <td colspan="2" id="grandTotal" style="font-weight:bold;">₹<?php echo $total; ?></td>
                </tr>
            </table>
            <br>
            <button type="submit" name="update" style="padding:8px 15px; background:#ff9800; color:white; border:none; border-radius:4px; cursor:pointer;">Update Cart</button>
        </form>
        <br>
        <div style="text-align:center;">
            <button id="payButton" style="padding:10px 20px; background:#2874f0; color:white; border:none; border-radius:5px; cursor:pointer;">💳 Proceed to Payment</button>
        </div>
    <?php endif; ?>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
// Function to recalculate total
function updateTotals() {
    let total = 0;
    document.querySelectorAll('tr').forEach(function(row){
        const priceCell = row.querySelector('.price');
        const qtyInput = row.querySelector('.qtyInput');
        const itemTotalCell = row.querySelector('.itemTotal');
        if(priceCell && qtyInput && itemTotalCell){
            const price = parseFloat(priceCell.textContent.replace('₹',''));
            const qty = parseInt(qtyInput.value);
            const itemTotal = price * qty;
            itemTotalCell.textContent = '₹' + itemTotal;
            total += itemTotal;
        }
    });
    document.getElementById('grandTotal').textContent = '₹' + total;
    return total;
}

// Update totals live when quantity changes
document.querySelectorAll('.qtyInput').forEach(function(input){
    input.addEventListener('change', updateTotals);
});

document.getElementById('payButton')?.addEventListener('click', function(){
    const total = updateTotals(); // ensure latest total
    var options = {
        "key": "rzp_test_1TSGXPk46TbXBv", // your Razorpay test key
        "amount": total * 100, // amount in paise
        "currency": "INR",
        "name": "Kitchenware Store",
        "description": "Order Payment",
        "handler": function (response){
            // After payment, redirect to success page with Razorpay payment id and amount
            window.location.href = "success.php?payment_id=" + response.razorpay_payment_id + "&amount=" + total;
        },
        "prefill": {
            "name": "<?php echo $_SESSION['username']; ?>",
            "email": "customer@example.com"
        },
        "theme": {
            "color": "#2874f0"
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>
