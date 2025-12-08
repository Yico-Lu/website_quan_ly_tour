<?php
    ob_start();
?>

<!-- Thông tin cơ bản -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-info-circle"></i> Thông tin Tour
        </h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>tours/edit/<?= $tour->id ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i> Sửa
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h4><?= htmlspecialchars($tour->ten_tour) ?></h4>
                <p class="text-muted mb-3"><?= htmlspecialchars($tour->mo_ta) ?></p>

                <div class="row">
                    <div class="col-md-6">
                        <strong>Danh mục:</strong><br>
                        <span class="badge bg-primary"><?= htmlspecialchars($tour->ten_danh_muc ?? 'Chưa phân loại') ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Giá:</strong><br>
                        <span class="text-success font-weight-bold"><?= $tour->formatGia() ?></span>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <strong>Trạng thái:</strong><br>
                        <span class="badge <?= $tour->getTrangThaiBadgeClass() ?>">
                            <?= $tour->getTrangThai() ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Ngày tạo:</strong><br>
                        <?= date('d/m/Y H:i', strtotime($tour->ngay_tao)) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-center">
                <?php if(!empty($tour->anh_tour)): ?>
                    <img src="<?= asset($tour->anh_tour) ?>"
                         alt="Ảnh tour"
                         class="img-fluid rounded shadow"
                         style="max-height: 200px;">
                <?php else: ?>
                    <div class="bg-light rounded p-4">
                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Chưa có ảnh</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chính sách tour -->
<div class="card card-info card-outline mt-4">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-file-text"></i> Chính sách Tour
        </h5>
    </div>
    <div class="card-body">
        <?php
        $chinhSach = $tour->getChinhSach();
        if(!empty($chinhSach)):
            foreach($chinhSach as $cs):
        ?>
        <div class="border rounded p-3 mb-3 bg-light">
            <h6 class="text-primary mb-2">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($cs['ten_chinh_sach']) ?>
            </h6>
            <p class="mb-0 text-muted">
                <?= nl2br(htmlspecialchars($cs['noi_dung'])) ?>
            </p>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p class="text-muted mb-0">
            <i class="bi bi-info-circle"></i> Chưa có chính sách cho tour này
        </p>
        <?php endif; ?>
    </div>
</div>

<!-- Lịch trình tour -->
<div class="card card-success card-outline mt-4">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-calendar-event"></i> Lịch trình Tour
        </h5>
    </div>
    <div class="card-body">
        <?php
        $lichTrinh = $tour->getLichTrinh();
        if(!empty($lichTrinh)):
            foreach($lichTrinh as $lt):
        ?>
        <div class="border rounded p-3 mb-3 bg-light">
            <h6 class="text-success mb-3">
                <i class="bi bi-calendar-day"></i> Ngày <?= htmlspecialchars($lt['ngay'] ?? '') ?>
            </h6>
            <?php if(!empty($lt['diem_tham_quan'])): ?>
            <div class="mb-2">
                <strong><i class="bi bi-geo-alt"></i> Điểm tham quan:</strong><br>
                <span class="text-muted"><?= htmlspecialchars($lt['diem_tham_quan']) ?></span>
            </div>
            <?php endif; ?>
            <?php if(!empty($lt['hoat_dong'])): ?>
            <div class="mb-0">
                <strong><i class="bi bi-list-check"></i> Hoạt động:</strong><br>
                <p class="text-muted mb-0">
                <?= nl2br(htmlspecialchars($lt['hoat_dong'])) ?>
            </p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p class="text-muted mb-0">
            <i class="bi bi-calendar-x"></i> Chưa có lịch trình cho tour này
        </p>
        <?php endif; ?>
    </div>
</div>

<!-- Nhà cung cấp -->
<div class="card card-warning card-outline mt-4">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-building"></i> Nhà cung cấp
        </h5>
    </div>
    <div class="card-body">
        <?php
        $nhaCungCap = $tour->getNhaCungCap();
        if(!empty($nhaCungCap)):
        ?>
        <div class="row">
            <?php foreach($nhaCungCap as $ncc): ?>
            <div class="col-md-6 mb-3">
                <div class="card h-100 border">
                    <div class="card-body">
                        <h6 class="card-title text-warning">
                            <i class="bi bi-building"></i> <?= htmlspecialchars($ncc['ten_nha_cung_cap']) ?>
                        </h6>
                        <p class="card-text">
                            <span class="badge bg-secondary mb-2">
                                <?php
                                $loaiLabels = [
                                    'hang_khong' => 'Hàng không',
                                    'khach_san' => 'Khách sạn',
                                    'nha_hang' => 'Nhà hàng',
                                    'phuong_tien' => 'Phương tiện',
                                    'hdv' => 'Hướng dẫn viên',
                                    'khac' => 'Khác'
                                ];
                                echo $loaiLabels[$ncc['loai']] ?? $ncc['loai'];
                                ?>
                            </span>
                            <?php if(!empty($ncc['lien_he'])): ?>
                            <br><small class="text-muted">
                                <i class="bi bi-telephone"></i> <?= htmlspecialchars($ncc['lien_he']) ?>
                            </small>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-muted mb-0">
            <i class="bi bi-building-x"></i> Chưa có nhà cung cấp cho tour này
        </p>
        <?php endif; ?>
    </div>
</div>

<!-- Ảnh chi tiết -->
<div class="card card-secondary card-outline mt-4">
    <div class="card-header">
        <h5 class="card-title">
            <i class="bi bi-images"></i> Ảnh chi tiết Tour
        </h5>
    </div>
    <div class="card-body">
        <?php
        $anhChiTiet = $tour->getAnhChiTiet();
        if(!empty($anhChiTiet)):
        ?>
        <div class="row">
            <?php foreach($anhChiTiet as $anh): ?>
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="<?= asset($anh['duong_dan']) ?>"
                         class="card-img-top"
                         alt="Ảnh tour"
                         style="height: 150px; object-fit: cover;">
                    <div class="card-body p-2">
                        <p class="card-text small text-muted mb-0">
                            <?= htmlspecialchars(basename($anh['duong_dan'])) ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-4">
            <i class="bi bi-images text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2">Chưa có ảnh chi tiết cho tour này</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Nút điều hướng -->
<div class="mt-4">
    <a href="<?= BASE_URL ?>tours" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>
    <a href="<?= BASE_URL ?>tours/edit/<?= $tour->id ?>" class="btn btn-warning">
        <i class="bi bi-pencil"></i> Sửa tour
    </a>
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


