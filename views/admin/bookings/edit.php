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

    <form action="<?= BASE_URL ?>bookings/update" method="POST" id="bookingEditForm" enctype="multipart/form-data" novalidate>
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
                                data-gia="<?= htmlspecialchars($tour['gia']) ?>"
                                data-ngay-xuat-phat="<?= htmlspecialchars($tour['ngay_xuat_phat'] ?? '') ?>"
                                data-ngay-ket-thuc="<?= htmlspecialchars($tour['ngay_ket_thuc'] ?? '') ?>"
                                <?= $selectedTourId == $tour['id'] ? 'selected' : '' ?>
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
                                <?php 
                                $selectedHdvId = $old['hdv_id'] ?? ($currentHdv['hdv_id'] ?? '');
                                foreach ($guideList as $guide): 
                                ?>
                            <option
                                value="<?= $guide['id'] ?>"
                                        <?= $selectedHdvId == $guide['id'] ? 'selected' : '' ?>
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
                                <option value="hdv" <?= ($old['vai_tro'] ?? ($currentHdv['vai_tro'] ?? 'hdv')) == 'hdv' ? 'selected' : '' ?>>HDV</option>
                                <option value="hdv_chinh" <?= ($old['vai_tro'] ?? ($currentHdv['vai_tro'] ?? '')) == 'hdv_chinh' ? 'selected' : '' ?>>HDV chính</option>
                                <option value="hdv_phu" <?= ($old['vai_tro'] ?? ($currentHdv['vai_tro'] ?? '')) == 'hdv_phu' ? 'selected' : '' ?>>HDV phụ</option>
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
                        ><?= htmlspecialchars($old['chi_tiet'] ?? ($currentHdv['chi_tiet'] ?? '')) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Điểm danh khách -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Điểm danh khách</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($diemDanh)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 140px;">Booking Khách ID</th>
                                        <th style="width: 260px;">Trạng thái</th>
                                        <th>Ghi chú</th>
                                        <th style="width: 140px;">Ngày giờ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($diemDanh as $row): ?>
                                        <?php
                                        $status = $row['trang_thai'] ?? '';
                                        $lkhIdRow = $row['lich_khoi_hanh_id'] ?? ($booking->lich_khoi_hanh_id ?? '');
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="attendance[<?= $row['booking_khach_id'] ?>][booking_khach_id]" value="<?= htmlspecialchars($row['booking_khach_id']) ?>">
                                                <input type="hidden" name="attendance[<?= $row['booking_khach_id'] ?>][lich_khoi_hanh_id]" value="<?= htmlspecialchars($lkhIdRow) ?>">
                                                <?= htmlspecialchars($row['booking_khach_id']) ?>
                                            </td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="att_<?= $row['booking_khach_id'] ?>_den" name="attendance[<?= $row['booking_khach_id'] ?>][trang_thai]" value="da_den" <?= $status === 'da_den' ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="att_<?= $row['booking_khach_id'] ?>_den">Đã đến</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="att_<?= $row['booking_khach_id'] ?>_tre" name="attendance[<?= $row['booking_khach_id'] ?>][trang_thai]" value="tre" <?= $status === 'tre' ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="att_<?= $row['booking_khach_id'] ?>_tre">Trễ</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" id="att_<?= $row['booking_khach_id'] ?>_vang" name="attendance[<?= $row['booking_khach_id'] ?>][trang_thai]" value="vang" <?= ($status === 'vang' || $status === 'vang_mat') ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="att_<?= $row['booking_khach_id'] ?>_vang">Vắng mặt</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="attendance[<?= $row['booking_khach_id'] ?>][ghi_chu]" value="<?= htmlspecialchars($row['ghi_chu'] ?? '') ?>" placeholder="Ghi chú (tùy chọn)">
                                            </td>
                                            <td><?= !empty($row['ngay_gio']) ? date('d/m/Y H:i', strtotime($row['ngay_gio'])) : '<span class="text-muted">-</span>' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <span class="text-muted">Chưa có dữ liệu điểm danh</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ngay_gio_xuat_phat" class="form-label">Giờ xuất phát</label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="ngay_gio_xuat_phat"
                        name="ngay_gio_xuat_phat"
                        value="<?= $booking->ngay_gio_xuat_phat ? date('Y-m-d\TH:i', strtotime($booking->ngay_gio_xuat_phat)) : '' ?>"
                    />
                </div>
                <div class="col-md-6 mb-3">
                    <label for="thoi_gian_ket_thuc" class="form-label">Thời gian kết thúc</label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="thoi_gian_ket_thuc"
                        name="thoi_gian_ket_thuc"
                        value="<?= $booking->thoi_gian_ket_thuc ? date('Y-m-d\TH:i', strtotime($booking->thoi_gian_ket_thuc)) : '' ?>"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="diem_tap_trung" class="form-label">Điểm tập trung</label>
                    <input
                        type="text"
                        class="form-control"
                        id="diem_tap_trung"
                        name="diem_tap_trung"
                        value="<?= htmlspecialchars($booking->diem_tap_trung ?? '') ?>"
                        placeholder="Nhập điểm tập trung (nếu có)"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="lich_ghi_chu" class="form-label">Ghi chú lịch khởi hành</label>
                    <textarea
                        class="form-control"
                        id="lich_ghi_chu"
                        name="lich_ghi_chu"
                        rows="2"
                        placeholder="Nhập ghi chú cho lịch khởi hành (nếu có)"
                    ><?= htmlspecialchars($old['lich_ghi_chu'] ?? ($booking->lich_ghi_chu ?? '')) ?></textarea>
                </div>
            </div>

            <div class="row">
                <!-- Loại khách -->
                <div class="col-md-6 mb-3">
                    <label for="loai_khach" class="form-label">Loại khách <span class="text-danger">*</span></label>
                    <select class="form-select" id="loai_khach" name="loai_khach" required>
                        <option value="le" <?= ($old['loai_khach'] ?? $booking->loai_khach) == 'le' ? 'selected' : '' ?>>Khách lẻ</option>
                        <option value="doan" <?= ($old['loai_khach'] ?? $booking->loai_khach) == 'doan' ? 'selected' : '' ?>>Khách đoàn</option>
                    </select>
                </div>
            </div>

            <!-- Thông tin người đại diện -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Thông tin người đại diện</h5>
                </div>
                <div class="card-body">
                    <input
                        type="hidden"
                        id="khach_data_edit"
                        data-current-khachs="<?= htmlspecialchars(json_encode($currentKhachs ?? []), ENT_QUOTES, 'UTF-8') ?>"
                        data-old-khachs="<?= htmlspecialchars(json_encode($old['khach'] ?? []), ENT_QUOTES, 'UTF-8') ?>"
                    >
                    <div id="khach_container">
                        <!-- Form khách hàng sẽ được thêm bằng JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Upload file danh sách khách hàng -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">File danh sách khách hàng</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Kiểm tra xem có file danh sách khách hàng không
                    $uploadDir = BASE_PATH . '/public/uploads/guest_lists/';
                    $filePattern = $uploadDir . 'booking_' . $booking->id . '.*';
                    $existingFiles = glob($filePattern);
                    $hasFile = !empty($existingFiles) && is_file($existingFiles[0]);
                    $filePath = $hasFile ? $existingFiles[0] : null;
                    $fileName = $hasFile ? basename($filePath) : null;
                    ?>
                    
                    <?php if ($hasFile && $fileName): ?>
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-file-earmark-excel"></i>
                            <strong>File hiện tại:</strong> <?= htmlspecialchars($fileName) ?>
                            <br>
                            <a href="<?= BASE_URL ?>public/uploads/guest_lists/<?= htmlspecialchars($fileName) ?>" 
                               class="btn btn-sm btn-primary mt-2" download>
                                <i class="bi bi-download"></i> Tải danh sách khách hàng
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle"></i>
                            Chưa có file danh sách khách hàng cho booking này.
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="guest_list_file" class="form-label">
                            <?= $hasFile ? 'Thay thế file (tùy chọn)' : 'Chọn file Excel/CSV (tùy chọn)' ?>
                        </label>
                    <input
                            type="file"
                        class="form-control"
                            id="guest_list_file"
                            name="guest_list_file"
                            accept=".xlsx,.xls,.csv"
                        />
                        <div class="form-text">
                            Định dạng file: XLSX, XLS, CSV. File sẽ được lưu với tên: <strong>booking_<?= $booking->id ?>.extension</strong>
                            <?php if ($hasFile): ?>
                                <br>
                                <small class="text-warning">Nếu chọn file mới, file cũ sẽ bị thay thế.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </div>

            <div class="row">
                <!-- Tên người đặt (ẩn, sẽ tự động lấy từ khách hàng đầu tiên) -->
                <input type="hidden" id="ten_nguoi_dat" name="ten_nguoi_dat" value="<?= htmlspecialchars(!empty($old['ten_nguoi_dat']) ? $old['ten_nguoi_dat'] : (!empty($booking->ten_nguoi_dat) ? $booking->ten_nguoi_dat : '')) ?>">

                <!-- Số lượng -->
                <div class="col-md-6 mb-3">
                    <label for="so_luong" class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input
                        type="number"
                        class="form-control"
                        id="so_luong"
                        name="so_luong"
                        value="<?= htmlspecialchars(!empty($old['so_luong']) ? $old['so_luong'] : (!empty($booking->so_luong) ? $booking->so_luong : '1')) ?>"
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
    'extraJs' => [
        'js/auto-hide-alerts.js',
        'js/booking-edit.js'
    ],
]);
?>
