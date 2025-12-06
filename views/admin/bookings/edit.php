<?php
ob_start();
?>

<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa Booking</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>bookings/show/<?= $booking->id ?>" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Xem chi tiết
            </a>
            <a href="<?= BASE_URL ?>bookings" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="<?= BASE_URL ?>bookings/update" method="POST" id="bookingEditForm" novalidate>
        <input type="hidden" name="id" value="<?= $booking->id ?>">
        <div class="card-body">
            <!-- Hiển thị lỗi (chỉ hiển thị khi có lỗi từ validation sau khi submit) -->
            <?php if (isset($errors) && is_array($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>Có lỗi:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Tour -->
                <div class="col-md-6 mb-3">
                    <label for="tour_id" class="form-label">Tour <span class="text-danger">*</span></label>
                    <select class="form-select" id="tour_id" name="tour_id" data-required="true">
                        <option value="">Chọn tour</option>
                        <?php 
                        $selectedTourId = !empty($old['tour_id']) ? $old['tour_id'] : (!empty($booking->tour_id) ? $booking->tour_id : '');
                        foreach ($tourList as $tour): 
                        ?>
                            <option
                                value="<?= $tour['id'] ?>"
                                <?= $selectedTourId == $tour['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($tour['ten_tour']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- HDV phụ trách -->
                <div class="col-md-6 mb-3">
                    <label for="assigned_hdv_id" class="form-label">HDV phụ trách</label>
                    <select class="form-select" id="assigned_hdv_id" name="assigned_hdv_id">
                        <option value="">Chọn HDV (tùy chọn)</option>
                        <?php foreach ($guideList as $guide): ?>
                            <option
                                value="<?= $guide['id'] ?>"
                                <?= ($old['assigned_hdv_id'] ?? $booking->assigned_hdv_id) == $guide['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($guide['ho_ten']) ?> (<?= htmlspecialchars($guide['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Loại khách -->
                <div class="col-md-6 mb-3">
                    <label for="loai_khach" class="form-label">Loại khách <span class="text-danger">*</span></label>
                    <select class="form-select" id="loai_khach" name="loai_khach" required>
                        <option value="le" <?= ($old['loai_khach'] ?? $booking->loai_khach) == 'le' ? 'selected' : '' ?>>Khách lẻ</option>
                        <option value="doan" <?= ($old['loai_khach'] ?? $booking->loai_khach) == 'doan' ? 'selected' : '' ?>>Khách đoàn</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Tên người đặt -->
                <div class="col-md-6 mb-3">
                    <label for="ten_nguoi_dat" class="form-label">Tên người đặt <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control"
                        id="ten_nguoi_dat"
                        name="ten_nguoi_dat"
                        value="<?= htmlspecialchars(!empty($old['ten_nguoi_dat']) ? $old['ten_nguoi_dat'] : (!empty($booking->ten_nguoi_dat) ? $booking->ten_nguoi_dat : '')) ?>"
                        placeholder="Nhập tên người đặt"
                        data-required="true"
                    />
                </div>

                <!-- Số lượng -->
                <div class="col-md-6 mb-3">
                    <label for="so_luong" class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input
                        type="number"
                        class="form-control"
                        id="so_luong"
                        name="so_luong"
                        value="<?= htmlspecialchars($old['so_luong'] ?? $booking->so_luong) ?>"
                        placeholder="Nhập số lượng người"
                        min="1"
                        data-required="true"
                    />
                </div>
            </div>

            <div class="row">
                <!-- Thời gian tour -->
                <div class="col-md-6 mb-3">
                    <label for="thoi_gian_tour" class="form-label">Thời gian tour <span class="text-danger">*</span></label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="thoi_gian_tour"
                        name="thoi_gian_tour"
                        value="<?= !empty($old['thoi_gian_tour']) ? htmlspecialchars($old['thoi_gian_tour']) : (!empty($booking->thoi_gian_tour) ? date('Y-m-d\TH:i', strtotime($booking->thoi_gian_tour)) : '') ?>"
                        data-required="true"
                    />
                </div>

                <!-- Liên hệ -->
                <div class="col-md-6 mb-3">
                    <label for="lien_he" class="form-label">Liên hệ <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control"
                        id="lien_he"
                        name="lien_he"
                        value="<?= htmlspecialchars(!empty($old['lien_he']) ? $old['lien_he'] : (!empty($booking->lien_he) ? $booking->lien_he : '')) ?>"
                        placeholder="Số điện thoại hoặc email"
                        data-required="true"
                    />
                </div>
            </div>

            <!-- Yêu cầu đặc biệt -->
            <div class="mb-3">
                <label for="yeu_cau_dac_biet" class="form-label">Yêu cầu đặc biệt</label>
                <textarea
                    class="form-control"
                    id="yeu_cau_dac_biet"
                    name="yeu_cau_dac_biet"
                    rows="3"
                    placeholder="Nhập yêu cầu đặc biệt (nếu có)"
                ><?= htmlspecialchars($old['yeu_cau_dac_biet'] ?? $booking->yeu_cau_dac_biet) ?></textarea>
            </div>

            <!-- Ghi chú -->
            <div class="mb-3">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea
                    class="form-control"
                    id="ghi_chu"
                    name="ghi_chu"
                    rows="2"
                    placeholder="Nhập ghi chú (nếu có)"
                ><?= htmlspecialchars($old['ghi_chu'] ?? $booking->ghi_chu) ?></textarea>
            </div>

            <!-- Trạng thái -->
            <div class="mb-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select class="form-select" id="trang_thai" name="trang_thai">
                    <option value="cho_xac_nhan" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="da_coc" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'da_coc' ? 'selected' : '' ?>>Đã cọc</option>
                    <option value="da_thanh_toan" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'da_thanh_toan' ? 'selected' : '' ?>>Đã thanh toán</option>
                    <option value="da_huy" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
                    <option value="hoan_thanh" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'hoan_thanh' ? 'selected' : '' ?>>Hoàn thành</option>
                </select>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-circle"></i> Cập nhật Booking
            </button>
            <a href="<?= BASE_URL ?>bookings" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

// Hiển thị layout admin với nội dung
view('layouts.AdminLayout', [
    'title' => $title ?? 'Chỉnh sửa Booking - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Chỉnh sửa Booking',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>

<script>
// Validate form bằng JavaScript thay vì HTML5 validation
document.addEventListener('DOMContentLoaded', function() {
    // Validate tất cả các form trong trang
    const allForms = document.querySelectorAll('form[novalidate]');
    allForms.forEach(function(form) {
        validateForm(form);
    });
    
    function validateForm(form) {
        form.addEventListener('submit', function(e) {
            // Chỉ validate form này, không validate form khác
            e.stopPropagation();
            
            let isValid = true;
            // Chỉ lấy các trường required trong form này, không lấy từ form khác
            const requiredFields = Array.from(form.querySelectorAll('[data-required="true"]')).filter(function(field) {
                // Đảm bảo field thuộc về form này
                return form.contains(field);
            });
            
            // Xóa các lỗi cũ trong form này
            form.querySelectorAll('.is-invalid').forEach(function(field) {
                field.classList.remove('is-invalid');
            });
            form.querySelectorAll('.invalid-feedback').forEach(function(feedback) {
                feedback.remove();
            });
            
            // Xóa lỗi trong form chính nếu đang submit form con
            if (form.id !== 'bookingEditForm') {
                const mainForm = document.getElementById('bookingEditForm');
                if (mainForm) {
                    mainForm.querySelectorAll('.is-invalid').forEach(function(field) {
                        field.classList.remove('is-invalid');
                    });
                    mainForm.querySelectorAll('.invalid-feedback').forEach(function(feedback) {
                        feedback.remove();
                    });
                    // Xóa cả alert lỗi nếu có
                    const errorAlert = mainForm.querySelector('.alert-danger');
                    if (errorAlert) {
                        errorAlert.remove();
                    }
                }
            }
            
            // Kiểm tra từng trường required trong form này
            requiredFields.forEach(function(field) {
                // Đảm bảo field thuộc về form này
                if (!form.contains(field)) {
                    return;
                }
                const value = field.value.trim();
                if (!value) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Tạo message lỗi
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    
                    // Lấy label tương ứng trong form này
                    const label = form.querySelector('label[for="' + field.id + '"]');
                    let labelText = 'Trường này';
                    if (label) {
                        labelText = label.textContent.replace(/\*/g, '').trim();
                    }
                    
                    // Tùy chỉnh message theo từng trường
                    if (field.id === 'tour_id') {
                        feedback.textContent = 'Vui lòng chọn tour';
                    } else if (field.id === 'ten_nguoi_dat') {
                        feedback.textContent = 'Vui lòng nhập tên người đặt';
                    } else if (field.id === 'thoi_gian_tour') {
                        feedback.textContent = 'Vui lòng chọn thời gian tour';
                    } else if (field.id === 'lien_he') {
                        feedback.textContent = 'Vui lòng nhập thông tin liên hệ';
                    } else if (field.id === 'ten_dich_vu' || field.name === 'ten_dich_vu') {
                        feedback.textContent = 'Vui lòng nhập tên dịch vụ';
                    } else if (field.id === 'hdv_id' || field.name === 'hdv_id') {
                        feedback.textContent = 'Vui lòng chọn hướng dẫn viên';
                    } else if (field.name === 'ho_ten') {
                        feedback.textContent = 'Vui lòng nhập họ tên';
                    } else if (field.id === 'excel_file') {
                        feedback.textContent = 'Vui lòng chọn file Excel/CSV';
                    } else {
                        feedback.textContent = 'Vui lòng nhập ' + labelText.toLowerCase();
                    }
                    
                    field.parentNode.appendChild(feedback);
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                e.stopImmediatePropagation();
                return false;
            }
            // Nếu hợp lệ, cho phép submit tiếp tục - không preventDefault
        }, false); // Sử dụng bubble phase
    }
});
</script>
