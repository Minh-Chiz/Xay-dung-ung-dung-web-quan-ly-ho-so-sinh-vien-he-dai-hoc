<?php

require_once __DIR__ . '/db_connection.php';

/**
 * Lấy tất cả danh sách lớp học từ database
 * @return array Danh sách lớp học
 */
function getAllClasses() {
    $conn = getDbConnection();
    
    // Truy vấn lấy tất cả lớp học
    $sql = "SELECT id, class_code, class_name FROM classes ORDER BY id";
    $result = mysqli_query($conn, $sql);
    
    $classes = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $classes[] = $row;
        }
    }
    
    mysqli_close($conn);
    return $classes;
}

/**
 * Lấy thông tin một lớp học theo ID
 * @param int $id ID của lớp học
 * @return array|null Thông tin lớp học hoặc null nếu không tìm thấy
 */
function getClassById($id) {
    $conn = getDbConnection();
    
    $sql = "SELECT id, class_code, class_name FROM classes WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $class = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $class;
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    return null;
}

/**
 * Thêm lớp học mới
 * @param string $class_code Mã lớp
 * @param string $class_name Tên lớp
 * @return bool True nếu thành công, False nếu thất bại
 */
function addClass($class_code, $class_name) {
    $conn = getDbConnection();
    
    $sql = "INSERT INTO classes (class_code, class_name) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $class_code, $class_name);
        $success = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    
    mysqli_close($conn);
    return false;
}

/**
 * Cập nhật thông tin lớp học VÀ cập nhật tên lớp cho sinh viên
 * @param int $id ID của lớp học
 * @param string $class_code Mã lớp mới
 * @param string $class_name Tên lớp mới
 * @return bool True nếu thành công, False nếu thất bại
 */
function updateClass($id, $class_code, $class_name) {
    $conn = getDbConnection();
    
    // Bắt đầu Transaction (Giao dịch) để đảm bảo tính toàn vẹn dữ liệu
    // Nếu 1 trong 2 bước lỗi, nó sẽ quay lại trạng thái ban đầu
    mysqli_begin_transaction($conn);

    try {
        // BƯỚC 1: Lấy tên lớp CŨ trước khi thay đổi
        $oldClassName = "";
        $sqlGetOld = "SELECT class_name FROM classes WHERE id = ? LIMIT 1";
        $stmtGet = mysqli_prepare($conn, $sqlGetOld);
        mysqli_stmt_bind_param($stmtGet, "i", $id);
        mysqli_stmt_execute($stmtGet);
        $resultGet = mysqli_stmt_get_result($stmtGet);
        if ($row = mysqli_fetch_assoc($resultGet)) {
            $oldClassName = $row['class_name'];
        }
        mysqli_stmt_close($stmtGet);

        // BƯỚC 2: Cập nhật bảng classes (Bảng lớp học)
        $sqlClass = "UPDATE classes SET class_code = ?, class_name = ? WHERE id = ?";
        $stmtClass = mysqli_prepare($conn, $sqlClass);
        mysqli_stmt_bind_param($stmtClass, "ssi", $class_code, $class_name, $id);
        $resultClass = mysqli_stmt_execute($stmtClass);
        mysqli_stmt_close($stmtClass);

        if (!$resultClass) {
            throw new Exception("Lỗi khi cập nhật bảng classes");
        }

        // BƯỚC 3: Cập nhật bảng students (Bảng sinh viên)
        // Chỉ chạy nếu tên lớp thực sự thay đổi và lấy được tên cũ
        if (!empty($oldClassName) && $oldClassName !== $class_name) {
            // Cập nhật cột class trong bảng students: Đổi từ tên cũ sang tên mới
            $sqlStudent = "UPDATE students SET class = ? WHERE class = ?";
            $stmtStudent = mysqli_prepare($conn, $sqlStudent);
            // Lưu ý: Cần trim() để đảm bảo không lỗi do khoảng trắng thừa
            $cleanNewName = trim($class_name);
            $cleanOldName = trim($oldClassName);
            
            mysqli_stmt_bind_param($stmtStudent, "ss", $cleanNewName, $cleanOldName);
            mysqli_stmt_execute($stmtStudent);
            mysqli_stmt_close($stmtStudent);
        }

        // Nếu mọi thứ ổn, xác nhận lưu vào CSDL
        mysqli_commit($conn);
        mysqli_close($conn);
        return true;

    } catch (Exception $e) {
        // Nếu có lỗi, hoàn tác lại mọi thay đổi (Rollback)
        mysqli_rollback($conn);
        mysqli_close($conn);
        return false;
    }
}

/**
 * Xóa lớp học theo ID
 * @param int $id ID của lớp học cần xóa
 * @return bool True nếu thành công, False nếu thất bại
 */
function deleteClass($id) {
    $conn = getDbConnection();
    
    $sql = "DELETE FROM classes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    
    mysqli_close($conn);
    return false;
}
?>