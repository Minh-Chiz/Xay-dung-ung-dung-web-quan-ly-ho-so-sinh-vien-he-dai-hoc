<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])){
    handleLogin();
}

function handleLogin(){
    $conn = getDbConnection();
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
        header('Location: ../index.php');
        exit();
    }

    $user = authenticateUser($conn, $username, $password);
    if ($user){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['success'] = "Đăng nhập thành công. Chào mừng " . htmlspecialchars($user['username']) . "!";
        mysqli_close($conn);
        header('Location: ../views/student.php');
        exit();
    }

    $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng.";
    mysqli_close($conn);
    header('Location: ../index.php');
    exit();
}