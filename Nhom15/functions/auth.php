<?php
/**
 * Hàm kiểm tra xem người dùng đã đăng nhập hay chưa
 * Nếu chưa, chuyển hướng họ đến trang đăng nhập
 * 
 * @param string $redirectPath Đường dẫn để chuyển hướng sau khi đăng nhập
 */
function checkLogin($redirectPath = '../index.php') {
    //khởi động session nếu chưa có
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

   // Kiểm tra xem user đã đăng nhập chưa
    if (!isset($_SESSION['user_id']) || empty($_SESSION['username'])) {
        // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
        $_SESSION['error'] = "Vui lòng đăng nhập để tiếp tục.";
        header('Location: ' . $redirectPath);
        exit();
    }
}

/**
 * Hàm đăng xuất người dùng
 * Xóa session và chuyển hướng đến trang đăng nhập
 * 
 * @param string $redirectPath Đường dẫn để chuyển hướng sau khi đăng xuất
 */
function logout($redirectPath = '../index.php') {
    // Khởi động session nếu chưa có
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Xóa tất cả dữ liệu trong session
    session_unset();
    session_destroy();

    // Khởi tạo session mới để lưu thông báo
    session_start();
    $_SESSION['success'] = "Bạn đã đăng xuất thành công.";

    // Chuyển hướng đến trang đăng nhập
    header('Location: ' . $redirectPath);
    exit();
}

/**
 * Hàm lấy thông tin người dùng đã đăng nhập
 * 
 * @return array|null Mảng thông tin người dùng hoặc null nếu chưa đăng nhập
 */
function getLoggedInUser() {
    // Khởi động session nếu chưa có
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['user_id']) && !empty($_SESSION['username'])) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'] ?? null
        ];
    }

    return null;
}

/**
 * Hàm kiểm tra xem người dùng đã đăng nhập hay chưa
 * 
 * @return bool True nếu đã đăng nhập, False nếu chưa
 */
function isLoggedIn() {
    // Khởi động session nếu chưa có
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['user_id']) && !empty($_SESSION['username']);
}

/**
 * Hàm xác thực đăng nhập người dùng
 * @param mysqli $conn Kết nối cơ sở dữ liệu
 * @param string $username Tên đăng nhập
 * @param string $password Mật khẩu
 * @return array|false nếu đăng nhập thành công, False nếu thất bại
 */

function authenticateUser($conn, $username, $password){
    $sql = "SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) >0){
        $user = mysqli_fetch_assoc($result);
        if ($password === $user['password']){ // Nên dùng password_verify nếu có mã hóa
            mysqli_stmt_close($stmt);
            return $user;
        }
    }
    if ($stmt) mysqli_stmt_close($stmt);
        return false;
}

?>