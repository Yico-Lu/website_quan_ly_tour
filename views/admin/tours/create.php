<?php
    ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Thêm Tour Mới</h3>
    </div>

    <form action="<?= BASE_URL ?>tours/store" method="POST">
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
                <!-- Tên tour -->
                <div class="col-md-8 mb-3">
                    <label for="ten_tour" class="form-label">Tên Tour <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control"
                        id="ten_tour"
                        name="ten_tour"
                        value="<?= htmlspecialchars($old['ten_tour'] ?? '') ?>"
                        placeholder="Nhập tên tour"
                        required
                    />
                </div>

                <!-- Danh mục -->
                <div class="col-md-4 mb-3">
                    <label for="danh_muc_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select class="form-select" id="danh_muc_id" name="danh_muc_id" required>
                        <option value="">Chọn danh mục</option>
                        <?php foreach($danhMucList as $danhMuc): ?>
                            <option
                                value="<?= $danhMuc['id'] ?>"
                                <?= ($old['danh_muc_id'] ?? '') == $danhMuc['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($danhMuc['ten_danh_muc']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Giá tour -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gia" class="form-label">Giá Tour (VNĐ) <span class="text-danger">*</span></label>
                    <input
                        type="number"
                        class="form-control"
                        id="gia"
                        name="gia"
                        value="<?= htmlspecialchars($old['gia'] ?? '') ?>"
                        placeholder="0"
                        min="1"
                        required
                    />
                </div>

                <!-- Trạng thái -->
                <div class="col-md-6 mb-3">
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
                <label for="mo_ta" class="form-label">Mô tả Tour <span class="text-danger">*</span></label>
                <textarea
                    class="form-control"
                    id="mo_ta"
                    name="mo_ta"
                    rows="2"
                    placeholder="Nhập mô tả chi tiết về tour..."
                    required
                ><?= htmlspecialchars($old['mo_ta'] ?? '') ?></textarea>
            </div>

            <!-- Ảnh tour -->
            <div class="mb-3">
                <label for="anh_tour" class="form-label">Ảnh Tour</label>
                <input
                    type="text"
                    class="form-control"
                    id="anh_tour"
                    name="anh_tour"
                    value="<?= htmlspecialchars($old['anh_tour'] ?? '') ?>"
                    placeholder="Đường dẫn ảnh tour (VD: tour-ha-noi.jpg)"
                />
                <div class="form-text">Có thể để trống, sẽ dùng ảnh mặc định</div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Thêm Tour
            </button>
            <a href="<?= BASE_URL ?>tours" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Thêm Tour Mới',
    'pageTitle' => $pageTitle ?? 'Thêm Tour Mới',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>