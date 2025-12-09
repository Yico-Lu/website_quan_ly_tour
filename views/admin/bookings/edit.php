<?php
ob_start();
?>

<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">Sửa Booking</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>bookings/show/<?= $booking->id ?>" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Xem chi tiết
            </a>
            <a href="<?= BASE_URL ?>bookings" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="<?= BASE_URL ?>bookings/update" method="POST">
        <input type="hidden" name="id" value="<?= $booking->id ?>" />
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
                <!-- Tên người đặt -->
                <div class="col-md-6 mb-3">
                    <label for="ten_nguoi_dat" class="form-label">Tên người đặt <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control"
                        id="ten_nguoi_dat"
                        name="ten_nguoi_dat"
                        value="<?= htmlspecialchars($old['ten_nguoi_dat'] ?? $booking->ten_nguoi_dat) ?>"
                        placeholder="Nhập tên người đặt tour"
                        required
                    />
                </div>

                <!-- Thông tin liên hệ -->
                <div class="col-md-6 mb-3">
                    <label for="lien_he" class="form-label">Thông tin liên hệ <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control"
                        id="lien_he"
                        name="lien_he"
                        value="<?= htmlspecialchars($old['lien_he'] ?? $booking->lien_he) ?>"
                        placeholder="Số điện thoại hoặc email"
                        required
                    />
                </div>
            </div>

            <div class="row">
                <!-- Tour -->
                <div class="col-md-6 mb-3">
                    <label for="tour_id" class="form-label">Tour <span class="text-danger">*</span></label>
                    <select class="form-select" id="tour_id" name="tour_id" required>
                        <option value="">Chọn tour</option>
                        <?php
                        $selectedTour = $old['tour_id'] ?? $booking->tour_id;
                        foreach ($tourList as $tour):
                        ?>
                            <option
                                value="<?= $tour['id'] ?>"
                                <?= $selectedTour == $tour['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($tour['ten_tour']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Hướng dẫn viên -->
                <div class="col-md-6 mb-3">
                    <label for="assigned_hdv_id" class="form-label">Hướng dẫn viên</label>
                    <select class="form-select" id="assigned_hdv_id" name="assigned_hdv_id">
                        <option value="">Chọn hướng dẫn viên (tùy chọn)</option>
                        <?php
                        $selectedGuide = $old['assigned_hdv_id'] ?? $booking->assigned_hdv_id;
                        foreach ($guideList as $guide):
                        ?>
                            <option
                                value="<?= $guide['id'] ?>"
                                <?= $selectedGuide == $guide['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($guide['ho_ten']) ?> (<?= htmlspecialchars($guide['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Loại khách -->
                <div class="col-md-4 mb-3">
                    <label for="loai_khach" class="form-label">Loại khách</label>
                    <select class="form-select" id="loai_khach" name="loai_khach">
                        <option value="le" <?= ($old['loai_khach'] ?? $booking->loai_khach) == 'le' ? 'selected' : '' ?>>Khách lẻ</option>
                        <option value="doan" <?= ($old['loai_khach'] ?? $booking->loai_khach) == 'doan' ? 'selected' : '' ?>>Khách đoàn</option>
                    </select>
                </div>

                <!-- Số lượng -->
                <div class="col-md-4 mb-3">
                    <label for="so_luong" class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input
                        type="number"
                        class="form-control"
                        id="so_luong"
                        name="so_luong"
                        value="<?= htmlspecialchars($old['so_luong'] ?? $booking->so_luong) ?>"
                        min="1"
                        required
                    />
                </div>

                <!-- Thời gian tour -->
                <div class="col-md-4 mb-3">
                    <label for="thoi_gian_tour" class="form-label">Thời gian tour <span class="text-danger">*</span></label>
                    <input
                        type="datetime-local"
                        class="form-control"
                        id="thoi_gian_tour"
                        name="thoi_gian_tour"
                        value="<?= htmlspecialchars($old['thoi_gian_tour'] ?? date('Y-m-d\TH:i', strtotime($booking->thoi_gian_tour))) ?>"
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
                    placeholder="Nhập yêu cầu đặc biệt nếu có..."
                ><?= htmlspecialchars($old['yeu_cau_dac_biet'] ?? $booking->yeu_cau_dac_biet) ?></textarea>
            </div>

            <div class="row">
                <!-- Trạng thái -->
                <div class="col-md-6 mb-3">
                    <label for="trang_thai" class="form-label">Trạng thái</label>
                    <select class="form-select" id="trang_thai" name="trang_thai">
                        <option value="cho_xac_nhan" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="da_xac_nhan" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'da_xac_nhan' ? 'selected' : '' ?>>Đã xác nhận</option>
                        <option value="da_coc" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'da_coc' ? 'selected' : '' ?>>Đã cọc</option>
                        <option value="da_thanh_toan" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'da_thanh_toan' ? 'selected' : '' ?>>Đã thanh toán</option>
                        <option value="dang_xu_ly" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'dang_xu_ly' ? 'selected' : '' ?>>Đang xử lý</option>
                        <option value="hoan_thanh" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'hoan_thanh' ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="da_huy" <?= ($old['trang_thai'] ?? $booking->trang_thai) == 'da_huy' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>

                <!-- Ghi chú -->
                <div class="col-md-6 mb-3">
                    <label for="ghi_chu" class="form-label">Ghi chú</label>
                    <textarea
                        class="form-control"
                        id="ghi_chu"
                        name="ghi_chu"
                        rows="2"
                        placeholder="Ghi chú nội bộ..."
                    ><?= htmlspecialchars($old['ghi_chu'] ?? $booking->ghi_chu) ?></textarea>
                </div>
            </div>

            <!-- Thông tin hệ thống -->
            <div class="row">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6><i class="bi bi-info-circle"></i> Thông tin hệ thống</h6>
                        <small class="text-muted">
                            <strong>Ngày tạo:</strong> <?= date('d/m/Y H:i', strtotime($booking->ngay_tao)) ?><br>
                            <strong>Ngày cập nhật:</strong> <?= date('d/m/Y H:i', strtotime($booking->ngay_cap_nhat)) ?>
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6><i class="bi bi-bar-chart"></i> Thống kê</h6>
                        <small class="text-muted">
                            <strong>Tour:</strong> <?= htmlspecialchars($booking->ten_tour ?? 'N/A') ?><br>
                            <strong>Trạng thái:</strong>
                            <span class="badge <?= $booking->getTrangThaiBadgeClass() ?>">
                                <?= $booking->getTrangThai() ?>
                            </span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-circle"></i> Cập nhật Booking
            </button>
            <a href="<?= BASE_URL ?>bookings" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Sửa Booking - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Sửa Booking',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>


