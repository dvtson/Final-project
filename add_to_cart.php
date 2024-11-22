<?php
session_start(); // Bắt đầu phiên làm việc

// Kiểm tra nếu có ID sản phẩm trong yêu cầu
if (isset($_POST['id'])) {
    $productId = $_POST['id'];

    // Khởi tạo giỏ hàng nếu chưa tồn tại
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Thêm sản phẩm vào giỏ hàng
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++; // Tăng số lượng nếu sản phẩm đã có
    } else {
        $_SESSION['cart'][$productId] = 1; // Thêm sản phẩm mới vào giỏ hàng
    }

    // Trả về thông báo thành công
    echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm vào giỏ hàng.']);
} else {
    // Trả về thông báo lỗi nếu không có ID sản phẩm
    echo json_encode(['success' => false, 'message' => 'Không có sản phẩm nào được chỉ định.']);
}
?>