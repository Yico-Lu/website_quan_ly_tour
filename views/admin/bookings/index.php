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
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Tên người đặt</th>
                        <th>Tour</th>
                        <th>Loại khách</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
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
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-telephone"></i> <?= htmlspecialchars($booking->lien_he) ?>
                                    </small>
                                </td>
                                <td>
                                    <strong class="text-primary">
                                        <i class="bi bi-airplane"></i>
                                        <?= htmlspecialchars($booking->ten_tour ?? 'N/A') ?>
                                    </strong>
                                    <?php if (!empty($booking->ten_hdv)): ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-person"></i> HDV: <?= htmlspecialchars($booking->ten_hdv) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $booking->getLoaiKhachBadgeClass() ?>">
                                        <?= $booking->getLoaiKhach() ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-people"></i> <?= $booking->so_luong ?> người
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= $booking->getTrangThaiBadgeClass() ?>">
                                        <?= $booking->getTrangThai() ?>
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong><?= date('d/m/Y', strtotime($booking->ngay_tao)) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('H:i', strtotime($booking->ngay_tao)) ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm gap-1">
                                        <a href="<?= BASE_URL ?>bookings/show/<?= $booking->id ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>bookings/edit/<?= $booking->id ?>" class="btn btn-warning btn-sm" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="<?= BASE_URL ?>bookings/delete"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa booking này không?')">
                                            <input type="hidden" name="id" value="<?= $booking->id ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                                <h4 class="text-muted mt-3">Chưa có booking nào</h4>
                                <p class="text-muted">Hệ thống chưa có đơn đặt tour nào.</p>
                                <a href="<?= BASE_URL ?>bookings/create" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tạo booking đầu tiên
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-end">
            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-link disabled"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Danh sách Booking - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Danh sách Booking',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
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



