<?php
ob_start();
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Thông tin Hướng dẫn viên</h3>
                <div class="card-tools">
                    <a href="<?= BASE_URL ?>hdvs/edit/<?= $hdv->id ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>
                    <a href="<?= BASE_URL ?>hdvs" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <?php if ($hdv->anh_dai_dien): ?>
                            <img src="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($hdv->anh_dai_dien) ?>" 
                                 alt="<?= htmlspecialchars($hdv->ho_ten) ?>" 
                                 class="img-thumbnail" 
                                 style="max-width: 200px; max-height: 200px;">
                        <?php else: ?>
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 200px; height: 200px;">
                                <i class="bi bi-person" style="font-size: 5rem;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <h4><?= htmlspecialchars($hdv->ho_ten) ?></h4>
                        <p class="text-muted mb-2">
                            <i class="bi bi-envelope"></i> <?= htmlspecialchars($hdv->email) ?>
                        </p>
                        <p class="mb-2">
                            <span class="badge <?= $hdv->getNhomBadgeClass() ?> fs-6">
                                <?= $hdv->getNhomName() ?>
                            </span>
                            <span class="badge <?= $hdv->getTrangThaiBadgeClass() ?> fs-6 ms-2">
                                <?= $hdv->getTrangThaiName() ?>
                            </span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Mã HDV:</strong>
                    </div>
                    <div class="col-sm-9">
                        #<?= $hdv->id ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Ngày sinh:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?= $hdv->ngay_sinh ? date('d/m/Y', strtotime($hdv->ngay_sinh)) : 'Chưa cập nhật' ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Liên hệ:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?= htmlspecialchars($hdv->lien_he ?? 'Chưa cập nhật') ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Chuyên môn:</strong>
                    </div>
                    <div class="col-sm-9">
                        <div class="bg-light p-3 rounded">
                            <?= nl2br(htmlspecialchars($hdv->chuyen_mon)) ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Số booking:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge bg-info fs-6"><?= $hdv->getSoLuongBooking() ?> booking</span>
                    </div>
                </div>
            </div>
        </div>
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
                        <?= date('d/m/Y H:i', strtotime($hdv->ngay_tao)) ?>
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Trạng thái:</strong><br>
                    <span class="badge <?= $hdv->getTrangThaiBadgeClass() ?>">
                        <?= $hdv->getTrangThaiName() ?>
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
                    <a href="<?= BASE_URL ?>hdvs/edit/<?= $hdv->id ?>" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>hdvs/delete"
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa HDV này?')">
                        <input type="hidden" name="id" value="<?= $hdv->id ?>">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Xóa HDV
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
    'title' => $title ?? 'Chi tiết Hướng dẫn viên - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Hướng dẫn viên',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>

