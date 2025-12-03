<?php
ob_start();
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Thông tin Danh mục Tour</h3>
                <div class="card-tools">
                    <a href="<?= BASE_URL ?>categories/edit/<?= $danhMuc->id ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <a href="<?= BASE_URL ?>categories" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Mã danh mục:</strong>
                    </div>
                    <div class="col-sm-9">
                        #<?= $danhMuc->id ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Tên danh mục:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-primary fs-6"><?= htmlspecialchars($danhMuc->ten_danh_muc) ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Mô tả:</strong>
                    </div>
                    <div class="col-sm-9">
                        <div class="bg-light p-3 rounded">
                            <?= nl2br(htmlspecialchars($danhMuc->mo_ta)) ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Số tour:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-info fs-6"><?= $danhMuc->getSoLuongTour() ?> tour</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Trạng thái:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge <?= $danhMuc->getTrangThaiBadgeClass() ?> fs-6">
                            <?= $danhMuc->getTrangThai() ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách tour trong danh mục này -->
        <?php if ($danhMuc->getSoLuongTour() > 0): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Danh sách Tour trong danh mục này</h5>
            </div>
            <div class="card-body">
                <?php
                $pdo = getDB();
                $sql = "SELECT id, ten_tour, gia, trang_thai FROM tour WHERE danh_muc_id = ? ORDER BY ngay_tao DESC LIMIT 10";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$danhMuc->id]);
                $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Tên tour</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $tour): ?>
                            <tr>
                                <td>
                                    <a href="<?= BASE_URL ?>tours/edit/<?= $tour['id'] ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($tour['ten_tour']) ?>
                                    </a>
                                </td>
                                <td><?= number_format($tour['gia'], 0, ',', '.') ?> VND</td>
                                <td>
                                    <span class="badge <?= $tour['trang_thai'] == 1 ? 'text-bg-success' : 'text-bg-danger' ?>">
                                        <?= $tour['trang_thai'] == 1 ? 'Hoạt động' : 'Ngưng hoạt động' ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($danhMuc->getSoLuongTour() > 10): ?>
                <div class="text-center mt-3">
                    <small class="text-muted">... và <?= $danhMuc->getSoLuongTour() - 10 ?> tour khác</small>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
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
                        <?= date('d/m/Y H:i', strtotime($danhMuc->ngay_tao)) ?>
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Ngày cập nhật:</strong><br>
                    <span class="text-muted">
                        <i class="bi bi-calendar-check"></i>
                        <?= date('d/m/Y H:i', strtotime($danhMuc->ngay_cap_nhat)) ?>
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
                    <a href="<?= BASE_URL ?>categories/edit/<?= $danhMuc->id ?>" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>categories/delete"
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Tất cả tour trong danh mục sẽ bị ảnh hưởng.')">
                        <input type="hidden" name="id" value="<?= $danhMuc->id ?>">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Xóa danh mục
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
    'title' => $title ?? 'Chi tiết Danh mục - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Danh mục Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>
