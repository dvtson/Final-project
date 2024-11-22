<?php 
session_start();
include 'conn.inc';

// Kiểm tra nếu giỏ hàng trống
$totalPrice = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu khách hàng từ form
    $customerName = $_POST['customer_name'] ?? '';
    $phoneNumber = $_POST['phone_number'] ?? '';

    // Kiểm tra dữ liệu
    if (empty($customerName) || empty($phoneNumber)) {
        die("Vui lòng nhập đầy đủ thông tin khách hàng.");
    }

    // Tính tổng giá trị đơn hàng
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name, $price);
        if ($stmt->fetch()) {
            $totalPrice += $price * $quantity;
        }
        $stmt->close();
    }

    // Thêm dữ liệu vào bảng orders
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, phone_number, total_price) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Lỗi chuẩn bị câu lệnh SQL (orders): " . $conn->error);
    }
    $stmt->bind_param("ssd", $customerName, $phoneNumber, $totalPrice);
    if (!$stmt->execute()) {
        die("Lỗi khi thêm vào bảng orders: " . $stmt->error);
    }
    $orderId = $stmt->insert_id; // Lấy ID của đơn hàng vừa tạo
    $stmt->close();

    // Thêm dữ liệu vào bảng order_items
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name, $price);
        $stmt->fetch();
        $stmt->close();

        $subtotal = $price * $quantity;

        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Lỗi chuẩn bị câu lệnh SQL (order_items): " . $conn->error);
        }
        $stmt->bind_param("isidd", $orderId, $name, $quantity, $price, $subtotal);
        if (!$stmt->execute()) {
            die("Lỗi khi thêm vào bảng order_items: " . $stmt->error);
        }
        $stmt->close();
    }

    // Xóa giỏ hàng sau khi đặt hàng thành công
    unset($_SESSION['cart']);
    
    // Hiển thị giao diện xác nhận đơn hàng
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .confirmation-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #4caf50;
        }
        p {
            color: #555;
        }
        .button-container {
            margin-top: 20px;
        }
        .button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <h2>Thank You, $customerName!</h2>
        <p>Your order has been placed successfully.</p>
        <div class="button-container">
            <a href="PhamarcySelling.html" class="button">Continue Shopping</a>
        </div>
    </div>
</body>
</html>
HTML;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .checkout-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"], input[type="tel"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>
        <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
            <p>Your cart is empty. Please add products before checking out.</p>
        <?php else: ?>
            <form action="checkout.php" method="POST">
                <label for="customer_name">Full Name:</label>
                <input type="text" id="customer_name" name="customer_name" required>

                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" required>

                <h3>Order Summary</h3>
                <ul>
                    <?php
                    foreach ($_SESSION['cart'] as $id => $quantity):
                        $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $stmt->bind_result($name, $price);
                        $stmt->fetch();
                        $stmt->close();

                        if ($name):
                            $subtotal = $price * $quantity;
                            $totalPrice += $subtotal;
                            echo "<li>$name (x$quantity): " . number_format($subtotal, 2) . " VND</li>";
                        endif;
                    endforeach;
                    ?>
                </ul>
                <h4>Total: <?php echo number_format($totalPrice, 2); ?> VND</h4>

                <button type="submit">Place Order</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>



