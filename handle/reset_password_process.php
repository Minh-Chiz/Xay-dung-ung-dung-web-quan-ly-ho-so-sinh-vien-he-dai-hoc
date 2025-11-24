<?php
session_start();
require_once __DIR__ . '/../functions/db_connection.php';
require_once __DIR__ . '/../functions/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 1. Validate dữ liệu
    if (empty($username) || empty($email) || empty($new_password) || empty($confirm_password)) {
        header("Location: ../views/forgot_password.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    if ($new_password !== $confirm_password) {
        header("Location: ../views/forgot_password.php?error=Mật khẩu xác nhận không khớp");
        exit();
    }
    
    if (strlen($new_password) < 6) {
        header("Location: ../views/forgot_password.php?error=Mật khẩu phải có ít nhất 6 ký tự");
        exit();
    }

    // 2. Gọi hàm reset mật khẩu
    $result = resetStudentPassword($username, $email, $new_password);

    if ($result['success']) {
        // Nếu thành công, chuyển hướng về trang đăng nhập với thông báo
        $_SESSION['success'] = $result['message'];
        header("Location: ../index.php");
    } else {
        // Nếu thất bại, quay lại trang quên mật khẩu
        header("Location: ../views/forgot_password.php?error=" . urlencode($result['message']));
    }
    exit();
} else {
    header("Location: ../views/forgot_password.php");
    exit();
}
?>