<?php
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?= $nhatKy ? 'Sửa nhật ký tour' : 'Thêm nhật ký tour' ?>
        </h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>guide/diary/<?= $booking->id ?>" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    <form method="POST" action="<?= BASE_URL ?><?= $nhatKy ? 'guide/diary/update' : 'guide/diary/store' ?>">
        <?php if ($nhatKy): ?>
            <input type="hidden" name="id" value="<?= $nhatKy->id ?>">
        <?php endif; ?>
        <input type="hidden" name="booking_id" value="<?= $booking->id ?>">
        
        <div class="card-body">
            <?php displayFlashMessages(); ?>

            <div class="mb-3">
                <label for="ngay_gio" class="form-label">Ngày giờ <span class="text-danger">*</span></label>
                <input 
                    type="datetime-local" 
                    class="form-control" 
                    id="ngay_gio" 
                    name="ngay_gio" 
                    value="<?= $nhatKy ? date('Y-m-d\TH:i', strtotime($nhatKy->ngay_gio)) : date('Y-m-d\TH:i') ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="noi_dung" class="form-label">Nội dung <span class="text-danger">*</span></label>
                <textarea 
                    class="form-control" 
                    id="noi_dung" 
                    name="noi_dung" 
                    rows="5" 
                    placeholder="Nhập nội dung nhật ký..."
                    required
                ><?= htmlspecialchars($nhatKy->noi_dung ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="danh_gia_hdv" class="form-label">Đánh giá HDV</label>
                <textarea 
                    class="form-control" 
                    id="danh_gia_hdv" 
                    name="danh_gia_hdv" 
                    rows="3" 
                    placeholder="Nhập đánh giá về hướng dẫn viên (nếu có)"
                ><?= htmlspecialchars($nhatKy->danh_gia_hdv ?? '') ?></textarea>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> <?= $nhatKy ? 'Cập nhật' : 'Thêm' ?>
            </button>
            <a href="<?= BASE_URL ?>guide/diary/<?= $booking->id ?>" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
view('layouts.GuideLayout', [
    'title' => $title ?? ($nhatKy ? 'Sửa nhật ký tour' : 'Thêm nhật ký tour'),
    'pageTitle' => $pageTitle ?? ($nhatKy ? 'Sửa nhật ký tour' : 'Thêm nhật ký tour'),
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>

