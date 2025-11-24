<?php
// Nhom15/functions/user_functions.php
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy tất cả danh sách người dùng
 */
function getAllUsers() {
    $conn = getDbConnection();
    $sql = "SELECT id, username, role FROM users ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    
    $users = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }
    mysqli_close($conn);
    return $users;
}

/**
 * Lấy thông tin user theo ID
 */
function getUserById($id) {
    $conn = getDbConnection();
    $sql = "SELECT id, username, role FROM users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $user;
    }
    mysqli_close($conn);
    return null;
}

/**
 * Thêm user mới
 */
function addUser($username, $password, $role) {
    $conn = getDbConnection();
    
    // Kiểm tra username đã tồn tại chưa
    $checkSql = "SELECT id FROM users WHERE username = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $username);
    mysqli_stmt_execute($checkStmt);
    if(mysqli_stmt_fetch($checkStmt)) {
        mysqli_stmt_close($checkStmt);
        mysqli_close($conn);
        return ['success' => false, 'message' => 'Tên đăng nhập đã tồn tại'];
    }
    mysqli_stmt_close($checkStmt);

    // Thêm mới
    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Lưu ý: Hệ thống hiện tại đang dùng mật khẩu dạng text thường (theo auth.php). 
        // Nếu nâng cấp bảo mật, hãy dùng password_hash($password, PASSWORD_DEFAULT) ở đây.
        mysqli_stmt_bind_param($stmt, "sss", $username, $password, $role);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return ['success' => $success, 'message' => $success ? 'Thêm thành công' : 'Lỗi Database'];
    }
    
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Lỗi kết nối'];
}

/**
 * Cập nhật user
 * Nếu $password rỗng thì giữ nguyên mật khẩu cũ
 */
function updateUser($id, $username, $password, $role) {
    $conn = getDbConnection();
    
    // Kiểm tra trùng username với người khác
    $checkSql = "SELECT id FROM users WHERE username = ? AND id != ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "si", $username, $id);
    mysqli_stmt_execute($checkStmt);
    if(mysqli_stmt_fetch($checkStmt)) {
        mysqli_stmt_close($checkStmt);
        mysqli_close($conn);
        return ['success' => false, 'message' => 'Tên đăng nhập đã tồn tại'];
    }
    mysqli_stmt_close($checkStmt);

    if (!empty($password)) {
        // Cập nhật cả mật khẩu
        $sql = "UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $username, $password, $role, $id);
    } else {
        // Chỉ cập nhật thông tin, giữ nguyên mật khẩu
        $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $role, $id);
    }

    $success = false;
    if ($stmt) {
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    return ['success' => $success, 'message' => $success ? 'Cập nhật thành công' : 'Lỗi Database'];
}

/**
 * Xóa user
 */
function deleteUser($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    $success = false;
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return $success;
}
?>