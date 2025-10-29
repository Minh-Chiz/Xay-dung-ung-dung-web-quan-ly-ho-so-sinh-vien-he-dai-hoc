<?php
// Giả định file này chứa hàm getDbConnection()
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách students từ database
 * ĐÃ CẬP NHẬT: Lấy thêm 6 trường dữ liệu mới
 * @return array Danh sách students
 */
function getAllStudents() {
    $conn = getDbConnection();
    
    // TRUY VẤN MỚI: Lấy tất cả 8 trường dữ liệu
    $sql = "SELECT id, student_code, student_name, student_date, gender, major, class, email, phone_number 
            FROM students ORDER BY student_code ASC";
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
 * ĐÃ CẬP NHẬT: Nhận và lưu 8 trường dữ liệu
 * @param string $student_code Mã sinh viên
 * @param string $student_name Tên sinh viên
 * @param string $student_date Ngày sinh
 * @param string $gender Giới tính
 * @param string $major Khoa/Ngành
 * @param string $class Lớp
 * @param string $email Email
 * @param string $phone_number Số điện thoại
 * @return bool True nếu thành công, False nếu thất bại
 */
function addStudent($student_code, $student_name, $student_date, $gender, $major, $class, $email, $phone_number) {
    $conn = getDbConnection();
    
    // SQL MỚI: Thêm 8 placeholder (?) cho 8 trường
    $sql = "INSERT INTO students (student_code, student_name, student_date, gender, major, class, email, phone_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // BIND MỚI: "ssssssss" (8 tham số đều là string)
        mysqli_stmt_bind_param($stmt, "ssssssss", 
            $student_code, 
            $student_name, 
            $student_date, 
            $gender, 
            $major, 
            $class, 
            $email, 
            $phone_number
        );
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
 * ĐÃ CẬP NHẬT: Lấy thêm 6 trường dữ liệu mới
 * @param int $id ID của student
 * @return array|null Thông tin student hoặc null nếu không tìm thấy
 */
function getStudentById($id) {
    $conn = getDbConnection();
    
    // TRUY VẤN MỚI: Lấy tất cả 8 trường dữ liệu
    $sql = "SELECT id, student_code, student_name, student_date, gender, major, class, email, phone_number 
            FROM students WHERE id = ? LIMIT 1";
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
 * ĐÃ CẬP NHẬT: Nhận và cập nhật 8 trường dữ liệu
 * @param int $id ID của student
 * @param string $student_code Mã sinh viên mới
 * @param string $student_name Tên sinh viên mới
 * @param string $student_date Ngày sinh mới
 * @param string $gender Giới tính mới
 * @param string $major Khoa/Ngành mới
 * @param string $class Lớp mới
 * @param string $email Email mới
 * @param string $phone_number Số điện thoại mới
 * @return bool True nếu thành công, False nếu thất bại
 */
function updateStudent($id, $student_code, $student_name, $student_date, $gender, $major, $class, $email, $phone_number) {
    $conn = getDbConnection();
    
    // SQL MỚI: Cập nhật tất cả 8 trường
    $sql = "UPDATE students SET 
                student_code = ?, 
                student_name = ?, 
                student_date = ?, 
                gender = ?, 
                major = ?, 
                class = ?, 
                email = ?, 
                phone_number = ?
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // BIND MỚI: "ssssssssi" (8 string cho dữ liệu, 1 integer cho ID)
        mysqli_stmt_bind_param($stmt, "ssssssssi", 
            $student_code, 
            $student_name, 
            $student_date, 
            $gender, 
            $major, 
            $class, 
            $email, 
            $phone_number,
            $id
        );
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