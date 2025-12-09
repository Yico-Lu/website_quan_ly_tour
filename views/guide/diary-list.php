<?php
ob_start();
?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Nhật ký Tour: <?= htmlspecialchars($booking->ten_tour) ?></h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>guide/booking/<?= $booking->id ?>" class="btn btn-info btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <a href="<?= BASE_URL ?>guide/diary/create/<?= $booking->id ?>" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Thêm nhật ký
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php displayFlashMessages(); ?>

        <?php if (!empty($nhatKys)): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Ngày giờ</th>
                            <th>Nội dung</th>
                            <th>Đánh giá HDV</th>
                            <th style="width: 150px">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($nhatKys as $index => $nhatKy): ?>
                            <tr>
                                <td><?= $index + 1 ?>.</td>
                                <td>
                                    <?= $nhatKy->ngay_gio ? date('d/m/Y H:i', strtotime($nhatKy->ngay_gio)) : '' ?>
                                </td>
                                <td>
                                    <?= nl2br(htmlspecialchars($nhatKy->noi_dung)) ?>
                                </td>
                                <td>
                                    <?= nl2br(htmlspecialchars($nhatKy->danh_gia_hdv)) ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm gap-1">
                                        <a href="<?= BASE_URL ?>guide/diary/edit/<?= $nhatKy->id ?>" class="btn btn-warning btn-sm" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="<?= BASE_URL ?>guide/diary/delete" class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhật ký này?')">
                                            <input type="hidden" name="id" value="<?= $nhatKy->id ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Chưa có nhật ký nào cho tour này.
                <a href="<?= BASE_URL ?>guide/diary/create/<?= $booking->id ?>" class="btn btn-sm btn-success mt-2">
                    <i class="bi bi-plus-circle"></i> Thêm nhật ký đầu tiên
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.GuideLayout', [
    'title' => $title ?? 'Nhật ký Tour',
    'pageTitle' => $pageTitle ?? 'Nhật ký Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>

