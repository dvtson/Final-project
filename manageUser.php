<?php
include "conn.inc";

$sql = "SELECT * FROM login ORDER BY id";
$result = mysqli_query($conn, $sql);

echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Manage User</title>
    <link href='https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap' rel='stylesheet'/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-image: url('https://raw.githubusercontent.com/adi1090x/files/master/dynamic-wallpaper/main.gif');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            margin: 0;
            padding: 20px;
            color: #fff;
        }
        .container {
            background: #fff;
            color: #333;
            max-width: 900px;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #0056d2;
        }
        a {
            text-decoration: none;
            color: #0056d2;
            margin-bottom: 15px;
            display: inline-block;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #003f91;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 16px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #0056d2;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td a {
            color: #0056d2;
            font-weight: 500;
        }
        td a:hover {
            color: #003f91;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
        .footer a {
            color: #0056d2;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .footer a:hover {
            color: #003f91;
        }
        .add-user-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: linear-gradient(90deg, #0056d2, #0078ff);
            color: white;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .add-user-btn:hover {
            background: linear-gradient(90deg, #004bb0, #0056d2);
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class='container'>
    <h1>Quản lý người dùng</h1>
    <a href='addUser.html' class='add-user-btn'>Thêm người dùng</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên người dùng</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Sửa</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>";

while ($row = mysqli_fetch_array($result)) {
    $id = $row['id'];
    echo "<tr>
            <td>{$id}</td>
            <td>{$row['username']}</td>
            <td>{$row['email']}</td>
            <td>{$row['roles']}</td>
            <td>{$row['status']}</td>
            <td><a href='editUser.php?id={$id}'>Sửa</a></td>
            <td><a href='delUser.php?id={$id}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\");'>Xóa</a></td>
          </tr>";
}

echo "</tbody>
    </table>
    <div class='footer'>
        <p><a href='admindex.php'>Trang chủ Admin</a></p>
        <p><a href='logout2.php'>Đăng xuất</a></p>
    </div>
</div>

</body>
</html>";
?>



