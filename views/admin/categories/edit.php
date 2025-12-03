<?php
ob_start();
?>

<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa Danh mục Tour</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>categories/show/<?= $danhMuc->id ?>" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Xem chi tiết
            </a>
            <a href="<?= BASE_URL ?>categories" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="<?= BASE_URL ?>categories/update" method="POST">
        <input type="hidden" name="id" value="<?= $danhMuc->id ?>">
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
                <!-- Tên danh mục -->
                <div class="col-md-8 mb-3">
                    <label for="ten_danh_muc" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control"
                        id="ten_danh_muc"
                        name="ten_danh_muc"
                        value="<?= htmlspecialchars($old['ten_danh_muc'] ?? $danhMuc->ten_danh_muc) ?>"
                        placeholder="Nhập tên danh mục tour"
                        required
                    />
                    <div class="form-text">Ví dụ: Tour miền Bắc, Tour miền Trung, Tour Phú Quốc...</div>
                </div>

                <!-- Trạng thái -->
                <div class="col-md-4 mb-3">
                    <label for="trang_thai" class="form-label">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            role="switch"
                            id="trang_thai"
                            name="trang_thai"
                            value="1"
                            <?= ($old['trang_thai'] ?? $danhMuc->trang_thai) ? 'checked' : '' ?>
                        />
                        <label class="form-check-label" for="trang_thai">
                            Hoạt động
                        </label>
                    </div>
                    <div class="form-text">
                        Bỏ check nếu muốn tạm ngưng danh mục này<br>
                        <small class="text-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Có <?= $danhMuc->getSoLuongTour() ?> tour trong danh mục này
                        </small>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="mb-3">
                <label for="mo_ta" class="form-label">Mô tả <span class="text-danger">*</span></label>
                <textarea
                    class="form-control"
                    id="mo_ta"
                    name="mo_ta"
                    rows="4"
                    placeholder="Mô tả chi tiết về danh mục tour này..."
                    required
                ><?= htmlspecialchars($old['mo_ta'] ?? $danhMuc->mo_ta) ?></textarea>
                <div class="form-text">Mô tả sẽ giúp người dùng hiểu rõ hơn về danh mục này</div>
            </div>

            <!-- Thông tin bổ sung -->
            <div class="row">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6><i class="bi bi-info-circle"></i> Thông tin hệ thống</h6>
                        <small class="text-muted">
                            <strong>Ngày tạo:</strong> <?= date('d/m/Y H:i', strtotime($danhMuc->ngay_tao)) ?><br>
                            <strong>Ngày cập nhật:</strong> <?= date('d/m/Y H:i', strtotime($danhMuc->ngay_cap_nhat)) ?>
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6><i class="bi bi-bar-chart"></i> Thống kê</h6>
                        <small class="text-muted">
                            <strong>Số tour:</strong> <span class="badge bg-primary"><?= $danhMuc->getSoLuongTour() ?></span><br>
                            <strong>Trạng thái:</strong>
                            <span class="badge <?= $danhMuc->getTrangThaiBadgeClass() ?>">
                                <?= $danhMuc->getTrangThai() ?>
                            </span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-circle"></i> Cập nhật Danh mục
            </button>
            <a href="<?= BASE_URL ?>categories" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

// Hiển thị layout admin với nội dung
view('layouts.AdminLayout', [
    'title' => $title ?? 'Chỉnh sửa Danh mục Tour - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Chỉnh sửa Danh mục Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>
