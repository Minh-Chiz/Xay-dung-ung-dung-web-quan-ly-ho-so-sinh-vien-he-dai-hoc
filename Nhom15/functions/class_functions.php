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
 * Cập nhật thông tin lớp học
 * @param int $id ID của lớp học
 * @param string $class_code Mã lớp mới
 * @param string $class_name Tên lớp mới
 * @return bool True nếu thành công, False nếu thất bại
 */
function updateClass($id, $class_code, $class_name) {
    $conn = getDbConnection();
    
    $sql = "UPDATE classes SET class_code = ?, class_name = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $class_code, $class_name, $id);
        $success = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    
    mysqli_close($conn);
    return false;
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