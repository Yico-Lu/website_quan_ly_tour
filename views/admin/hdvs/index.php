<?php
ob_start();
?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Danh sách Hướng dẫn viên</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>hdvs/create" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Thêm HDV mới
            </a>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table class="table table-bordered table-hover text-center">
            <thead class="table-light">
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Ảnh đại diện</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Ngày sinh</th>
                    <th>Nhóm</th>
                    <th>Chuyên môn</th>
                    <th>Liên hệ</th>
                    <th>Trạng thái</th>
                    <th style="width: 180px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($hdvs)): ?>
                    <?php foreach ($hdvs as $index => $hdv): ?>
                        <tr class="align-middle">
                            <td><?= $index + 1 ?>.</td>
                            <td>
                                <?php if ($hdv->anh_dai_dien): ?>
                                    <img src="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($hdv->anh_dai_dien) ?>" 
                                         alt="<?= htmlspecialchars($hdv->ho_ten) ?>" 
                                         class="img-thumbnail" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($hdv->ho_ten) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($hdv->email) ?></td>
                            <td>
                                <?= $hdv->ngay_sinh ? date('d/m/Y', strtotime($hdv->ngay_sinh)) : 'Chưa cập nhật' ?>
                            </td>
                            <td>
                                <span class="badge <?= $hdv->getNhomBadgeClass() ?>">
                                    <?= $hdv->getNhomName() ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= htmlspecialchars(substr($hdv->chuyen_mon, 0, 30)) ?>
                                    <?= strlen($hdv->chuyen_mon) > 30 ? '...' : '' ?>
                                </small>
                            </td>
                            <td><?= htmlspecialchars($hdv->lien_he ?? 'Chưa cập nhật') ?></td>
                            <td>
                                <span class="badge <?= $hdv->getTrangThaiBadgeClass() ?>">
                                    <?= $hdv->getTrangThaiName() ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm gap-1">
                                    <a href="<?= BASE_URL ?>hdvs/show/<?= $hdv->id ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>hdvs/edit/<?= $hdv->id ?>" class="btn btn-warning btn-sm" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>hdvs/delete" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa HDV này?')">
                                        <input type="hidden" name="id" value="<?= $hdv->id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="bi bi-person-x text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Chưa có hướng dẫn viên nào</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>

<?php
$content = ob_get_clean();

// Hiển thị layout admin với nội dung
view('layouts.AdminLayout', [
    'title' => $title ?? 'Danh sách Hướng dẫn viên - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Danh sách Hướng dẫn viên',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>

