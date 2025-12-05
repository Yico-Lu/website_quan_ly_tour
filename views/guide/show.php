<?php
// Bắt đầu capture nội dung
ob_start();
?>

<div class="container-fluid">
    <!-- Thông tin cơ bản tour -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        Thông tin Tour: <?= htmlspecialchars($tour->ten_tour) ?>
                    </h3>
                    <div class="card-tools">
                        <a href="<?= BASE_URL ?>guide" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Ảnh tour -->
                        <div class="col-md-4">
                            <?php if (!empty($tour->anh_tour)): ?>
                                <img src="<?= asset($tour->anh_tour) ?>"
                                     class="img-fluid rounded"
                                     alt="<?= htmlspecialchars($tour->ten_tour) ?>">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Thông tin chi tiết -->
                        <div class="col-md-8">
                            <dl class="row">
                                <dt class="col-sm-3">Tên tour:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($tour->ten_tour) ?></dd>

                                <dt class="col-sm-3">Danh mục:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge badge-primary">
                                        <?= htmlspecialchars($tour->ten_danh_muc ?? 'Chưa phân loại') ?>
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Giá tour:</dt>
                                <dd class="col-sm-9">
                                    <strong class="text-success h5">
                                        <?= $tour->formatGia() ?>
                                    </strong>
                                </dd>

                                <dt class="col-sm-3">Trạng thái:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge <?= $tour->getTrangThaiBadgeClass() ?>">
                                        <?= $tour->getTrangThai() ?>
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Ngày tạo:</dt>
                                <dd class="col-sm-9">
                                    <?= date('d/m/Y H:i', strtotime($tour->ngay_tao)) ?>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <!-- Mô tả tour -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Mô tả tour</h5>
                            <div class="border p-3 rounded">
                                <?= nl2br(htmlspecialchars($tour->mo_ta)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch trình tour -->
    <?php if (!empty($tour->getLichTrinh())): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Lịch trình Tour
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($tour->getLichTrinh() as $lichTrinh): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h4 class="timeline-title">
                                    Ngày <?= htmlspecialchars($lichTrinh['ngay'] ?? 'N/A') ?>
                                </h4>
                                <p class="text-muted">
                                    <?= nl2br(htmlspecialchars($lichTrinh['diem_tham_quan'] ?? '')) ?>
                                </p>
                                <?php if (!empty($lichTrinh['hoat_dong'])): ?>
                                <p><strong>Hoạt động:</strong> <?= htmlspecialchars($lichTrinh['hoat_dong']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Chính sách và nhà cung cấp -->
    <div class="row mt-4">
        <?php if (!empty($tour->getChinhSach())): ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-contract mr-2"></i>
                        Chính sách Tour
                    </h3>
                </div>
                <div class="card-body">
                    <?php foreach ($tour->getChinhSach() as $chinhSach): ?>
                    <div class="mb-3">
                        <h6><?= htmlspecialchars($chinhSach['ten_chinh_sach']) ?></h6>
                        <p class="text-muted">
                            <?= nl2br(htmlspecialchars($chinhSach['noi_dung'])) ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($tour->getNhaCungCap())): ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-building mr-2"></i>
                        Nhà cung cấp
                    </h3>
                </div>
                <div class="card-body">
                    <?php foreach ($tour->getNhaCungCap() as $ncc): ?>
                    <div class="mb-3">
                        <h6>
                            <i class="fas fa-building mr-1"></i>
                            <?= htmlspecialchars($ncc['ten_nha_cung_cap']) ?>
                        </h6>
                        <p class="mb-1">
                            <strong>Loại:</strong> <?= htmlspecialchars($ncc['loai']) ?>
                        </p>
                        <?php if (!empty($ncc['lien_he'])): ?>
                        <p class="mb-0">
                            <strong>Liên hệ:</strong> <?= htmlspecialchars($ncc['lien_he']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Ảnh chi tiết tour -->
    <?php if (!empty($tour->getAnhChiTiet())): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-images mr-2"></i>
                        Ảnh chi tiết Tour
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($tour->getAnhChiTiet() as $anh): ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card h-100">
                                <img src="<?= asset($anh['duong_dan']) ?>"
                                     class="card-img-top"
                                     alt="Ảnh tour"
                                     style="height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Các nút hành động -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center gap-2">
                        <!-- Các nút chức năng vận hành tour -->
                        <a href="<?= BASE_URL ?>guide/customers/<?= $tour->id ?>" class="btn btn-info">
                            <i class="fas fa-users"></i> Quản lý Khách hàng
                        </a>
                        <button class="btn btn-success" disabled>
                            <i class="fas fa-play"></i> Bắt đầu Tour
                        </button>
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
    'title' => $title ?? 'Chi tiết Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'currentUser' => $currentUser ?? null,
]);
?>