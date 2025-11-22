<?php

require_once __DIR__ . '/db_connection.php';

/**
 * Lấy tất cả danh sách học phần từ database
 * @return array Danh sách học phần
 */
function getAllSubjects() {
    $conn = getDbConnection();
    
    // Truy vấn lấy tất cả học phần
    $sql = "SELECT id, subject_code, subject_name, credits FROM subjects ORDER BY id";
    $result = mysqli_query($conn, $sql);
    
    $subjects = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $subjects[] = $row;
        }
    }
    
    mysqli_close($conn);
    return $subjects;
}

/**
 * Lấy thông tin một học phần theo ID
 * @param int $id ID của học phần
 * @return array|null Thông tin học phần hoặc null nếu không tìm thấy
 */
function getSubjectById($id) {
    $conn = getDbConnection();
    
    $sql = "SELECT id, subject_code, subject_name, credits FROM subjects WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $subject = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $subject;
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    return null;
}

/**
 * Thêm học phần mới
 * @param string $subject_code Mã học phần
 * @param string $subject_name Tên học phần
 * @param int $credits Số tín chỉ
 * @return bool True nếu thành công, False nếu thất bại
 */
function addSubject($subject_code, $subject_name, $credits) {
    $conn = getDbConnection();
    
    // Thêm học phần mới. Sử dụng UNIQUE KEY trên subject_code để ngăn trùng lặp
    $sql = "INSERT INTO subjects (subject_code, subject_name, credits) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Tham số: string (mã), string (tên), integer (tín chỉ)
        mysqli_stmt_bind_param($stmt, "ssi", $subject_code, $subject_name, $credits);
        $success = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    
    mysqli_close($conn);
    return false;
}

/**
 * Cập nhật thông tin học phần
 * @param int $id ID của học phần
 * @param string $subject_code Mã học phần mới
 * @param string $subject_name Tên học phần mới
 * @param int $credits Số tín chỉ mới
 * @return bool True nếu thành công, False nếu thất bại
 */
function updateSubject($id, $subject_code, $subject_name, $credits) {
    $conn = getDbConnection();
    
    $sql = "UPDATE subjects SET subject_code = ?, subject_name = ?, credits = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Tham số: string, string, integer, integer
        mysqli_stmt_bind_param($stmt, "ssii", $subject_code, $subject_name, $credits, $id);
        $success = mysqli_stmt_execute($stmt);
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    
    mysqli_close($conn);
    return false;
}

/**
 * Xóa học phần theo ID
 * @param int $id ID của học phần cần xóa
 * @return bool True nếu thành công, False nếu thất bại
 */
function deleteSubject($id) {
    $conn = getDbConnection();
    
    $sql = "DELETE FROM subjects WHERE id = ?";
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
