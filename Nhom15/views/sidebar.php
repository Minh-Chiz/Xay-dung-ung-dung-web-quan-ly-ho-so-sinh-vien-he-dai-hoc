<?php
// Sử dụng __DIR__ để tính toán đường dẫn chính xác
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
$currentUser = getLoggedInUser();

// Lấy tên tệp hiện tại để active link
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    
    <title><?php echo $pageTitle ?? 'Quản lý Sinh viên'; ?></title>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="../images/fitdnu_logo.png" alt="FIT-DNU Logo" />
                <h5>Quản lý Hồ sơ</h5>
            </div>

            <ul class="list-unstyled components">
                <li class="<?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
                    <a href="dashboard.php"><i class="bi bi-grid-fill me-2"></i>Dashboard</a>
                </li>
                <li class="<?php echo ($currentPage == 'student.php') ? 'active' : ''; ?>">
                    <a href="student.php"><i class="bi bi-people-fill me-2"></i>Quản lý sinh viên</a>
                </li>
                <li class="<?php echo ($currentPage == 'subject.php') ? 'active' : ''; ?>">
                    <a href="subject.php"><i class="bi bi-book-fill me-2"></i>Quản lý học phần</a>
                </li>
                <li class="<?php echo ($currentPage == 'grade.php') ? 'active' : ''; ?>">
                    <a href="grade.php"><i class="bi bi-pencil-square me-2"></i>Quản lý điểm</a>
                </li>
                <li class="<?php echo ($currentPage == 'class.php') ? 'active' : ''; ?>">
                    <a href="class.php"><i class="bi bi-collection-fill me-2"></i>Quản lý lớp học</a>
                </li>
            </ul>

            <div class="user-info">
                 <?php if ($currentUser): ?>
                    <span>
                        Chào, <strong><?= htmlspecialchars($currentUser['username']) ?></strong>
                        (<?= htmlspecialchars($currentUser['role']) ?>)
                    </span>
                <?php endif; ?>
                <a href="../handle/logout_process.php" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>
        </nav>

        <div id="content">
            ```

---

### Bước 3: Tạo tệp `layout_footer.php` mới

Chúng ta cần một tệp để "đóng" các thẻ HTML đã mở trong `sidebar.php`.

**Tệp mới: `Nhom15/views/layout_footer.php`**
```php
        </div>
    </div>

    <footer class="footer">
        Copyright © 2025 - Nhóm 15 - FITDNU
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Script ẩn thông báo (giữ nguyên)
    setTimeout(() => {
        let alertNode = document.querySelector('.alert');
        // Kiểm tra xem bootstrap có sẵn chưa
        if (typeof bootstrap !== 'undefined' && alertNode) {
            let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
            bsAlert.close();
        }
    }, 3000);
    </script>
</body>
</html>