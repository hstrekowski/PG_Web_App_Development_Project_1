<?php
$total_items = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $total_items += $qty;
    }
}
?>
<div class="cart-widget">
    <a href="index.php?action=saved">
        🛒 Zapamiętane: <strong><?php echo $total_items; ?></strong>
    </a>
</div>