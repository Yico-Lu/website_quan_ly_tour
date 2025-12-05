<?php
// Bắt đầu capture nội dung
ob_start();
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marked-alt mr-2"></i>
                        Danh sách Tour được phân công
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($tours)): ?>
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Thông báo!</h5>
                            Hiện tại bạn chưa được phân công tour nào.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($tours as $tour): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <!-- Ảnh tour -->
                                        <?php if (!empty($tour->anh_tour)): ?>
                                            <img src="<?= asset($tour->anh_tour) ?>"
                                                 class="card-img-top"
                                                 alt="<?= htmlspecialchars($tour->ten_tour) ?>"
                                                 style="height: 200px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                 style="height: 200px;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        <?php endif; ?>

                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">
                                                <?= htmlspecialchars($tour->ten_tour) ?>
                                            </h5>

                                            <p class="card-text text-muted">
                                                <i class="fas fa-tag"></i>
                                                <?= htmlspecialchars($tour->ten_danh_muc ?? 'Chưa phân loại') ?>
                                            </p>

                                            <p class="card-text">
                                                <?= htmlspecialchars(substr($tour->mo_ta, 0, 100)) ?>...
                                            </p>

                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge <?= $tour->getTrangThaiBadgeClass() ?>">
                                                        <?= $tour->getTrangThai() ?>
                                                    </span>
                                                    <strong class="text-primary">
                                                        <?= $tour->formatGia() ?>
                                                    </strong>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <a href="<?= BASE_URL ?>guide/show/<?= $tour->id ?>"
                                                       class="btn btn-primary btn-sm flex-fill">
                                                        <i class="fas fa-eye"></i> Xem chi tiết
                                                    </a>
                                                    <!-- TODO: Thêm các nút khác như bắt đầu tour, báo cáo, etc. -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Lấy nội dung đã capture
$content = ob_get_clean();

// Truyền vào layout
view('layouts.GuideLayout', [
    'title' => $title ?? 'Tour được phân công',
    'pageTitle' => $pageTitle ?? 'Tour được phân công',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'currentUser' => $currentUser ?? null,
]);
?>