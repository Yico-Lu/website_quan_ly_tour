<?php
ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Chi tiết Booking</h3>
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
        <!-- Thông tin cơ bản -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="text-primary mb-3"><i class="bi bi-person-circle"></i> Thông tin người đặt</h5>
                <div class="bg-light p-3 rounded">
                    <p><strong>Họ tên:</strong> <?= htmlspecialchars($booking->ten_nguoi_dat) ?></p>
                    <p><strong>Liên hệ:</strong> <?= htmlspecialchars($booking->lien_he) ?></p>
                    <p><strong>Loại khách:</strong>
                        <span class="badge <?= $booking->getLoaiKhachBadgeClass() ?>">
                            <?= $booking->getLoaiKhach() ?>
                        </span>
                    </p>
                    <p><strong>Số lượng:</strong> <span class="badge bg-info"><?= $booking->so_luong ?> người</span></p>
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="text-success mb-3"><i class="bi bi-airplane"></i> Thông tin tour</h5>
                <div class="bg-light p-3 rounded">
                    <p><strong>Tour:</strong> <?= htmlspecialchars($booking->ten_tour ?? 'N/A') ?></p>
                    <p><strong>Thời gian:</strong> <?= htmlspecialchars($booking->thoi_gian_tour) ?></p>
                    <?php if (!empty($booking->ten_hdv)): ?>
                        <p><strong>Hướng dẫn viên:</strong> <?= htmlspecialchars($booking->ten_hdv) ?></p>
                    <?php else: ?>
                        <p><strong>Hướng dẫn viên:</strong> <span class="text-muted">Chưa phân công</span></p>
                    <?php endif; ?>
                    <p><strong>Trạng thái:</strong>
                        <span class="badge <?= $booking->getTrangThaiBadgeClass() ?>">
                            <?= $booking->getTrangThai() ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Yêu cầu đặc biệt và ghi chú -->
        <?php if (!empty($booking->yeu_cau_dac_biet) || !empty($booking->ghi_chu)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-warning mb-3"><i class="bi bi-exclamation-triangle"></i> Yêu cầu và ghi chú</h5>
                    <div class="bg-light p-3 rounded">
                        <?php if (!empty($booking->yeu_cau_dac_biet)): ?>
                            <p><strong>Yêu cầu đặc biệt:</strong></p>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($booking->yeu_cau_dac_biet)) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($booking->ghi_chu)): ?>
                            <p><strong>Ghi chú:</strong></p>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($booking->ghi_chu)) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Thông tin hệ thống -->
        <div class="row">
            <div class="col-12">
                <h5 class="text-info mb-3"><i class="bi bi-info-circle"></i> Thông tin hệ thống</h5>
                <div class="bg-light p-3 rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ngày tạo:</strong> <?= date('d/m/Y H:i:s', strtotime($booking->ngay_tao)) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ngày cập nhật:</strong> <?= date('d/m/Y H:i:s', strtotime($booking->ngay_cap_nhat)) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= BASE_URL ?>bookings/edit/<?= $booking->id ?>" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Sửa Booking
        </a>
        <form method="POST" action="<?= BASE_URL ?>bookings/delete" class="d-inline"
              onsubmit="return confirm('Bạn có chắc chắn muốn xóa booking này không?')">
            <input type="hidden" name="id" value="<?= $booking->id ?>">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Xóa Booking
            </button>
        </form>
        <a href="<?= BASE_URL ?>bookings" class="btn btn-secondary float-end">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Chi tiết Booking - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Booking',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>

