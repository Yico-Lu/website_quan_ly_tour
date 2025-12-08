<?php
ob_start();
?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Danh sách Booking</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>bookings/create" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Thêm Booking mới
            </a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table class="table table-bordered table-hover text-center">
            <thead class="table-light">
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Người đặt</th>
                    <th>Tour</th>
                    <th>HDV Phụ trách</th>
                    <th>Thời gian</th>
                    <th>Số lượng</th>
                    <th>Loại khách</th>
                    <th>Trạng thái</th>
                    <th style="width: 180px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $index => $booking): ?>
                        <tr class="align-middle">
                            <td><?= $index + 1 ?>.</td>
                            <td>
                                <strong><?= htmlspecialchars($booking->ten_nguoi_dat) ?></strong>
                            </td>
                            <td>
                                <?= htmlspecialchars($booking->ten_tour ?? 'N/A') ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-person-badge"></i>
                                    <?= htmlspecialchars($booking->ten_hdv ?? 'Chưa phân công') ?>
                                </small>
                            </td>
                            <td>
                                <?= $booking->thoi_gian_tour ? date('d/m/Y H:i', strtotime($booking->thoi_gian_tour)) : 'Chưa xác định' ?>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= $booking->so_luong ?> người</span>
                            </td>
                            <td>
                                <span class="badge <?= $booking->getLoaiKhachBadgeClass() ?>">
                                    <?= $booking->getLoaiKhach() ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $booking->getTrangThaiBadgeClass() ?>">
                                    <?= $booking->getTrangThai() ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm gap-1">
                                    <a href="<?= BASE_URL ?>bookings/show/<?= $booking->id ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>bookings/edit/<?= $booking->id ?>" class="btn btn-warning btn-sm" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>bookings/delete" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa booking này? Hành động này không thể hoàn tác.')">
                                        <input type="hidden" name="id" value="<?= $booking->id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa booking">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Chưa có booking nào</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>

<?php
$content = ob_get_clean();

// Hiển thị layout admin với nội dung
view('layouts.AdminLayout', [
    'title' => $title ?? 'Danh sách Booking - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Danh sách Booking',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>

<!-- Hiển thị thông báo -->
<?php if (isset($_SESSION['success'])): ?>
    <script>
        alert('<?= addslashes($_SESSION['success']) ?>');
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <script>
        alert('Lỗi: <?= addslashes($_SESSION['error']) ?>');
    </script>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

