/**
 * main.js - Tăng cường trải nghiệm người dùng (UX/UI)
 * Được thêm vào bởi Đối tác lập trình
 */

document.addEventListener("DOMContentLoaded", function () {
    
    // 1. HIỆU ỨNG FADE-IN KHI TẢI TRANG
    // Thêm class .fade-in vào thẻ body hoặc container chính
    const contentContainer = document.querySelector('.card') || document.querySelector('.container-fluid');
    if (contentContainer) {
        contentContainer.classList.add('animate-fade-in');
    }

    // 2. TỰ ĐỘNG BIẾN NÚT XÓA CÓ CONFIRM THÀNH SWEETALERT2 ĐẸP MẮT
    // Tìm tất cả thẻ <a> có chứa onclick="return confirm(...)"
    const confirmLinks = document.querySelectorAll('a[onclick*="return confirm"]');
    
    confirmLinks.forEach(link => {
        // Lưu lại đường dẫn xóa
        const deleteUrl = link.getAttribute('href');
        // Lấy nội dung thông báo từ trong chuỗi confirm('...')
        const originalOnclick = link.getAttribute('onclick');
        const messageMatch = originalOnclick.match(/confirm\('([^']+)'\)/);
        const message = messageMatch ? messageMatch[1] : 'Bạn có chắc chắn muốn thực hiện hành động này?';

        // Gỡ bỏ sự kiện onclick cũ để không hiện popup mặc định
        link.removeAttribute('onclick');

        // Thêm sự kiện click mới sử dụng SweetAlert2
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Ngăn chặn chuyển trang ngay lập tức

            Swal.fire({
                title: 'Xác nhận',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545', // Màu đỏ cho nút xóa
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Hủy bỏ',
                background: '#fff',
                borderRadius: '15px',
                customClass: {
                    popup: 'animated-popup' // Class tùy chỉnh nếu cần
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hiệu ứng loading trước khi chuyển trang
                    Swal.fire({
                        title: 'Đang xử lý...',
                        text: 'Vui lòng chờ trong giây lát',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    // Chuyển hướng đến link xóa
                    window.location.href = deleteUrl;
                }
            });
        });
    });

    // 3. HIỆU ỨNG LOADING CHO CÁC FORM
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                // Lưu nội dung cũ
                const originalText = btn.innerHTML;
                // Đổi nút thành trạng thái loading
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
                
                // (Phòng hờ) Nếu form không submit được sau 5s thì mở lại nút
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 5000);
            }
        });
    });
});