<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách students từ database
 * @return array Danh sách students
 */
function getAllStudents() {
    $conn = getDbConnection();
    
    // Truy vấn lấy tất cả students
    $sql = "SELECT id, student_code, student_name FROM students ORDER BY id";
    $result = mysqli_query($conn, $sql);
    
    $students = [];
    if ($result && mysqli_num_rows($result) > 0) {
        // Lặp qua từng dòng trong kết quả truy vấn $result
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row; // Thêm mảng $row vào cuối mảng $students
        }
    }
    
    mysqli_close($conn);
    return $students;
}

/**
 * Thêm student mới
 * @param string $student_code Mã sinh viên
 * @param string $student_name Tên sinh viên
 * @param string $student_date Ngày sinh
 * @param string $gender Giới tính
 * @param string $major Ngành học
 * @param string $class Lớp học
 * @param string $email Email
 * @param string $phone Số điện thoại
 * @return bool True nếu thành công, False nếu thất bại
 */
function addStudent($student_code, $student_name, $student_date, $gender, $major, $class, $email = '', $phone = '') {
    $conn = getDbConnection();
    
    $sql = "INSERT INTO students (student_code, student_name, student_date, gender, major, class, email, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $student_code, $student_name, $student_date, $gender, $major, $class, $email, $phone);
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
 * @param int $id ID của student
 * @return array|null Thông tin student hoặc null nếu không tìm thấy
 */
function getStudentById($id) {
    $conn = getDbConnection();
    
    $sql = "SELECT id, student_code, student_name FROM students WHERE id = ? LIMIT 1";
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
 * @param int $id ID của student
 * @param string $student_code Mã sinh viên mới
 * @param string $student_name Tên sinh viên mới
 * @param string $student_date Ngày sinh mới
 * @param string $gender Giới tính mới
 * @param string $major Ngành học mới
 * @param string $class Lớp học mới
 * @param string $email Email mới
 * @param string $phone Số điện thoại mới
 * @return bool True nếu thành công, False nếu thất bại
 */
function updateStudent($id, $student_code, $student_name, $student_date, $gender, $major, $class, $email = '', $phone_number = '') {
    $conn = getDbConnection();
    
    $sql = "UPDATE students SET student_code = ?, student_name = ?, student_date = ?, gender = ?, major = ?, class = ?, email = ?, phone_number = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssgmcepi", $student_code, $student_name, $student_date, $gender, $major, $class, $email, $phone_number, $id);
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
 * @param int $id ID của student cần xóa
 * @return bool True nếu thành công, False nếu thất bại
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
?>
