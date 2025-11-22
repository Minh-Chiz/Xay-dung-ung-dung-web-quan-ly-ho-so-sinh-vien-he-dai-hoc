</div> </div> <footer class="footer">
        Copyright © 2025 - Nhóm 15 - FITDNU
    </footer>

    <script src="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js)"></script>
    
    <script>
    // Script tự động ẩn thông báo
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            let alertNode = document.querySelector('.alert-dismissible');
            // Kiểm tra xem bootstrap có sẵn chưa
            if (typeof bootstrap !== 'undefined' && alertNode) {
                // Sử dụng new bootstrap.Alert thay vì getOrCreateInstance để đảm bảo nó hoạt động
                let bsAlert = new bootstrap.Alert(alertNode);
                if (bsAlert) {
                    bsAlert.close();
                }
            }
        }, 3000); // 3 giây
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>