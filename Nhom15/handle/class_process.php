<?php
// session_start();
require_once __DIR__ . '/../functions/class_functions.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateClass();
        break;
    case 'edit':
        handleEditClass();
        break;
    case 'delete':
        handleDeleteClass();
        break;
    // default:
    //     header("Location: ../views/class.php?error=Hành động không hợp lệ");
    //     exit();
}
/**
 * Lấy tất cả danh sách lớp học
 */
function handleGetAllClasses() {
    return getAllClasses();
    // Xử lý hiển thị danh sách classes
}
function handleGetClassById($id) {
    return getClassById($id);
}

/**
 * Xử lý tạo lớp mới
 */
function handleCreateClass () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/class.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['class_code']) || !isset($_POST['class_name'])) {
        header("Location: ../views/class/create_class.php?error=Thiếu thông tin cần thiết");
        exit();
    }
    
    $class_code = trim($_POST['class_code']);
    $class_name = trim($_POST['class_name']);

    // Validate dữ liệu
    if (empty($class_code) || empty($class_name)) {
        header("Location: ../views/class/create_class.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    // Gọi hàm thêm lớp
    $result = addClass($class_code, $class_name);

    if ($result) {
        header("Location: ../views/class.php?success=Thêm lớp thành công");
        exit();
    } else {
        header("Location: ../views/class/create_class.php?error=Thêm lớp thất bại");
    }
    exit();
}

/**
 * Xử lý chỉnh sửa lớp học
 */
function handleEditClass() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/class.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['class_code']) || !isset($_POST['class_name'])) {
        header("Location: ../views/class.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $id = $_POST['id'];
    $class_code = trim($_POST['class_code']);
    $class_name = trim($_POST['class_name']);

    // Validate dữ liệu
    if (empty($class_code) || empty($class_name)) {
        header("Location: ../views/class/edit_class.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    // Gọi function để cập nhật học phần
    $result = updateClass($id, $class_code, $class_name);

    if ($result) {
        header("Location: ../views/class.php?success=Cập nhật lớp thành công");
        exit();
    } else {
        header("Location: ../views/class/edit_class.php?id=" . $id . "&error=Cập nhật lớp thất bại");
        exit();
    }
}

/**
 * Xử lý xóa lớp học
 */
function handleDeleteClass() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/class.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/class.php?error=Không tìm thấy ID lớp học");
        exit();
    }

    $id = $_GET['id'];

    // Validate ID là số
    if (!is_numeric($id)) {
        header("Location: ../views/class.php?error=ID lớp học không hợp lệ");
        exit();
    }

    // Gọi function để xóa lớp học
    $result = deleteClass($id);

    if ($result) {
        header("Location: ../views/class.php?success=Xóa lớp thành công");
    } else {
        header("Location: ../views/class.php?error=Xóa lớp thất bại");
    }
    exit();
}
?>