<?php
ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Thêm Danh mục Tour mới</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>categories" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="<?= BASE_URL ?>categories/store" method="POST">
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
                        value="<?= htmlspecialchars($old['ten_danh_muc'] ?? '') ?>"
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
                            <?= ($old['trang_thai'] ?? 1) ? 'checked' : '' ?>
                        />
                        <label class="form-check-label" for="trang_thai">
                            Hoạt động
                        </label>
                    </div>
                    <div class="form-text">Bỏ check nếu muốn tạm ngưng danh mục này</div>
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
                ><?= htmlspecialchars($old['mo_ta'] ?? '') ?></textarea>
                <div class="form-text">Mô tả sẽ giúp người dùng hiểu rõ hơn về danh mục này</div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Tạo Danh mục
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
    'title' => $title ?? 'Thêm Danh mục Tour mới - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Thêm Danh mục Tour mới',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>





