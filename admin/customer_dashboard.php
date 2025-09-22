<?php
session_start();
require '../config/db.php';
 // adjust path if needed

// Check if user is logged in and is customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: login.php");
    exit();
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
    header("Location: customer_dashboard.php");
    exit();
}

// Fetch products from DB
$products = [];
$sql = "SELECT * FROM product";
$result = $conn->query($sql);

if ($result) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard - Shop</title>
    <link href="css/customer.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="header-container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h1>
        <nav>
            <a href="customer_dashboard.php">Home</a>
            <a href="cart.php">Cart (<?= count($_SESSION['cart']) ?>)</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<main>
    <h2>Our Products</h2>
    <div class="products-grid">
        <?php foreach($products as $p): ?>
            <div class="product-card">
                <img src="uploads/<?= htmlspecialchars($p['image'] ?? 'placeholder.png') ?>" alt="<?= htmlspecialchars($p['product']) ?>">
                <h3><?= htmlspecialchars($p['product']) ?></h3>
                <p>$<?= number_format($p['price'], 2) ?></p>
                <a href="?add=<?= $p['id'] ?>" class="btn">Add to Cart</a>
            </div>
        <?php endforeach; ?>
        <?php if(empty($products)) echo "<p>No products available</p>"; ?>
    </div>
</main>
</body>
</html>
