<?php
ob_start();
?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Danh sách Danh mục Tour</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>categories/create" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Thêm Danh mục mới
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
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th>Số tour</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th style="width: 180px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($danhMucs)): ?>
                    <?php foreach ($danhMucs as $index => $danhMuc): ?>
                        <tr class="align-middle">
                            <td><?= $index + 1 ?>.</td>
                            <td>
                                <strong><?= htmlspecialchars($danhMuc->ten_danh_muc) ?></strong>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= htmlspecialchars(substr($danhMuc->mo_ta, 0, 50)) ?>
                                    <?= strlen($danhMuc->mo_ta) > 50 ? '...' : '' ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?= $danhMuc->getSoLuongTour() ?> tour</span>
                            </td>
                            <td>
                                <span class="badge <?= $danhMuc->getTrangThaiBadgeClass() ?>">
                                    <?= $danhMuc->getTrangThai() ?>
                                </span>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($danhMuc->ngay_tao)) ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm gap-1">
                                    <a href="<?= BASE_URL ?>categories/show/<?= $danhMuc->id ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>categories/edit/<?= $danhMuc->id ?>" class="btn btn-warning btn-sm" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>categories/delete" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Tất cả tour trong danh mục sẽ bị ảnh hưởng.')">
                                        <input type="hidden" name="id" value="<?= $danhMuc->id ?>">
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
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-folder-x text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Chưa có danh mục tour nào</p>
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
    'title' => $title ?? 'Danh sách Danh mục Tour - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Danh sách Danh mục Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>



