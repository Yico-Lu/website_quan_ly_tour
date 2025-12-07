<?php
ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Thêm Booking Mới</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>bookings" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="<?= BASE_URL ?>bookings/store" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            <!-- Hiển thị lỗi -->
            <?php if (isset($errors) && !empty($errors)): ?>
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
                    <select class="form-select" id="tour_id" name="tour_id" required>
                        <option value="">Chọn tour</option>
                        <?php foreach ($tourList as $tour): ?>
                            <option
                                value="<?= $tour['id'] ?>"
                                <?= ($old['tour_id'] ?? '') == $tour['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($tour['ten_tour']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- HDV phụ trách -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Hướng dẫn viên phụ trách</h5>
                </div>
                <div class="card-body">
            <div class="row">
                        <!-- HDV -->
                <div class="col-md-6 mb-3">
                            <label for="hdv_id" class="form-label">HDV</label>
                            <select class="form-select" id="hdv_id" name="hdv_id">
                        <option value="">Chọn HDV (tùy chọn)</option>
                        <?php foreach ($guideList as $guide): ?>
                            <option
                                value="<?= $guide['id'] ?>"
                                        <?= ($old['hdv_id'] ?? '') == $guide['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($guide['ho_ten']) ?> (<?= htmlspecialchars($guide['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                        <!-- Vai trò -->
                        <div class="col-md-6 mb-3">
                            <label for="vai_tro" class="form-label">Vai trò</label>
                            <select class="form-select" id="vai_tro" name="vai_tro">
                                <option value="hdv" <?= ($old['vai_tro'] ?? 'hdv') == 'hdv' ? 'selected' : '' ?>>HDV</option>
                                <option value="hdv_chinh" <?= ($old['vai_tro'] ?? '') == 'hdv_chinh' ? 'selected' : '' ?>>HDV chính</option>
                                <option value="hdv_phu" <?= ($old['vai_tro'] ?? '') == 'hdv_phu' ? 'selected' : '' ?>>HDV phụ</option>
                            </select>
                        </div>
                    </div>

                    <!-- Chi tiết -->
                    <div class="mb-3">
                        <label for="chi_tiet" class="form-label">Chi tiết</label>
                        <textarea
                            class="form-control"
                            id="chi_tiet"
                            name="chi_tiet"
                            rows="2"
                            placeholder="Nhập chi tiết về vai trò của HDV (nếu có)"
                        ><?= htmlspecialchars($old['chi_tiet'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Loại khách -->
                <div class="col-md-6 mb-3">
                    <label for="loai_khach" class="form-label">Loại khách <span class="text-danger">*</span></label>
                    <select class="form-select" id="loai_khach" name="loai_khach" required>
                        <option value="le" <?= ($old['loai_khach'] ?? 'le') == 'le' ? 'selected' : '' ?>>Khách lẻ</option>
                        <option value="doan" <?= ($old['loai_khach'] ?? '') == 'doan' ? 'selected' : '' ?>>Khách đoàn</option>
                    </select>
                </div>
            </div>

            <!-- Thông tin người đại diện -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Thông tin người đại diện</h5>
                </div>
                <div class="card-body">
                    <div id="khach_container">
                        <!-- Chỉ có 1 khách hàng (người đại diện) -->
                    </div>
                </div>
            </div>

            <!-- Upload file danh sách khách hàng -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">File danh sách khách hàng (tùy chọn)</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="guest_list_file" class="form-label">Chọn file Excel/CSV</label>
                    <input
                            type="file"
                        class="form-control"
                            id="guest_list_file"
                            name="guest_list_file"
                            accept=".xlsx,.xls,.csv"
                        />
                        <div class="form-text">
                            Định dạng file: XLSX, XLS, CSV. File sẽ được lưu với tên: <strong>booking_{id}.extension</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Tên người đặt (ẩn, sẽ tự động lấy từ khách hàng đầu tiên) -->
                <input type="hidden" id="ten_nguoi_dat" name="ten_nguoi_dat" value="<?= htmlspecialchars($old['ten_nguoi_dat'] ?? '') ?>">

                <!-- Số lượng -->
                <div class="col-md-6 mb-3">
                    <label for="so_luong" class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input
                        type="number"
                        class="form-control"
                        id="so_luong"
                        name="so_luong"
                        value="<?= htmlspecialchars($old['so_luong'] ?? '1') ?>"
                        placeholder="Nhập số lượng người"
                        min="1"
                        required
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
                        value="<?= htmlspecialchars($old['thoi_gian_tour'] ?? '') ?>"
                        required
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
                        value="<?= htmlspecialchars($old['lien_he'] ?? '') ?>"
                        placeholder="Số điện thoại hoặc email"
                        required
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
                ><?= htmlspecialchars($old['yeu_cau_dac_biet'] ?? '') ?></textarea>
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
                ><?= htmlspecialchars($old['ghi_chu'] ?? '') ?></textarea>
            </div>

            <!-- Trạng thái -->
            <div class="mb-3">
                <label for="trang_thai" class="form-label">Trạng thái</label>
                <select class="form-select" id="trang_thai" name="trang_thai">
                    <option value="cho_xac_nhan" <?= ($old['trang_thai'] ?? 'cho_xac_nhan') == 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="da_coc" <?= ($old['trang_thai'] ?? '') == 'da_coc' ? 'selected' : '' ?>>Đã cọc</option>
                    <option value="da_thanh_toan" <?= ($old['trang_thai'] ?? '') == 'da_thanh_toan' ? 'selected' : '' ?>>Đã thanh toán</option>
                    <option value="da_huy" <?= ($old['trang_thai'] ?? '') == 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
                    <option value="hoan_thanh" <?= ($old['trang_thai'] ?? '') == 'hoan_thanh' ? 'selected' : '' ?>>Hoàn thành</option>
                </select>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Tạo Booking
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
    'title' => $title ?? 'Thêm Booking Mới - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Thêm Booking Mới',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const khachContainer = document.getElementById('khach_container');
    const addKhachBtn = document.getElementById('addKhachBtn');
    let khachIndex = 0;
    
    // Dữ liệu cũ từ server (nếu có lỗi validation)
    const oldKhachs = <?= json_encode($old['khach'] ?? []) ?>;
    
    // Tạo form khách hàng
    function createKhachForm(index, data = {}) {
        const card = document.createElement('div');
        card.className = 'card mb-3 khach-card';
        card.innerHTML = `
            <div class="card-header bg-light">
                <strong><span class="badge bg-primary me-2">Người đại diện</span>Thông tin người đại diện</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="khach[${index}][ho_ten]" 
                               value="${escapeHtml(data.ho_ten || '')}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giới tính</label>
                        <select class="form-select" name="khach[${index}][gioi_tinh]">
                            <option value="">-- Chọn --</option>
                            <option value="nam" ${(data.gioi_tinh || '') === 'nam' ? 'selected' : ''}>Nam</option>
                            <option value="nu" ${(data.gioi_tinh || '') === 'nu' ? 'selected' : ''}>Nữ</option>
                            <option value="khac" ${(data.gioi_tinh || '') === 'khac' ? 'selected' : ''}>Khác</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Năm sinh</label>
                        <input type="number" class="form-control" name="khach[${index}][nam_sinh]" 
                               value="${escapeHtml(data.nam_sinh || '')}" min="1900" max="2100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số giấy tờ</label>
                        <input type="text" class="form-control" name="khach[${index}][so_giay_to]" 
                               value="${escapeHtml(data.so_giay_to || '')}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tình trạng thanh toán</label>
                        <select class="form-select" name="khach[${index}][tinh_trang_thanh_toan]">
                            <option value="chua_thanh_toan" ${(data.tinh_trang_thanh_toan || 'chua_thanh_toan') === 'chua_thanh_toan' ? 'selected' : ''}>Chưa thanh toán</option>
                            <option value="da_coc" ${(data.tinh_trang_thanh_toan || '') === 'da_coc' ? 'selected' : ''}>Đã cọc</option>
                            <option value="da_thanh_toan" ${(data.tinh_trang_thanh_toan || '') === 'da_thanh_toan' ? 'selected' : ''}>Đã thanh toán</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Yêu cầu cá nhân</label>
                    <textarea class="form-control" name="khach[${index}][yeu_cau_ca_nhan]" rows="2" 
                              placeholder="Nhập yêu cầu cá nhân (nếu có)">${escapeHtml(data.yeu_cau_ca_nhan || '')}</textarea>
                </div>
            </div>
        `;
        return card;
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Thêm form khách hàng
    function addKhachForm(data = {}) {
        const form = createKhachForm(khachIndex, data);
        khachContainer.appendChild(form);
        khachIndex++;
        // Không cần updateRemoveButtons vì chỉ có 1 người đại diện, không có nút xóa
    }
    
    // Không cho phép xóa hoặc thêm khách hàng - chỉ có 1 người đại diện
    // Khởi tạo form khách hàng đầu tiên (người đại diện) nếu có dữ liệu cũ
    if (oldKhachs.length > 0) {
        // Chỉ lấy khách hàng đầu tiên
        addKhachForm(oldKhachs[0]);
    } else {
        addKhachForm(); // Thêm form trống đầu tiên (người đại diện)
    }
    
    // Tự động cập nhật tên người đặt từ khách hàng đầu tiên
    function updateTenNguoiDat() {
        const firstKhachCard = khachContainer.querySelector('.khach-card');
        if (firstKhachCard) {
            const firstHoTenInput = firstKhachCard.querySelector('input[name*="[ho_ten]"]');
            const tenNguoiDatInput = document.getElementById('ten_nguoi_dat');
            if (firstHoTenInput && tenNguoiDatInput) {
                tenNguoiDatInput.value = firstHoTenInput.value;
            }
        }
    }
    
    khachContainer.addEventListener('input', (e) => {
        if (e.target.name && e.target.name.includes('[ho_ten]')) {
            const firstKhachCard = khachContainer.querySelector('.khach-card');
            if (firstKhachCard && e.target.closest('.khach-card') === firstKhachCard) {
                updateTenNguoiDat();
            }
        }
    });
    
    // Cập nhật tên người đặt khi form submit và debug
    const bookingForm = document.querySelector('form[action*="bookings"]');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            updateTenNguoiDat();
            
            // Debug: Kiểm tra tất cả các khách hàng trước khi submit
            const allKhachInputs = khachContainer.querySelectorAll('input[name*="[ho_ten]"]');
            console.log('Total khach forms before submit:', allKhachInputs.length);
            allKhachInputs.forEach((input, idx) => {
                console.log(`Khach ${idx}: name="${input.name}", value="${input.value}"`);
            });
        });
    }
});
</script>
