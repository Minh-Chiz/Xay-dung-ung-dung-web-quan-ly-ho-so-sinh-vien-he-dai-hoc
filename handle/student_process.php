<?php
// session_start();
require_once __DIR__ . '/../functions/student_functions.php';
require_once __DIR__ . '/../functions/auth.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        checkRole(['teacher', 'admin'], '../views/student.php', "Bạn không có quyền thêm sinh viên.");
        handleCreateStudent();
        break;
    case 'edit':
        checkRole(['teacher', 'admin'], '../views/student.php', "Bạn không có quyền chỉnh sửa sinh viên.");
        handleEditStudent();
        break;
    case 'delete':
        checkRole(['teacher', 'admin'], '../views/student.php', "Bạn không có quyền xóa sinh viên.");
        handleDeleteStudent();
        break;
}

/**
 * Lấy tất cả danh sách sinh viên
 * @param string $search_code Mã sinh viên cần tìm
 * @param string $search_class Lớp cần tìm (MỚI)
 */
function handleGetAllStudents($search_code = '', $search_class = '') {
    return getAllStudents($search_code, $search_class); // Truyền cả 2 tham số
}

function handleGetStudentById($id) {
    return getStudentById($id);
}

function handleGetStudentByCode($student_code) {
    return getStudentByCode($student_code);
}

/**
 * Xử lý tạo sinh viên mới
 */
function handleCreateStudent() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/student.php?error=Phương thức không hợp lệ");
        exit();
    }
    
    if (!isset($_POST['student_code']) || !isset($_POST['student_name']) || !isset($_POST['student_date'])){
        header("Location: ../views/student/create_student.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    
    $student_code = trim($_POST['student_code']);
    $student_name = trim($_POST['student_name']);
    $student_date = $_POST['student_date'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $major = trim($_POST['major'] ?? '');
    $class = trim($_POST['class'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone_number'] ?? '');
    
    if (empty($student_code) || empty($student_name) || empty($student_date) || empty($gender) || empty($major) || empty($class)) {
        header("Location: ../views/student/create_student.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }
    
    $result = addStudent($student_code, $student_name, $student_date, $gender, $major, $class, $email, $phone);
    
    if ($result) {
        header("Location: ../views/student.php?success=Thêm sinh viên thành công");
    } else {
        header("Location: ../views/student/create_student.php?error=Có lỗi xảy ra khi thêm sinh viên");
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
    
    // [MỚI] Lấy các tham số điều hướng từ form
    $redirect = $_POST['redirect'] ?? '';
    $class_id = $_POST['class_id'] ?? '';
    
    // Kiểm tra các trường bắt buộc
    if (!isset($_POST['id']) || !isset($_POST['student_code']) || !isset($_POST['student_name'])) {
        header("Location: ../views/student.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    
    $id = $_POST['id'];
    $student_code = trim($_POST['student_code']);
    $student_name = trim($_POST['student_name']);
    $student_date = $_POST['student_date'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $major = trim($_POST['major'] ?? '');
    $class = trim($_POST['student_class'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    
    // Tạo chuỗi tham số URL để giữ lại trạng thái điều hướng nếu có lỗi
    $redirectParams = "";
    if (!empty($redirect) && !empty($class_id)) {
        $redirectParams = "&redirect=" . urlencode($redirect) . "&class_id=" . urlencode($class_id);
    }

    // Validate dữ liệu rỗng
    if (empty($student_code) || empty($student_name) || empty($student_date) || empty($gender) || empty($major) || empty($class)) {
        // Quay lại trang sửa kèm theo thông báo lỗi và tham số điều hướng
        header("Location: ../views/student/edit_student.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin" . $redirectParams);
        exit();
    }
    
    // Gọi hàm cập nhật trong CSDL
    $result = updateStudent($id, $student_code, $student_name, $student_date, $gender, $major, $class, $email, $phone_number);
    
    if ($result) {
        if ($redirect === 'class' && !empty($class_id)) {
            // Nếu sửa từ trang Lớp học -> Quay về trang Chi tiết lớp học
            header("Location: ../views/class/class_details.php?id=" . $class_id . "&success=Cập nhật sinh viên thành công");
        } else {
            // Mặc định -> Quay về danh sách sinh viên chung
            header("Location: ../views/student.php?success=Cập nhật sinh viên thành công");
        }
    } else {
        // Lỗi cập nhật -> Quay lại trang sửa
        header("Location: ../views/student/edit_student.php?id=" . $id . "&error=Cập nhật sinh viên thất bại" . $redirectParams);
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
    
    if (!is_numeric($id)) {
        header("Location: ../views/student.php?error=ID sinh viên không hợp lệ");
        exit();
    }
    
    $result = deleteStudent($id);
    
    if ($result) {
        header("Location: ../views/student.php?success=Xóa sinh viên thành công");
    } else {
        header("Location: ../views/student.php?error=Xóa sinh viên thất bại");
    }
    exit();
}
?>
