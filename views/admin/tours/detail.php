<?php ob_start(); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Chi tiết Tour</h3>
        <a href="<?= BASE_URL ?>tours" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>ID:</strong> <?= $tour->id ?></p>
                <p><strong>Tên Tour:</strong> <?= htmlspecialchars($tour->ten_tour) ?></p>
                <p><strong>Danh mục:</strong> <?= htmlspecialchars($tour->ten_danh_muc ?? 'Chưa phân loại') ?></p>
                <p><strong>Giá:</strong> <?= $tour->formatGia() ?></p>
                <p><strong>Trạng thái:</strong>
                    <span class="badge <?= $tour->getTrangThaiBadgeClass() ?>">
                        <?= $tour->getTrangThai() ?>
                    </span>
                </p>
                <p><strong>Ngày tạo:</strong> <?= date('d/m/Y', strtotime($tour->ngay_tao)) ?></p>
            </div>
            <!-- <div class="col-md-6 text-center">
                <p><strong>Ảnh Tour:</strong></p>
                <?php if (!empty($tour->anh_tour)): ?>
                    <img src="<?= BASE_URL ?>uploads/<?= $tour->anh_tour ?>" class="img-fluid border rounded" style="max-width: 300px;">
                <?php else: ?>
                    <p class="text-muted fst-italic">Không có ảnh</p>
                <?php endif; ?>
            </div> -->
        </div>

        <hr>
        <p><strong>Mô tả chi tiết:</strong></p>
        <div class="border rounded p-3" style="background: #fafafa;">
            <?= nl2br(htmlspecialchars($tour->mo_ta)) ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Chi tiết Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>
