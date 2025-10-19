<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Navigation bar -->
<nav style="padding:12px 20px; background:#2874f0; color:white; display:flex; align-items:center; justify-content:space-between;">
    <div>
        <a href="index.php" style="color:white; margin-right:20px; text-decoration:none; font-weight:bold;">Home</a>
        <a href="cart_page.php" style="color:white; margin-right:20px; text-decoration:none; font-weight:bold;">
            Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
        </a>
        <a href="orders.php" style="color:white; margin-right:20px; text-decoration:none; font-weight:bold;">Orders</a>
    </div>
    <div>
        <?php if(isset($_SESSION['user_id'])): ?>
            Hi, <?php echo $_SESSION['username']; ?> | 
            <a href="logout.php" style="color:white; text-decoration:none; font-weight:bold;">Logout</a>
        <?php else: ?>
            <a href="login.php" style="color:white; text-decoration:none; font-weight:bold; margin-right:10px;">Login</a>
            <a href="register.php" style="color:white; text-decoration:none; font-weight:bold;">Register</a>
        <?php endif; ?>
    </div>
</nav>
<hr style="margin:0; border:1px solid #ddd;">
