<?php
session_start();
include 'config.inc';
include 'conn.inc';

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Lấy từ khóa tìm kiếm từ URL
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Truy vấn tìm kiếm sản phẩm từ cơ sở dữ liệu
$sql = 'SELECT * FROM products WHERE name LIKE ?';
$statement1 = $conn->prepare($sql);
$likeTerm = '%' . $searchTerm . '%';
$statement1->bind_param('s', $likeTerm);
$statement1->execute();
$result1 = $statement1->get_result();

$products = $result1->fetch_all(MYSQLI_ASSOC);

$statement1->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <style>
        /* CSS cho giao diện hiển thị kết quả */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .product-item {
            width: 300px;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-item img {
            max-width: 200px;
            height: auto;
        }

        .product-item h3 {
            margin: 10px 0;
            color: #333;
        }

        .product-item p {
            color: #666;
        }

        .product-item button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .product-item button:hover {
            background-color: #0056b3;
        }

        .no-products {
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Kết quả tìm kiếm cho "<?php echo htmlspecialchars($searchTerm); ?>"</h1>

    <!-- Hiển thị kết quả -->
    <div class="product-container">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <a href="getproduct.php?id=<?php echo $product['id']; ?>">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                    </a>
                    
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-products">Không tìm thấy sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>