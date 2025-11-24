<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';
require_once __DIR__ . '/../functions/auth.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['user_id'];

    // 1. Validate dữ liệu trống
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        header("Location: ../views/change_password.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    // 2. Kiểm tra mật khẩu mới và xác nhận mật khẩu
    if ($new_password !== $confirm_password) {
        header("Location: ../views/change_password.php?error=Mật khẩu mới và xác nhận mật khẩu không khớp");
        exit();
    }

    // 3. Kiểm tra độ dài mật khẩu (Tùy chọn)
    if (strlen($new_password) < 6) {
        header("Location: ../views/change_password.php?error=Mật khẩu mới phải có ít nhất 6 ký tự");
        exit();
    }

    // 4. Gọi hàm xử lý từ auth.php
    $result = changeUserPassword($user_id, $current_password, $new_password);

    if ($result['success']) {
        $_SESSION['success'] = $result['message'];
        header("Location: ../views/dashboard.php");
    } else {
        // Nếu thất bại thì vẫn ở lại trang đổi mật khẩu để báo lỗi
        header("Location: ../views/change_password.php?error=" . urlencode($result['message']));
    }
    exit();

} else {
    // Nếu truy cập trực tiếp file này mà không phải POST
    header("Location: ../views/change_password.php");
    exit();
}
?>