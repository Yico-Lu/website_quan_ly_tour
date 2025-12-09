<?php
ob_start();
?>

<div class="row">
    <!-- Thông tin booking -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin Tour</h3>
                <div class="card-tools">
                    <a href="<?= BASE_URL ?>guide/my-bookings" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php displayFlashMessages(); ?>
                
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Tour:</strong></div>
                    <div class="col-md-9">
                        <span class="text-primary fw-bold"><?= htmlspecialchars($booking->ten_tour ?? 'N/A') ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Người đặt:</strong></div>
                    <div class="col-md-9"><?= htmlspecialchars($booking->ten_nguoi_dat) ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Số lượng:</strong></div>
                    <div class="col-md-9">
                        <span class="badge bg-primary"><?= $booking->so_luong ?> người</span>
                    </div>
                </div>

                <?php if ($booking->ngay_gio_xuat_phat): ?>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Thời gian khởi hành:</strong></div>
                    <div class="col-md-9">
                        <span class="text-success fw-bold">
                            <i class="bi bi-calendar-check"></i> <?= date('d/m/Y H:i', strtotime($booking->ngay_gio_xuat_phat)) ?>
                        </span>
                        <small class="text-muted ms-2">(Đã xác nhận)</small>
                    </div>
                </div>
                <?php elseif ($booking->thoi_gian_tour): ?>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Thời gian dự kiến:</strong></div>
                    <div class="col-md-9">
                        <span class="text-warning">
                            <i class="bi bi-calendar-event"></i> <?= date('d/m/Y H:i', strtotime($booking->thoi_gian_tour)) ?>
                        </span>
                        <small class="text-muted ms-2">(Chưa xác nhận)</small>
                    </div>
                </div>
                <?php else: ?>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Thời gian tour:</strong></div>
                    <div class="col-md-9">
                        <span class="text-muted">Chưa xác định</span>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($booking->thoi_gian_ket_thuc): ?>
                <div class="row mb-3">
                    <div class="col-md-3"><strong>Thời gian kết thúc:</strong></div>
                    <div class="col-md-9">
                        <span class="text-info">
                            <i class="bi bi-calendar-x"></i> <?= date('d/m/Y H:i', strtotime($booking->thoi_gian_ket_thuc)) ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Địa điểm tập trung:</strong></div>
                    <div class="col-md-9">
                        <?= !empty($booking->diem_tap_trung) ? '<i class="bi bi-geo-alt"></i> ' . htmlspecialchars($booking->diem_tap_trung) : '<span class="text-muted">Chưa có thông tin</span>' ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3"><strong>Trạng thái:</strong></div>
                    <div class="col-md-9">
                        <span class="badge <?= $booking->getTrangThaiBadgeClass() ?>">
                            <?= $booking->getTrangThai() ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch trình tour -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lịch trình Tour</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($lichTrinh)): ?>
                    <?php foreach ($lichTrinh as $lt): ?>
                        <div class="border rounded p-3 mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="bi bi-calendar-day"></i> Ngày <?= $lt['ngay'] ?? '' ?>
                            </h6>
                            <?php if (!empty($lt['diem_tham_quan'])): ?>
                                <p class="mb-2">
                                    <strong>Điểm tham quan:</strong> <?= htmlspecialchars($lt['diem_tham_quan']) ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($lt['hoat_dong'])): ?>
                                <p class="mb-0">
                                    <strong>Hoạt động:</strong><br>
                                    <?= nl2br(htmlspecialchars($lt['hoat_dong'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Chưa có lịch trình cho tour này.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Danh sách khách từ file Excel (tham khảo) -->
    <?php if (!empty($guestListFromFile)): ?>
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách khách</h3>
            </div>
            <div class="card-body">
                <?php if (empty($lichKhoiHanhId)): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle"></i>
                        Chưa có lịch khởi hành. Vui lòng cập nhật ngày giờ xuất phát để bật check-in.
                    </div>
                <?php elseif (!$canCheckIn): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Check-in chỉ khả dụng khi tour đang diễn ra (đã đến giờ khởi hành và chưa hoàn thành).
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> Tổng số khách trong file: <strong><?= count($guestListFromFile) ?></strong> người
                    </small>
                </div>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Họ tên</th>
                            <th>Giới tính</th>
                            <th>Năm sinh</th>
                            <th>Số giấy tờ</th>
                            <th>Yêu cầu cá nhân</th>
                            <th style="width: 120px">Check-in</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($guestListFromFile as $idx => $row): ?>
                            <tr>
                                <td><?= $idx + 1 ?>.</td>
                                <td><strong><?= htmlspecialchars($row['ho_ten'] ?? '') ?></strong></td>
                                <td><?= htmlspecialchars($row['gioi_tinh'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['nam_sinh'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['so_giay_to'] ?? '') ?></td>
                                <td><?= !empty($row['yeu_cau_ca_nhan']) ? nl2br(htmlspecialchars($row['yeu_cau_ca_nhan'])) : '<span class="text-muted">Chưa có</span>' ?></td>
                                <td>
                                    <?php if (!empty($row['booking_khach_id'])): ?>
                                        <?php 
                                            $dd = $diemDanhMap[$row['booking_khach_id']] ?? null;
                                            $trangThaiCheckIn = $dd['trang_thai'] ?? null;
                                            $isCheckedIn = $trangThaiCheckIn === 'da_den';
                                        ?>
                                        <button 
                                            type="button" 
                                            class="btn btn-sm <?= $isCheckedIn ? 'btn-success' : 'btn-outline-secondary' ?>" 
                                            onclick="toggleCheckIn(<?= $row['booking_khach_id'] ?>, <?= $isCheckedIn ? 'false' : 'true' ?>)"
                                            id="btnCheckInFile<?= $row['booking_khach_id'] ?>"
                                            <?= (!$canCheckIn ? 'disabled' : '') ?>
                                        >
                                            <?= $isCheckedIn ? '<i class="bi bi-check-circle"></i> Đã đến' : '<i class="bi bi-x-circle"></i> Vắng mặt' ?>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa có trong hệ thống</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Danh sách khách và check-in -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách khách và Check-in</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($khachs)): ?>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Tổng số khách: <strong><?= count($khachs) ?></strong> người
                        </small>
                    </div>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Họ tên</th>
                                <th>Giới tính</th>
                                <th>Năm sinh</th>
                                <th>Số giấy tờ</th>
                                <th>Yêu cầu cá nhân</th>
                                <th style="width: 120px">Check-in</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $diemDanhMap = [];
                            foreach ($diemDanh as $dd) {
                                if (!empty($dd['booking_khach_id'])) {
                                    $diemDanhMap[$dd['booking_khach_id']] = $dd;
                                }
                            }
                            ?>
                            <?php foreach ($khachs as $index => $khach): ?>
                                <?php 
                                $dd = $diemDanhMap[$khach['id']] ?? null;
                                $trangThaiCheckIn = $dd['trang_thai'] ?? null;
                                $isCheckedIn = $trangThaiCheckIn === 'da_den';
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?>.</td>
                                    <td><strong><?= htmlspecialchars($khach['ho_ten']) ?></strong></td>
                                    <td><?= htmlspecialchars($khach['gioi_tinh'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($khach['nam_sinh'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($khach['so_giay_to'] ?? '') ?></td>
                                    <td>
                                        <span id="yeuCauText<?= $khach['id'] ?>">
                                            <?= !empty($khach['yeu_cau_ca_nhan']) ? nl2br(htmlspecialchars($khach['yeu_cau_ca_nhan'])) : '<span class="text-muted">Chưa có</span>' ?>
                                        </span>
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-outline-primary ms-2" 
                                            onclick="showUpdateYeuCau(<?= $khach['id'] ?>, '<?= htmlspecialchars(addslashes($khach['yeu_cau_ca_nhan'] ?? '')) ?>')"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button 
                                            type="button" 
                                            class="btn btn-sm <?= $isCheckedIn ? 'btn-success' : 'btn-outline-secondary' ?>" 
                                            onclick="toggleCheckIn(<?= $khach['id'] ?>, <?= $isCheckedIn ? 'false' : 'true' ?>)"
                                            id="btnCheckIn<?= $khach['id'] ?>"
                                            <?= (!$canCheckIn ? 'disabled' : '') ?>
                                        >
                                            <?= $isCheckedIn ? '<i class="bi bi-check-circle"></i> Đã đến' : '<i class="bi bi-x-circle"></i> Vắng mặt' ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Chưa có danh sách khách cho booking này.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Yêu cầu đặc biệt của cả đoàn -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Yêu cầu đặc biệt của cả đoàn</h3>
                <button 
                    type="button" 
                    class="btn btn-sm btn-outline-primary" 
                    onclick="showUpdateYeuCauDoan()"
                >
                    <i class="bi bi-pencil"></i> Sửa
                </button>
            </div>
            <div class="card-body">
                <div id="yeuCauDoanText">
                    <?= !empty($booking->yeu_cau_dac_biet) ? nl2br(htmlspecialchars($booking->yeu_cau_dac_biet)) : '<span class="text-muted">Chưa có yêu cầu đặc biệt</span>' ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal cập nhật yêu cầu cá nhân -->
    <div class="modal fade" id="updateYeuCauModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="<?= BASE_URL ?>guide/update-yeu-cau">
                    <input type="hidden" name="booking_id" value="<?= $booking->id ?>">
                    <input type="hidden" name="booking_khach_id" id="modalKhachId">
                    
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật yêu cầu cá nhân</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Yêu cầu cá nhân</label>
                            <textarea 
                                class="form-control" 
                                name="yeu_cau_ca_nhan" 
                                id="modalYeuCau" 
                                rows="4" 
                                placeholder="Nhập yêu cầu cá nhân (ăn chay, bệnh lý, v.v.)"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function toggleCheckIn(khachId, isCheckIn) {
        const lichKhoiHanhId = '<?= $lichKhoiHanhId ?>';
        const canCheckIn = <?= $canCheckIn ? 'true' : 'false' ?>;
        if (!lichKhoiHanhId) {
            alert('Vui lòng cập nhật lịch khởi hành trước khi check-in.');
            return;
        }
        if (!canCheckIn) {
            alert('Check-in chỉ khả dụng khi tour đang diễn ra.');
            return;
        }

        const formData = new FormData();
        formData.append('booking_id', '<?= $booking->id ?>');
        formData.append('lich_khoi_hanh_id', lichKhoiHanhId);
        formData.append('booking_khach_id', khachId);
        formData.append('trang_thai', isCheckIn ? 'da_den' : 'vang_mat');
        
        fetch('<?= BASE_URL ?>guide/check-in', {
            method: 'POST',
            body: formData
        }).then(() => {
            location.reload();
        });
    }

    function showUpdateYeuCau(khachId, yeuCau) {
        document.getElementById('modalKhachId').value = khachId;
        document.getElementById('modalYeuCau').value = yeuCau;
        const modal = new bootstrap.Modal(document.getElementById('updateYeuCauModal'));
        modal.show();
    }

    function showUpdateYeuCauDoan() {
        const yeuCauDoan = '<?= htmlspecialchars(addslashes($booking->yeu_cau_dac_biet ?? '')) ?>';
        document.getElementById('modalYeuCauDoan').value = yeuCauDoan;
        const modal = new bootstrap.Modal(document.getElementById('updateYeuCauDoanModal'));
        modal.show();
    }
    </script>

    <!-- Modal cập nhật yêu cầu đặc biệt của cả đoàn -->
    <div class="modal fade" id="updateYeuCauDoanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="<?= BASE_URL ?>guide/update-yeu-cau-doan">
                    <input type="hidden" name="booking_id" value="<?= $booking->id ?>">
                    
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật yêu cầu đặc biệt của cả đoàn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Yêu cầu đặc biệt của cả đoàn</label>
                            <textarea 
                                class="form-control" 
                                name="yeu_cau_dac_biet" 
                                id="modalYeuCauDoan" 
                                rows="5" 
                                placeholder="Nhập yêu cầu đặc biệt của cả đoàn (ví dụ: ăn chay tập thể, yêu cầu về phương tiện, v.v.)"
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.GuideLayout', [
    'title' => $title ?? 'Chi tiết Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>

