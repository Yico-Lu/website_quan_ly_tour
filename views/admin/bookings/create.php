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

            <!-- Lịch khởi hành -->
            <div class="card mb-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Lịch khởi hành</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ngay_gio_xuat_phat" class="form-label">Ngày giờ xuất phát</label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="ngay_gio_xuat_phat"
                                name="ngay_gio_xuat_phat"
                                value="<?= htmlspecialchars($old['ngay_gio_xuat_phat'] ?? '') ?>"
                            />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="thoi_gian_ket_thuc" class="form-label">Thời gian kết thúc</label>
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="thoi_gian_ket_thuc"
                                name="thoi_gian_ket_thuc"
                                value="<?= htmlspecialchars($old['thoi_gian_ket_thuc'] ?? '') ?>"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="diem_tap_trung" class="form-label">Điểm tập trung</label>
                            <input
                                type="text"
                                class="form-control"
                                id="diem_tap_trung"
                                name="diem_tap_trung"
                                value="<?= htmlspecialchars($old['diem_tap_trung'] ?? '') ?>"
                                placeholder="Ví dụ: Sân bay Tân Sơn Nhất - Cổng D2"
                            />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lich_ghi_chu" class="form-label">Ghi chú lịch khởi hành</label>
                            <textarea
                                class="form-control"
                                id="lich_ghi_chu"
                                name="lich_ghi_chu"
                                rows="2"
                                placeholder="Ghi chú cho lịch khởi hành (nếu có)"
                            ><?= htmlspecialchars($old['lich_ghi_chu'] ?? '') ?></textarea>
                        </div>
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

            <!-- Dịch vụ booking -->
            <div class="card mb-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Dịch vụ booking</h5>
                </div>
                <div class="card-body" id="dich_vu_container">
                    <div class="row align-items-end g-3 mb-2 dich-vu-row">
                        <div class="col-md-5">
                            <label class="form-label">Tên dịch vụ</label>
                            <input type="text" class="form-control" name="dich_vu[0][ten_dich_vu]"
                                value="<?= htmlspecialchars($old['dich_vu'][0]['ten_dich_vu'] ?? '') ?>"
                                placeholder="Ví dụ: Đón sân bay, Ăn sáng, Phòng sớm">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Chi tiết</label>
                            <input type="text" class="form-control" name="dich_vu[0][chi_tiet]"
                                value="<?= htmlspecialchars($old['dich_vu'][0]['chi_tiet'] ?? '') ?>"
                                placeholder="Ghi chú thêm cho dịch vụ (tùy chọn)">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Để trống nếu chưa chốt dịch vụ kèm theo.</small>
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
    'extraJs' => ['js/auto-hide-alerts.js', 'js/booking-create.js'],
]);
?>
<script>
    // Truyền dữ liệu khách hàng cũ cho JS
    window.OLD_KHACH_DATA = <?= json_encode($old['khach'] ?? []) ?>;
    window.OLD_DICH_VU = <?= json_encode($old['dich_vu'] ?? []) ?>;
</script>

