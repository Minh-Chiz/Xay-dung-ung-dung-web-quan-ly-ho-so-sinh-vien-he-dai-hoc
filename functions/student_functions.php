<?php
require_once __DIR__ . '/db_connection.php';

/**
 * Lấy danh sách students từ database, có thể lọc theo Mã SV và Lớp
 */
function getAllStudents($search_code = '', $search_class = '') {
    $conn = getDbConnection();
    
    $sql = "SELECT id, student_code, student_name, student_date, gender, major, class, email, phone_number 
            FROM students
            WHERE 1=1";
    
    $params = [];
    $types = "";

    if (!empty($search_code)) {
        $sql .= " AND student_code LIKE ?";
        $search_code_param = "%" . $search_code . "%";
        $params[] = &$search_code_param;
        $types .= "s";
    }

    if (!empty($search_class)) {
        $sql .= " AND class LIKE ?";
        $search_class_param = "%" . $search_class . "%"; 
        $params[] = &$search_class_param;
        $types .= "s";
    }
    
    $sql .= " ORDER BY id ASC"; 
    
    $stmt = mysqli_prepare($conn, $sql);

    if (!empty($types) && $stmt) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    $students = [];
    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    return $students;
}

/**
 * Thêm student mới
 */
function addStudent($student_code, $student_name, $student_date, $gender, $major, $class, $email = '', $phone = '') {
    $conn = getDbConnection();
    $sql = "INSERT INTO students (student_code, student_name, student_date, gender, major, class, email, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssss", $student_code, $student_name, $student_date, $gender, $major, $class, $email, $phone);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Lấy thông tin một student theo ID
 */
function getStudentById($id) {
    $conn = getDbConnection();
    $sql = "SELECT id, student_code, student_name,student_date, gender, major, class, email, phone_number FROM students WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $student = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $student;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

/**
 * Cập nhật thông tin student
 */
function updateStudent($id, $student_code, $student_name, $student_date, $gender, $major, $class, $email = '', $phone_number = '') {
    $conn = getDbConnection();
    $sql = "UPDATE students SET student_code = ?, student_name = ?, student_date = ?, gender = ?, major = ?, class = ?, email = ?, phone_number = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $student_code, $student_name, $student_date, $gender, $major, $class, $email, $phone_number, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }
    mysqli_close($conn);
    return false;
}

/**
 * Xóa student theo ID
 */
function deleteStudent($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM students WHERE id = ?";
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

/**
 * Lấy thông tin một student theo student_code
 */
function getStudentByCode($student_code) {
    $conn = getDbConnection();
    $sql = "SELECT id, student_code, student_name, student_date, gender, major, class, email, phone_number FROM students WHERE student_code = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $student_code);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $student = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $student;
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return null;
}

/**
 * Lấy danh sách sinh viên thuộc một lớp cụ thể (Theo tên lớp)
 */
function getStudentsByClassName($className) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM students WHERE TRIM(class) = ? ORDER BY student_name ASC";
    $stmt = mysqli_prepare($conn, $sql);
    $students = [];
    if ($stmt) {
        $className = trim($className);
        mysqli_stmt_bind_param($stmt, "s", $className);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return $students;
}

/**
 * [MỚI] Lấy danh sách sinh viên theo Tên Lớp HOẶC Mã Lớp
 * Giúp hiển thị đầy đủ danh sách dù dữ liệu sinh viên lưu Mã hay Tên lớp
 */
function getStudentsByClassAttributes($className, $classCode) {
    $conn = getDbConnection();
    // Tìm sinh viên có trường 'class' trùng với Tên Lớp HOẶC trùng với Mã Lớp
    $sql = "SELECT * FROM students WHERE TRIM(class) = ? OR TRIM(class) = ? ORDER BY student_name ASC";
    $stmt = mysqli_prepare($conn, $sql);
    
    $students = [];
    if ($stmt) {
        $className = trim($className);
        $classCode = trim($classCode);
        // Bind 2 tham số string (ss)
        mysqli_stmt_bind_param($stmt, "ss", $className, $classCode);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $students[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    return $students;
}
?>