<?php
include "conn.inc";
session_start();

echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin Dashboard</title>
    <link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap' rel='stylesheet'/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('https://raw.githubusercontent.com/adi1090x/files/master/dynamic-wallpaper/main.gif');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }
        .container {
            background: rgba(0, 0, 0, 0.5);
            color: #333;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            font-size: 24px;
            color: #fff;
            margin-bottom: 20px;
        }
        .welcome {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
			color: #fff
        }
        a {
            display: block;
            margin: 10px 0;
            padding: 12px;
            background: linear-gradient(90deg, #0056d2, #0078ff);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        a:hover {
            background: linear-gradient(90deg, #004bb0, #0056d2);
            transform: scale(1.05);
        }
        .logout {
            background: #ff4d4d;
        }
        .logout:hover {
            background: #e03e3e;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Admin Dashboard</h1>
        <div class='welcome'>Chào mừng, Admin " . htmlspecialchars($_SESSION['user']) . "!</div>";

if ($_SESSION['role'] == 'admin') {
    echo "<a href='manageuser.php'>Quản lý người dùng</a>";
    echo "<a href='addproduct.php'>Thêm sản phẩm</a>";
    echo "<a href='logout2.php' class='logout'>Đăng xuất</a>";
}

echo "</div>
</body>
</html>";
?>



