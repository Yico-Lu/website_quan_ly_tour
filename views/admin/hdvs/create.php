<?php
ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Thêm Hướng dẫn viên mới</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>hdvs" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="<?= BASE_URL ?>hdvs/store" method="POST" enctype="multipart/form-data">
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
                <!-- Tài khoản -->
                <div class="col-md-6 mb-3">
                    <label for="tai_khoan_id" class="form-label">Tài khoản <span class="text-danger">*</span></label>
                    <select class="form-select" id="tai_khoan_id" name="tai_khoan_id" required>
                        <option value="">-- Chọn tài khoản --</option>
                        <?php foreach ($availableAccounts as $account): ?>
                            <option value="<?= $account['id'] ?>" 
                                    <?= ($old['tai_khoan_id'] ?? '') == $account['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($account['ho_ten']) ?> (<?= htmlspecialchars($account['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Chọn tài khoản HDV chưa có thông tin chi tiết</div>
                    <?php if (empty($availableAccounts)): ?>
                        <div class="alert alert-warning mt-2">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Không có tài khoản HDV nào chưa có thông tin chi tiết. 
                            Vui lòng tạo tài khoản HDV mới trước.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Nhóm -->
                <div class="col-md-6 mb-3">
                    <label for="nhom" class="form-label">Nhóm <span class="text-danger">*</span></label>
                    <select class="form-select" id="nhom" name="nhom" required>
                        <option value="">-- Chọn nhóm --</option>
                        <option value="noi_dia" <?= ($old['nhom'] ?? '') == 'noi_dia' ? 'selected' : '' ?>>Nội địa</option>
                        <option value="quoc_te" <?= ($old['nhom'] ?? '') == 'quoc_te' ? 'selected' : '' ?>>Quốc tế</option>
                        <option value="yeu_cau" <?= ($old['nhom'] ?? '') == 'yeu_cau' ? 'selected' : '' ?>>Theo yêu cầu</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Ngày sinh -->
                <div class="col-md-6 mb-3">
                    <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                    <input
                        type="date"
                        class="form-control"
                        id="ngay_sinh"
                        name="ngay_sinh"
                        value="<?= htmlspecialchars($old['ngay_sinh'] ?? '') ?>"
                    />
                </div>

                <!-- Liên hệ -->
                <div class="col-md-6 mb-3">
                    <label for="lien_he" class="form-label">Liên hệ</label>
                    <input
                        type="text"
                        class="form-control"
                        id="lien_he"
                        name="lien_he"
                        value="<?= htmlspecialchars($old['lien_he'] ?? '') ?>"
                        placeholder="Số điện thoại, email..."
                    />
                </div>
            </div>

            <!-- Chuyên môn -->
            <div class="mb-3">
                <label for="chuyen_mon" class="form-label">Chuyên môn <span class="text-danger">*</span></label>
                <textarea
                    class="form-control"
                    id="chuyen_mon"
                    name="chuyen_mon"
                    rows="3"
                    placeholder="Ví dụ: Hướng dẫn văn hóa, lịch sử; Hướng dẫn du lịch thiên nhiên..."
                    required
                ><?= htmlspecialchars($old['chuyen_mon'] ?? '') ?></textarea>
                <div class="form-text">Mô tả chuyên môn và kinh nghiệm của hướng dẫn viên</div>
            </div>

            <!-- Ảnh đại diện -->
            <div class="mb-3">
                <label for="anh_dai_dien" class="form-label">Ảnh đại diện</label>
                <input
                    type="file"
                    class="form-control"
                    id="anh_dai_dien"
                    name="anh_dai_dien"
                    accept="image/*"
                />
                <div class="form-text">Chấp nhận định dạng: JPG, PNG, GIF. Kích thước tối đa: 5MB</div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary" <?= empty($availableAccounts) ? 'disabled' : '' ?>>
                <i class="bi bi-check-circle"></i> Tạo HDV
            </button>
            <a href="<?= BASE_URL ?>hdvs" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();

// Hiển thị layout admin với nội dung
view('layouts.AdminLayout', [
    'title' => $title ?? 'Thêm Hướng dẫn viên mới - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Thêm Hướng dẫn viên mới',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>

