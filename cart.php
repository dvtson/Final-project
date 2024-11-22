<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .cart-container {
            background-color: white;
            max-width: 800px;
            margin: 0 auto;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .total {
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
        }
        .remove-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 5px 10px;
        }
        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin: 20px 0;
        }
        .checkout-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #0056d2;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .checkout-button:hover {
            background-color: #004bb0;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h2>Giỏ Hàng</h2>
        <?php
        session_start();
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "myDB";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }

        if (isset($_POST['remove_id'])) {
            $removeId = $_POST['remove_id'];
            if (isset($_SESSION['cart'][$removeId])) {
                unset($_SESSION['cart'][$removeId]);
                echo "<script>alert('Sản phẩm đã được xóa khỏi giỏ hàng.');</script>";
            }
        }

        $totalPrice = 0;
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])):
        ?>
            <div class="empty-cart">Giỏ hàng của bạn đang trống.</div>
            <a href="PhamarcySelling.html" class="checkout-button">Tiếp tục mua sắm</a>
        <?php else: ?>
            <?php foreach ($_SESSION['cart'] as $id => $quantity):
                $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $stmt->bind_result($name, $price);
                    $stmt->fetch();
                    $stmt->close();

                    if (!empty($name)) {
                        $totalPrice += $price * intval($quantity);
                        ?>
                        <div class="cart-item">
                            <span><?php echo htmlspecialchars($name); ?> (x<?php echo $quantity; ?>)</span>
                            <span><?php echo number_format($price * intval($quantity), 0, ',', '.'); ?> VND</span>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="remove_id" value="<?php echo $id; ?>">
                                <button type="submit" class="remove-button">Xóa</button>
                            </form>
                        </div>
                        <?php
                    } else {
                        echo "<p style='color:red;'>Sản phẩm ID $id không tồn tại.</p>";
                    }
                }
            endforeach; ?>
            <div class="total">Tổng cộng: <?php echo number_format($totalPrice, 0, ',', '.'); ?> VND</div>
            <button onclick="location.href='checkout.php'" class="checkout-button">Thanh toán</button>
        <?php endif; ?>
    </div>
    <?php
    $conn->close();
    ?>
</body>
</html>