<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy tất cả danh sách điểm số cùng với tên sinh viên và học phần
 * @param int|null $student_id_filter Lọc theo ID sinh viên cụ thể (nếu được cung cấp)
 * @return array Danh sách điểm
 */
function getAllGrades($student_id_filter = null) { // Thêm tham số
    $conn = getDbConnection();
    
    // THAY ĐỔI: g.score thành g.grade
    $sql = "
        SELECT
            g.id, g.grade, g.term,
            s.student_code, s.student_name,
            sub.subject_code, sub.subject_name
        FROM grades g
        JOIN students s ON g.student_id = s.id
        JOIN subjects sub ON g.subject_id = sub.id
    ";
    if ($student_id_filter !== null) {
        $sql .= " WHERE g.student_id = ?";
    }
    
    $sql .= " ORDER BY s.student_code, sub.subject_code";
    
    $stmt = mysqli_prepare($conn, $sql);

    // Nếu có lọc, bind param
    if ($student_id_filter !== null && $stmt) {
        mysqli_stmt_bind_param($stmt, "i", $student_id_filter);
    }
    
    $grades = [];
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) { 
                $grades[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    return $grades;
}

/**
 * Lấy thông tin một điểm số theo ID
 * @param int $id ID của điểm số
 * @return array|null Thông tin điểm số hoặc null nếu không tìm thấy
 */
function getGradeById($id) {
    $conn = getDbConnection();
    
    // THAY ĐỔI: score thành grade
    $sql = "SELECT id, student_id, subject_id, grade, term FROM grades WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $grade = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $grade;
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    return null;
}

/**
 * Thêm điểm số mới
 * @param int $student_id ID sinh viên
 * @param int $subject_id ID học phần
 * @param float $grade Điểm số
 * @param string $term Học kỳ
 * @return array Mảng ['success' => bool, 'message' => string]
 */
function addGrade($student_id, $subject_id, $grade, $term = '') {
    $conn = getDbConnection();
    
    // THAY ĐỔI: score thành grade
    $sql = "INSERT INTO grades (student_id, subject_id, grade, term) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        $error_message = "Lỗi chuẩn bị SQL: " . mysqli_error($conn);
        mysqli_close($conn);
        return ['success' => false, 'message' => $error_message];
    }
    
    // THAY ĐỔI: $score thành $grade (kiểu dữ liệu 'd' - double/decimal vẫn đúng)
    mysqli_stmt_bind_param($stmt, "iids", $student_id, $subject_id, $grade, $term);
    $success = mysqli_stmt_execute($stmt);
    
    if ($success) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return ['success' => true, 'message' => "Thêm thành công."];
    } else {
        $error_message = mysqli_error($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        // Kiểm tra lỗi trùng khóa UNIQUE (mã lỗi 1062)
        if (strpos($error_message, 'Duplicate entry') !== false) {
            return ['success' => false, 'message' => "(Có thể sinh viên đã có điểm cho học phần này)"];
        }
        
        return ['success' => false, 'message' => "Lỗi thực thi MySQL: " . $error_message];
    }
}

/**
 * Cập nhật thông tin điểm số
 * @param int $id ID của bản ghi điểm
 * @param int $student_id ID sinh viên
 * @param int $subject_id ID học phần
 * @param float $grade Điểm số mới
 * @param string $term Học kỳ mới
 * @return array Mảng ['success' => bool, 'message' => string]
 */
function updateGrade($id, $student_id, $subject_id, $grade, $term = '') {
    $conn = getDbConnection();
    
    // THAY ĐỔI: score = ? thành grade = ?
    $sql = "UPDATE grades SET student_id = ?, subject_id = ?, grade = ?, term = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        $error_message = "Lỗi chuẩn bị SQL: " . mysqli_error($conn);
        mysqli_close($conn);
        return ['success' => false, 'message' => $error_message];
    }
    
    // THAY ĐỔI: $score thành $grade (kiểu 'd' vẫn đúng)
    mysqli_stmt_bind_param($stmt, "iidsi", $student_id, $subject_id, $grade, $term, $id);
    $success = mysqli_stmt_execute($stmt);
    
    if ($success) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return ['success' => true, 'message' => "Cập nhật thành công."];
    } else {
        $error_message = mysqli_error($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        // Kiểm tra lỗi trùng khóa UNIQUE (mã lỗi 1062)
        if (strpos($error_message, 'Duplicate entry') !== false) {
            return ['success' => false, 'message' => "Cặp Sinh viên và Học phần này đã tồn tại ở một bản ghi điểm khác."];
        }
        
        return ['success' => false, 'message' => "Lỗi thực thi MySQL: " . $error_message];
    }
}

/**
 * Xóa điểm số theo ID
 * @param int $id ID của điểm số cần xóa
 * @return bool True nếu thành công, False nếu thất bại
 */
function deleteGrade($id) {
    $conn = getDbConnection();
    
    $sql = "DELETE FROM grades WHERE id = ?";
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
