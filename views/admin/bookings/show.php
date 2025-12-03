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
                        <strong>HDV phụ trách:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-info">
                            <i class="bi bi-person-badge"></i>
                            <?= htmlspecialchars($booking->ten_hdv ?? 'Chưa phân công') ?>
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Số lượng:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-info fs-6"><?= $booking->so_luong ?> người</span>
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

<?php
$content = ob_get_clean();

// Hiển thị layout admin với nội dung
view('layouts.AdminLayout', [
    'title' => $title ?? 'Chi tiết Booking - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Booking',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>
