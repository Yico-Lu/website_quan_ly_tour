<?php
ob_start();
?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Danh sách Tour của tôi</h3>
    </div>
    <div class="card-body">
        <?php displayFlashMessages(); ?>

        <table class="table table-bordered table-hover text-center">
            <thead class="table-light">
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Tour</th>
                    <th>Người đặt</th>
                    <th>Thời gian tour</th>
                    <th>Ngày khởi hành</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th style="width: 200px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $index => $booking): ?>
                        <tr class="align-middle">
                            <td><?= $index + 1 ?>.</td>
                            <td>
                                <strong><?= htmlspecialchars($booking->ten_tour ?? 'N/A') ?></strong>
                            </td>
                            <td><?= htmlspecialchars($booking->ten_nguoi_dat) ?></td>
                            <td>
                                <?= $booking->thoi_gian_tour ? date('d/m/Y H:i', strtotime($booking->thoi_gian_tour)) : 'Chưa xác định' ?>
                            </td>
                            <td>
                                <?php if ($booking->ngay_gio_xuat_phat): ?>
                                    <?= date('d/m/Y H:i', strtotime($booking->ngay_gio_xuat_phat)) ?>
                                    <?php if ($booking->diem_tap_trung): ?>
                                        <br><small class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($booking->diem_tap_trung) ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Chưa có</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= $booking->so_luong ?> người</span>
                            </td>
                            <td>
                                <span class="badge <?= $booking->getTrangThaiBadgeClass() ?>">
                                    <?= $booking->getTrangThai() ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm gap-1">
                                    <a href="<?= BASE_URL ?>guide/booking/<?= $booking->id ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i> Chi tiết
                                    </a>
                                    <a href="<?= BASE_URL ?>guide/diary/<?= $booking->id ?>" class="btn btn-success btn-sm" title="Nhật ký tour">
                                        <i class="bi bi-journal-text"></i> Nhật ký
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <h3 class="text-muted">Chưa có tour nào được phân công</h3>
                            <p class="text-muted">Bạn sẽ thấy các tour được phân công ở đây</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.GuideLayout', [
    'title' => $title ?? 'Danh sách Tour của tôi',
    'pageTitle' => $pageTitle ?? 'Danh sách Tour của tôi',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>

