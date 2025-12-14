<?php
ob_start();
?>

<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa Hướng dẫn viên</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>hdvs/show/<?= $hdv->id ?>" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Xem chi tiết
            </a>
            <a href="<?= BASE_URL ?>hdvs" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form action="<?= BASE_URL ?>hdvs/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $hdv->id ?>">
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
                <!-- Nhóm -->
                <div class="col-md-6 mb-3">
                    <label for="nhom" class="form-label">Nhóm <span class="text-danger">*</span></label>
                    <select class="form-select" id="nhom" name="nhom" required>
                        <option value="">-- Chọn nhóm --</option>
                        <option value="noi_dia" <?= ($old['nhom'] ?? $hdv->nhom) == 'noi_dia' ? 'selected' : '' ?>>Nội địa</option>
                        <option value="quoc_te" <?= ($old['nhom'] ?? $hdv->nhom) == 'quoc_te' ? 'selected' : '' ?>>Quốc tế</option>
                        <option value="yeu_cau" <?= ($old['nhom'] ?? $hdv->nhom) == 'yeu_cau' ? 'selected' : '' ?>>Theo yêu cầu</option>
                    </select>
                </div>

                <!-- Ngày sinh -->
                <div class="col-md-6 mb-3">
                    <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                    <input
                        type="date"
                        class="form-control"
                        id="ngay_sinh"
                        name="ngay_sinh"
                        value="<?= htmlspecialchars($old['ngay_sinh'] ?? ($hdv->ngay_sinh ? date('Y-m-d', strtotime($hdv->ngay_sinh)) : '')) ?>"
                    />
                </div>
            </div>

            <div class="row">
                <!-- Liên hệ -->
                <div class="col-md-6 mb-3">
                    <label for="lien_he" class="form-label">Liên hệ</label>
                    <input
                        type="text"
                        class="form-control"
                        id="lien_he"
                        name="lien_he"
                        value="<?= htmlspecialchars($old['lien_he'] ?? $hdv->lien_he) ?>"
                        placeholder="Số điện thoại, email..."
                    />
                </div>

                <!-- Ảnh đại diện hiện tại -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ảnh đại diện hiện tại</label>
                    <div>
                        <?php if ($hdv->anh_dai_dien): ?>
                            <img src="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($hdv->anh_dai_dien) ?>" 
                                 alt="<?= htmlspecialchars($hdv->ho_ten) ?>" 
                                 class="img-thumbnail" 
                                 style="max-width: 150px; max-height: 150px;">
                        <?php else: ?>
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px;">
                                <i class="bi bi-person" style="font-size: 3rem;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
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
                ><?= htmlspecialchars($old['chuyen_mon'] ?? $hdv->chuyen_mon) ?></textarea>
            </div>

            <!-- Ảnh đại diện mới -->
            <div class="mb-3">
                <label for="anh_dai_dien" class="form-label">Thay đổi ảnh đại diện</label>
                <input
                    type="file"
                    class="form-control"
                    id="anh_dai_dien"
                    name="anh_dai_dien"
                    accept="image/*"
                />
            </div>

            <!-- Thông tin bổ sung -->
            <div class="row">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6><i class="bi bi-info-circle"></i> Thông tin hệ thống</h6>
                        <small class="text-muted">
                            <strong>Ngày tạo:</strong> <?= date('d/m/Y H:i', strtotime($hdv->ngay_tao)) ?><br>
                            <strong>Trạng thái:</strong>
                            <span class="badge <?= $hdv->getTrangThaiBadgeClass() ?>">
                                <?= $hdv->getTrangThaiName() ?>
                            </span>
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6><i class="bi bi-bar-chart"></i> Thống kê</h6>
                        <small class="text-muted">
                            <strong>Số booking:</strong> <span class="badge bg-primary"><?= $hdv->getSoLuongBooking() ?></span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-circle"></i> Cập nhật HDV
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
    'title' => $title ?? 'Chỉnh sửa Hướng dẫn viên - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Chỉnh sửa Hướng dẫn viên',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>

