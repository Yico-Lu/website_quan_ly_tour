<?php
    ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Thêm Danh mục Mới</h3>
    </div>

    <form action="<?= BASE_URL ?>categories/store" method="POST">
        <div class="card-body">
            <!-- Hiển thị lỗi -->
            <?php if(isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>Có lỗi:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Tên danh mục -->
                <div class="col-md-8 mb-3">
                    <label for="ten_danh_muc" class="form-label">Tên Danh mục <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control"
                        id="ten_danh_muc"
                        name="ten_danh_muc"
                        value="<?= htmlspecialchars($old['ten_danh_muc'] ?? '') ?>"
                        placeholder="Nhập tên danh mục"
                        required
                    />
                </div>

                <!-- Trạng thái -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Trạng thái</label>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="trang_thai"
                            name="trang_thai"
                            value="1"
                            <?= ($old['trang_thai'] ?? 1) ? 'checked' : '' ?>
                        />
                        <label class="form-check-label" for="trang_thai">
                            Hoạt động
                        </label>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="mb-3">
                <label for="mo_ta" class="form-label">Mô tả Danh mục <span class="text-danger">*</span></label>
                <textarea
                    class="form-control"
                    id="mo_ta"
                    name="mo_ta"
                    rows="4"
                    placeholder="Nhập mô tả chi tiết về danh mục..."
                    required
                ><?= htmlspecialchars($old['mo_ta'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Thêm Danh mục
            </button>
            <a href="<?= BASE_URL ?>categories" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Thêm Danh mục Mới',
    'pageTitle' => $pageTitle ?? 'Thêm Danh mục Mới',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>
