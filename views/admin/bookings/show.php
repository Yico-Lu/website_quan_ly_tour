<?php
ob_start();
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Thông tin Booking</h3>
                <div class="card-tools">
                    <a href="<?= BASE_URL ?>bookings/edit/<?= $booking->id ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <a href="<?= BASE_URL ?>bookings" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Mã booking:</strong>
                    </div>
                    <div class="col-sm-9">
                        #<?= $booking->id ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Người đặt:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-primary fs-6"><?= htmlspecialchars($booking->ten_nguoi_dat) ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Tour:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="text-primary fw-bold"><?= htmlspecialchars($booking->ten_tour ?? 'N/A') ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Loại khách:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge <?= $booking->getLoaiKhachBadgeClass() ?>">
                            <?= $booking->getLoaiKhach() ?>
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Số lượng:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-info fs-6"><?= (int)($booking->so_luong ?? 0) ?> người</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Bảng giá:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php
                        $donGia = $booking->gia_tour ?? 0;
                        $soLuong = $booking->so_luong ?? 0;
                        $thanhTien = $donGia * $soLuong;
                        $fmt = function ($n) {
                            return number_format((float)$n, 0, ',', '.') . ' VNĐ';
                        };
                        ?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <th style="width: 180px;">Đơn giá tour</th>
                                        <td><?= $fmt($donGia) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Số lượng</th>
                                        <td><?= (int)$soLuong ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-uppercase text-danger">Tổng tiền</th>
                                        <td><strong class="text-danger"><?= $fmt($thanhTien) ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- HDV phụ trách -->
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>HDV phụ trách:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php if (!empty($hdvs)): ?>
                            <?php foreach ($hdvs as $hdv): ?>
                                <div class="card mb-2">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>
                            <i class="bi bi-person-badge"></i>
                                                    <?= htmlspecialchars($hdv['ho_ten'] ?? 'N/A') ?>
                                                </strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope"></i>
                                                    <?= htmlspecialchars($hdv['email'] ?? '') ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-primary">
                                                <?php
                                                $vaiTroNames = [
                                                    'hdv' => 'HDV',
                                                    'hdv_chinh' => 'HDV chính',
                                                    'hdv_phu' => 'HDV phụ'
                                                ];
                                                echo $vaiTroNames[$hdv['vai_tro'] ?? 'hdv'] ?? 'HDV';
                                                ?>
                        </span>
                                        </div>
                                        <?php if (!empty($hdv['chi_tiet'])): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Chi tiết:</strong> <?= nl2br(htmlspecialchars($hdv['chi_tiet'])) ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="badge bg-secondary">
                                <i class="bi bi-person-badge"></i>
                                Chưa phân công
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Khách đại diện đặt tour -->
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Khách đại diện đặt tour:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?php 
                        $khachs = $booking->getKhachs();
                        if (!empty($khachs)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>STT</th>
                                            <th>Họ tên</th>
                                            <th>Giới tính</th>
                                            <th>Năm sinh</th>
                                            <th>Số giấy tờ</th>
                                            <th>Tình trạng thanh toán</th>
                                            <th>Yêu cầu cá nhân</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($khachs as $index => $khach): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><strong><?= htmlspecialchars($khach['ho_ten'] ?? 'N/A') ?></strong></td>
                                                <td>
                                                    <?php
                                                    $gioiTinhNames = [
                                                        'nam' => 'Nam',
                                                        'nu' => 'Nữ',
                                                        'khac' => 'Khác'
                                                    ];
                                                    echo $gioiTinhNames[$khach['gioi_tinh'] ?? ''] ?? 'N/A';
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($khach['nam_sinh'] ?? 'N/A') ?></td>
                                                <td><?= htmlspecialchars($khach['so_giay_to'] ?? 'N/A') ?></td>
                                                <td>
                                                    <span class="badge <?= Booking::getTinhTrangThanhToanBadgeClass($khach['tinh_trang_thanh_toan'] ?? 'chua_thanh_toan') ?>">
                                                        <?= Booking::getTinhTrangThanhToanName($khach['tinh_trang_thanh_toan'] ?? 'chua_thanh_toan') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($khach['yeu_cau_ca_nhan'])): ?>
                                                        <small><?= nl2br(htmlspecialchars($khach['yeu_cau_ca_nhan'])) ?></small>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Chưa có thông tin khách hàng</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Thời gian tour:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?= $booking->thoi_gian_tour ? date('d/m/Y H:i', strtotime($booking->thoi_gian_tour)) : 'Chưa xác định' ?>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Liên hệ:</strong>
                    </div>
                    <div class="col-sm-9">
                        <i class="bi bi-telephone"></i> <?= htmlspecialchars($booking->lien_he) ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Trạng thái:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge <?= $booking->getTrangThaiBadgeClass() ?> fs-6">
                            <?= $booking->getTrangThai() ?>
                        </span>
                    </div>
                </div>

                <?php if (!empty($booking->yeu_cau_dac_biet)): ?>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Yêu cầu đặc biệt:</strong>
                        </div>
                        <div class="col-sm-9">
                            <div class="bg-light p-2 rounded">
                                <?= nl2br(htmlspecialchars($booking->yeu_cau_dac_biet)) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($booking->ghi_chu)): ?>
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Ghi chú:</strong>
                        </div>
                        <div class="col-sm-9">
                            <div class="bg-light p-2 rounded">
                                <?= nl2br(htmlspecialchars($booking->ghi_chu)) ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Thông tin hệ thống</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Ngày tạo:</strong><br>
                    <span class="text-muted">
                        <i class="bi bi-calendar-plus"></i>
                        <?= date('d/m/Y H:i', strtotime($booking->ngay_tao)) ?>
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Ngày cập nhật:</strong><br>
                    <span class="text-muted">
                        <i class="bi bi-calendar-check"></i>
                        <?= date('d/m/Y H:i', strtotime($booking->ngay_cap_nhat)) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Dịch vụ booking -->
        <div class="card mb-3" id="dich-vu-booking">
            <div class="card-header">
                <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#dichVuCollapse" aria-expanded="false" aria-controls="dichVuCollapse">
                    <h5 class="card-title mb-0">Dịch vụ booking</h5>
                    <i class="bi bi-chevron-down ms-2"></i>
                </button>
            </div>
            <div id="dichVuCollapse" class="collapse">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3 gap-2">
                        <button class="btn btn-sm btn-outline-primary" type="button" id="btnDichVuAdd" onclick="toggleDichVuForm(true)">
                            <i class="bi bi-plus"></i> Thêm dịch vụ
                        </button>
                        <button class="btn btn-sm btn-secondary d-none" type="button" id="btnDichVuCancel" onclick="toggleDichVuForm(false)">
                            Hủy
                        </button>
                    </div>

                    <form id="dichVuForm" class="d-none mb-3" method="POST" action="<?= BASE_URL ?>bookings/update-dich-vu">
                        <input type="hidden" name="booking_id" value="<?= $booking->id ?>">
                        <input type="hidden" name="dich_vu_id" id="dich_vu_id" value="">
                        <div class="mb-3">
                            <label class="form-label">Tên dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" name="ten_dich_vu" id="ten_dich_vu" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Chi tiết</label>
                            <textarea name="chi_tiet" id="chi_tiet" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" onclick="toggleDichVuForm(false)">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>

                    <?php if (!empty($dichVus)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên dịch vụ</th>
                                        <th>Chi tiết</th>
                                        <th>Ngày tạo</th>
                                        <th style="width: 90px;">Sửa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dichVus as $dv): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($dv['ten_dich_vu'] ?? '') ?></td>
                                            <td><?= !empty($dv['chi_tiet']) ? nl2br(htmlspecialchars($dv['chi_tiet'])) : '<span class="text-muted">-</span>' ?></td>
                                            <td><?= !empty($dv['ngay_tao']) ? date('d/m/Y H:i', strtotime($dv['ngay_tao'])) : '<span class="text-muted">-</span>' ?></td>
                                            <td class="text-center">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-secondary"
                                                        data-id="<?= htmlspecialchars($dv['id'] ?? '') ?>"
                                                        data-ten="<?= htmlspecialchars($dv['ten_dich_vu'] ?? '', ENT_QUOTES) ?>"
                                                        data-chitiet="<?= htmlspecialchars($dv['chi_tiet'] ?? '', ENT_QUOTES) ?>"
                                                        onclick="onEditDichVu(this)">
                                                    Sửa
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <span class="text-muted">Chưa có dịch vụ</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Lịch khởi hành -->
        <div class="card mb-3" id="lich-khoi-hanh">
            <div class="card-header d-flex align-items-center justify-content-between">
                <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#lichKhoiHanhCollapse" aria-expanded="false" aria-controls="lichKhoiHanhCollapse">
                    <h5 class="card-title mb-0">Lịch khởi hành</h5>
                    <i class="bi bi-chevron-down ms-2"></i>
                </button>
            </div>
            <div id="lichKhoiHanhCollapse" class="collapse">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-sm btn-outline-primary" type="button" id="btnLkhEdit" onclick="toggleLkhEdit(true)">
                            <i class="bi bi-pencil"></i> Sửa
                        </button>
                        <button class="btn btn-sm btn-secondary ms-2 d-none" type="button" id="btnLkhCancel" onclick="toggleLkhEdit(false)">
                            Hủy
                        </button>
                    </div>
                <div id="lkhView">
                    <div class="mb-3">
                        <strong>Giờ xuất phát:</strong><br>
                        <?= $booking->ngay_gio_xuat_phat ? date('d/m/Y H:i', strtotime($booking->ngay_gio_xuat_phat)) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                    </div>
                    <div class="mb-3">
                        <strong>Điểm tập trung:</strong><br>
                        <?= !empty($booking->diem_tap_trung) ? htmlspecialchars($booking->diem_tap_trung) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                    </div>
                    <div class="mb-3">
                        <strong>Kết thúc:</strong><br>
                        <?= $booking->thoi_gian_ket_thuc ? date('d/m/Y H:i', strtotime($booking->thoi_gian_ket_thuc)) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                    </div>
                    <?php if (!empty($booking->lich_ghi_chu)): ?>
                    <div class="mb-0">
                        <strong>Ghi chú lịch khởi hành:</strong><br>
                        <div class="bg-light p-2 rounded"><?= nl2br(htmlspecialchars($booking->lich_ghi_chu)) ?></div>
                    </div>
                    <?php endif; ?>
                </div>

                <form id="lkhForm" class="d-none mt-2" method="POST" action="<?= BASE_URL ?>bookings/update-lich-khoi-hanh">
                    <input type="hidden" name="booking_id" value="<?= $booking->id ?>">
                    <div class="mb-3">
                        <label class="form-label">Giờ xuất phát</label>
                        <input type="datetime-local" name="ngay_gio_xuat_phat" class="form-control"
                               value="<?= $booking->ngay_gio_xuat_phat ? date('Y-m-d\TH:i', strtotime($booking->ngay_gio_xuat_phat)) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Điểm tập trung</label>
                        <input type="text" name="diem_tap_trung" class="form-control"
                               value="<?= htmlspecialchars($booking->diem_tap_trung ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kết thúc</label>
                        <input type="datetime-local" name="thoi_gian_ket_thuc" class="form-control"
                               value="<?= $booking->thoi_gian_ket_thuc ? date('Y-m-d\TH:i', strtotime($booking->thoi_gian_ket_thuc)) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú lịch khởi hành</label>
                        <textarea name="lich_ghi_chu" class="form-control" rows="3"><?= htmlspecialchars($booking->lich_ghi_chu ?? '') ?></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="toggleLkhEdit(false)">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- Danh sách khách hàng (file) -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Danh sách khách hàng</h5>
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
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#khachListModal" onclick="loadKhachList(<?= $booking->id ?>)">
                        <i class="bi bi-people"></i> Danh sách khách hàng
                    </button>
                <?php else: ?>
                    <span class="text-muted">Chưa có file danh sách khách hàng</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Thao tác nhanh -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thao tác</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= BASE_URL ?>bookings/edit/<?= $booking->id ?>" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>bookings/delete"
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa booking này? Hành động này không thể hoàn tác.')">
                        <input type="hidden" name="id" value="<?= $booking->id ?>">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Xóa booking
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal hiển thị danh sách khách hàng -->
<div class="modal fade" id="khachListModal" tabindex="-1" aria-labelledby="khachListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="khachListModalLabel">
                    <i class="bi bi-people"></i> Danh sách khách hàng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="khachListError" class="alert alert-danger d-none"></div>
                <div id="khachListContent">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Giới tính</th>
                                    <th>Năm sinh</th>
                                    <th>Số giấy tờ</th>
                                    <th>Yêu cầu cá nhân</th>
                                    <th class="text-center">Điểm danh</th>
                                </tr>
                            </thead>
                            <tbody id="khachListTableBody">
                                <!-- Dữ liệu sẽ được load bằng JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="saveAttendance(<?= $booking->id ?>)">
                    <i class="bi bi-save"></i> Lưu
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Set BASE_URL for booking-show.js
window.BASE_URL = '<?= BASE_URL ?>';
</script>

<?php
$content = ob_get_clean();

// Hiển thị layout admin với nội dung
view('layouts.AdminLayout', [
    'title' => $title ?? 'Chi tiết Booking - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Booking',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js', 'js/booking-show.js'],
]);
?>
