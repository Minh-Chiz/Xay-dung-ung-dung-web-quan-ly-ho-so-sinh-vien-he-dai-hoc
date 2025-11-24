<?php
// Nhom15/handle/user_process.php
require_once __DIR__ . '/../functions/user_functions.php';
require_once __DIR__ . '/../functions/auth.php';

// CHỈ CHO PHÉP ADMIN
checkRole(['admin'], '../views/dashboard.php', "Bạn không có quyền quản lý tài khoản.");

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        handleCreateUser();
        break;
    case 'edit':
        handleEditUser();
        break;
    case 'delete':
        handleDeleteUser();
        break;
    default:
        header("Location: ../views/user.php");
        exit();
}

function handleCreateUser() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'student';

    if (empty($username) || empty($password)) {
        header("Location: ../views/user/create_user.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = addUser($username, $password, $role);

    if ($result['success']) {
        header("Location: ../views/user.php?success=Thêm tài khoản thành công");
    } else {
        header("Location: ../views/user/create_user.php?error=" . urlencode($result['message']));
    }
    exit();
}

function handleEditUser() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

    $id = $_POST['id'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? ''; // Nếu rỗng nghĩa là không đổi pass
    $role = $_POST['role'] ?? 'student';

    if (empty($id) || empty($username)) {
        header("Location: ../views/user.php?error=Thiếu thông tin");
        exit();
    }

    $result = updateUser($id, $username, $password, $role);

    if ($result['success']) {
        header("Location: ../views/user.php?success=Cập nhật tài khoản thành công");
    } else {
        header("Location: ../views/user/edit_user.php?id=$id&error=" . urlencode($result['message']));
    }
    exit();
}

function handleDeleteUser() {
    $id = $_GET['id'] ?? '';
    if (empty($id)) die();

    // Ngăn admin tự xóa chính mình (nếu cần thiết)
    $currentUser = getLoggedInUser();
    if ($currentUser['id'] == $id) {
        header("Location: ../views/user.php?error=Bạn không thể xóa chính tài khoản đang đăng nhập");
        exit();
    }

    if (deleteUser($id)) {
        header("Location: ../views/user.php?success=Xóa tài khoản thành công");
    } else {
        header("Location: ../views/user.php?error=Xóa thất bại");
    }
    exit();
}
?>