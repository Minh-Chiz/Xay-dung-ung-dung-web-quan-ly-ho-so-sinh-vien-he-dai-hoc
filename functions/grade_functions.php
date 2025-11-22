<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy danh sách điểm, có hỗ trợ lọc theo ID sinh viên, Mã SV và Lớp
 * @param int|null $student_id_filter Lọc theo ID sinh viên cụ thể (dành cho trang cá nhân của SV)
 * @param string $search_code Tìm theo Mã sinh viên
 * @param string $search_class Tìm theo Tên lớp
 * @return array Danh sách điểm
 */
function getAllGrades($student_id_filter = null, $search_code = '', $search_class = '') {
    $conn = getDbConnection();
    
    // Thêm s.class vào câu lệnh SELECT để lấy tên lớp
    $sql = "
        SELECT
            g.id, g.grade, g.term,
            s.student_code, s.student_name, s.class,
            sub.subject_code, sub.subject_name
        FROM grades g
        JOIN students s ON g.student_id = s.id
        JOIN subjects sub ON g.subject_id = sub.id
        WHERE 1=1
    ";
    
    $params = [];
    $types = "";

    // 1. Lọc theo ID sinh viên (Dành cho view của sinh viên)
    if ($student_id_filter !== null) {
        $sql .= " AND g.student_id = ?";
        $params[] = &$student_id_filter;
        $types .= "i";
    }

    // 2. Lọc theo Mã sinh viên (Tìm kiếm)
    if (!empty($search_code)) {
        $sql .= " AND s.student_code LIKE ?";
        $search_code_param = "%" . $search_code . "%";
        $params[] = &$search_code_param;
        $types .= "s";
    }

    // 3. Lọc theo Lớp (Tìm kiếm)
    if (!empty($search_class)) {
        $sql .= " AND s.class LIKE ?";
        $search_class_param = "%" . $search_class . "%";
        $params[] = &$search_class_param;
        $types .= "s";
    }
    
    $sql .= " ORDER BY g.id ASC"; // Sắp xếp
    
    $stmt = mysqli_prepare($conn, $sql);

    if (!empty($types) && $stmt) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
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
 */
function getGradeById($id) {
    $conn = getDbConnection();
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
 */
function addGrade($student_id, $subject_id, $grade, $term = '') {
    $conn = getDbConnection();
    $sql = "INSERT INTO grades (student_id, subject_id, grade, term) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        return ['success' => false, 'message' => mysqli_error($conn)];
    }
    
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
        if (strpos($error_message, 'Duplicate entry') !== false) {
            return ['success' => false, 'message' => "(Sinh viên đã có điểm môn này rồi)"];
        }
        return ['success' => false, 'message' => $error_message];
    }
}

/**
 * Cập nhật thông tin điểm số
 */
function updateGrade($id, $student_id, $subject_id, $grade, $term = '') {
    $conn = getDbConnection();
    $sql = "UPDATE grades SET student_id = ?, subject_id = ?, grade = ?, term = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        return ['success' => false, 'message' => mysqli_error($conn)];
    }
    
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
        if (strpos($error_message, 'Duplicate entry') !== false) {
            return ['success' => false, 'message' => "Trùng lặp: Sinh viên này đã có điểm môn học này."];
        }
        return ['success' => false, 'message' => $error_message];
    }
}

/**
 * Xóa điểm số theo ID
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