<?php
// session_start();
require_once __DIR__ . '/../functions/student_functions.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateStudent();
        break;
    case 'edit':
        handleEditStudent();
        break;
    case 'delete':
        handleDeleteStudent();
        break;
    // default:
    //     header("Location: ../views/student.php?error=Hành động không hợp lệ");
    //     exit();
}

// -------------------------------------------------------------
// HÀM ĐỌC DỮ LIỆU (READ) - KHÔNG CẦN THAY ĐỔI
// -------------------------------------------------------------

/**
 * Lấy tất cả danh sách sinh viên
 */
function handleGetAllStudents() {
    return getAllStudents();
}

function handleGetStudentById($id) {
    return getStudentById($id);
}

// -------------------------------------------------------------
// HÀM THÊM DỮ LIỆU (CREATE) - ĐÃ CẬP NHẬT
// -------------------------------------------------------------

/**
 * Xử lý tạo sinh viên mới
 */
function handleCreateStudent() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/student.php?error=Phương thức không hợp lệ");
        exit();
    }
    
    // Kiểm tra tất cả các trường BẮT BUỘC
    if (!isset($_POST['student_code']) || !isset($_POST['student_name']) || !isset($_POST['student_date']) || !isset($_POST['gender']) || !isset($_POST['major']) || !isset($_POST['class'])) {
        header("Location: ../views/student/create_student.php?error=Thiếu thông tin bắt buộc");
        exit();
    }
    
    // Lấy dữ liệu và làm sạch/trim
    $student_code = trim($_POST['student_code']);
    $student_name = trim($_POST['student_name']);
    $student_date = trim($_POST['student_date']);
    $gender = trim($_POST['gender']);
    $major = trim($_POST['major']);
    $class = trim($_POST['class']);
    
    // Các trường không bắt buộc (dùng toán tử null coalescing để tránh lỗi nếu không tồn tại)
    $email = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    
    // Validate dữ liệu bắt buộc
    if (empty($student_code) || empty($student_name) || empty($student_date) || empty($gender) || empty($major) || empty($class)) {
        header("Location: ../views/student/create_student.php?error=Vui lòng điền đầy đủ thông tin bắt buộc");
        exit();
    }
    
    // Gọi hàm thêm sinh viên (CẦN CẬP NHẬT TRONG student_functions.php)
    $result = addStudent(
        $student_code, 
        $student_name, 
        $student_date, 
        $gender, 
        $major, 
        $class, 
        $email, 
        $phone_number
    );
    
    if ($result) {
        header("Location: ../views/student.php?success=Thêm sinh viên **$student_name** thành công");
    } else {
        // Có thể thêm logic kiểm tra lỗi trùng Mã sinh viên ở đây
        header("Location: ../views/student/create_student.php?error=Có lỗi xảy ra khi thêm sinh viên (có thể Mã SV bị trùng)");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa sinh viên
 */
function handleEditStudent() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/student.php?error=Phương thức không hợp lệ");
        exit();
    }
    
    // Kiểm tra tất cả các trường BẮT BUỘC và ID
    if (!isset($_POST['id']) || !isset($_POST['student_code']) || !isset($_POST['student_name']) || !isset($_POST['student_date']) || !isset($_POST['gender']) || !isset($_POST['major']) || !isset($_POST['class'])) {
        header("Location: ../views/student.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    
    $id = $_POST['id'];
    // Lấy dữ liệu và làm sạch/trim
    $student_code = trim($_POST['student_code']);
    $student_name = trim($_POST['student_name']);
    $student_date = trim($_POST['student_date']);
    $gender = trim($_POST['gender']);
    $major = trim($_POST['major']);
    $class = trim($_POST['class']);
    
    // Các trường không bắt buộc
    $email = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    
    // Validate dữ liệu bắt buộc
    if (empty($id) || empty($student_code) || empty($student_name) || empty($student_date) || empty($gender) || empty($major) || empty($class)) {
        header("Location: ../views/student/edit_student.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin bắt buộc");
        exit();
    }
    
    // Gọi function để cập nhật sinh viên (CẦN CẬP NHẬT TRONG student_functions.php)
    $result = updateStudent(
        $id, 
        $student_code, 
        $student_name, 
        $student_date, 
        $gender, 
        $major, 
        $class, 
        $email, 
        $phone_number
    );
    
    if ($result) {
        header("Location: ../views/student.php?success=Cập nhật sinh viên **$student_name** thành công");
    } else {
        header("Location: ../views/student/edit_student.php?id=" . $id . "&error=Cập nhật sinh viên thất bại (có thể Mã SV bị trùng)");
    }
    exit();
}

/**
 * Xử lý xóa sinh viên
 */
function handleDeleteStudent() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/student.php?error=Phương thức không hợp lệ");
        exit();
    }
    
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/student.php?error=Không tìm thấy ID sinh viên");
        exit();
    }
    
    $id = $_GET['id'];
    
    // Validate ID là số
    if (!is_numeric($id)) {
        header("Location: ../views/student.php?error=ID sinh viên không hợp lệ");
        exit();
    }
    
    // Gọi function để xóa sinh viên
    $result = deleteStudent($id);
    
    if ($result) {
        header("Location: ../views/student.php?success=Xóa sinh viên thành công");
    } else {
        header("Location: ../views/student.php?error=Xóa sinh viên thất bại");
    }
    exit();
}
?>