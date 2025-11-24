</div> </div> 
    
    <footer class="footer">
        Copyright © 2025 - Nhóm 15 - FITDNU
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
        // Tính toán đường dẫn JS dựa trên độ sâu thư mục (giống sidebar.php)
        $jsPathPrefix = (strpos($_SERVER['PHP_SELF'], '/views/student/') !== false ||
                    strpos($_SERVER['PHP_SELF'], '/views/class/') !== false ||
                    strpos($_SERVER['PHP_SELF'], '/views/subject/') !== false ||
                    strpos($_SERVER['PHP_SELF'], '/views/grade/') !== false ||
                    strpos($_SERVER['PHP_SELF'], '/views/user/') !== false) ? '../../' : '../';
    ?>
    <script src="<?php echo $jsPathPrefix; ?>assets/js/main.js"></script>

    <script>
    // Script tự động ẩn thông báo
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            let alertNode = document.querySelector('.alert-dismissible');
            if (typeof bootstrap !== 'undefined' && alertNode) {
                let bsAlert = new bootstrap.Alert(alertNode);
                bsAlert.close();
            }
        }, 4000); 
    });
    </script>
</body>
</html>