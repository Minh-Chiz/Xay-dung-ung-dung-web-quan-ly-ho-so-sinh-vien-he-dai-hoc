<?php
/**
 * Hàm kiểm tra xem người dùng đã đăng nhập hay chưa
 * Nếu chưa, chuyển hướng họ đến trang đăng nhập
 * * @param string $redirectPath Đường dẫn để chuyển hướng sau khi đăng nhập
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
 * * @param string $redirectPath Đường dẫn để chuyển hướng sau khi đăng xuất
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
 * * @return array|null Mảng thông tin người dùng hoặc null nếu chưa đăng nhập
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
 * Hàm kiểm tra vai trò người dùng
 * Nếu vai trò không được phép, chuyển hướng họ và hiển thị thông báo lỗi
 * * @param array $allowedRoles Mảng chứa các vai trò được phép (ví dụ: ['teacher', 'admin'])
 * @param string $redirectPath Đường dẫn để chuyển hướng nếu không có quyền
 * @param string $errorMessage Thông báo lỗi tùy chỉnh
 */
function checkRole($allowedRoles, $redirectPath, $errorMessage = "Bạn không có quyền truy cập chức năng này.") {
    // Khởi động session nếu chưa có
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $user = getLoggedInUser();
    
    // Kiểm tra xem user đã đăng nhập, có role và role đó có trong danh sách được phép
    if (!$user || !isset($user['role']) || !in_array($user['role'], $allowedRoles)) {
        $_SESSION['error'] = $errorMessage;
        header('Location: ' . $redirectPath);
        exit();
    }
}


/**
 * Hàm kiểm tra xem người dùng đã đăng nhập hay chưa
 * * @return bool True nếu đã đăng nhập, False nếu chưa
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

/**
 * Thay đổi mật khẩu người dùng
 * @param int $userId ID người dùng
 * @param string $currentPassword Mật khẩu hiện tại (nhập từ form)
 * @param string $newPassword Mật khẩu mới
 * @return array ['success' => bool, 'message' => string]
 */
function changeUserPassword($userId, $currentPassword, $newPassword) {
    $conn = getDbConnection();
    
    // 1. Lấy mật khẩu hiện tại trong DB để kiểm tra
    $sql = "SELECT password FROM users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$user) {
        mysqli_close($conn);
        return ['success' => false, 'message' => 'Người dùng không tồn tại.'];
    }

    // 2. So sánh mật khẩu cũ (Lưu ý: Hệ thống hiện tại đang dùng plain text, chưa mã hóa)
    // Nếu sau này bạn nâng cấp lên password_hash, hãy đổi thành password_verify()
    if ($currentPassword !== $user['password']) {
        mysqli_close($conn);
        return ['success' => false, 'message' => 'Mật khẩu hiện tại không chính xác.'];
    }

    // 3. Cập nhật mật khẩu mới
    $updateSql = "UPDATE users SET password = ? WHERE id = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "si", $newPassword, $userId);
    $updateSuccess = mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);
    mysqli_close($conn);

    if ($updateSuccess) {
        return ['success' => true, 'message' => 'Đổi mật khẩu thành công.'];
    } else {
        return ['success' => false, 'message' => 'Lỗi hệ thống, không thể cập nhật mật khẩu.'];
    }
}

/**
 * Đặt lại mật khẩu cho sinh viên nếu thông tin xác thực đúng
 * @param string $username Mã sinh viên (Tên đăng nhập)
 * @param string $email Email đã đăng ký
 * @param string $newPassword Mật khẩu mới
 * @return array ['success' => bool, 'message' => string]
 */
function resetStudentPassword($username, $email, $newPassword) {
    $conn = getDbConnection();

    // 1. Kiểm tra xem Mã SV và Email có khớp trong bảng students không
    // Lưu ý: Chúng ta join với bảng users để đảm bảo tài khoản tồn tại
    $sql = "SELECT s.id 
            FROM students s
            JOIN users u ON s.student_code = u.username
            WHERE s.student_code = ? AND s.email = ? LIMIT 1";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $student = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$student) {
        mysqli_close($conn);
        return ['success' => false, 'message' => 'Thông tin không chính xác. Vui lòng kiểm tra Mã sinh viên và Email.'];
    }

    // 2. Nếu thông tin đúng, cập nhật mật khẩu trong bảng users
    $updateSql = "UPDATE users SET password = ? WHERE username = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "ss", $newPassword, $username);
    $success = mysqli_stmt_execute($updateStmt);
    
    mysqli_stmt_close($updateStmt);
    mysqli_close($conn);

    if ($success) {
        return ['success' => true, 'message' => 'Mật khẩu đã được đặt lại thành công. Bạn có thể đăng nhập ngay.'];
    } else {
        return ['success' => false, 'message' => 'Lỗi hệ thống. Vui lòng thử lại sau.'];
    }
}

?>