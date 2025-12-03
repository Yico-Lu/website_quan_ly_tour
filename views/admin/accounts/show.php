<?php
    ob_start();
?>

<div class="row">
    <!-- Thông tin cơ bản -->
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thông tin tài khoản</h3>
                <div class="card-tools">
                    <a href="<?= BASE_URL ?>accounts/edit/<?= $user->id ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Họ tên:</label>
                            <p class="form-control-plaintext"><strong><?= htmlspecialchars($user->name) ?></strong></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email:</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($user->email) ?></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Vai trò:</label>
                            <p class="form-control-plaintext">
                                <span class="badge <?= $user->getRoleBadgeClass() ?>">
                                    <?= $user->getRoleName() ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Trạng thái:</label>
                            <p class="form-control-plaintext">
                                <span class="badge <?= $user->getStatusBadgeClass() ?>">
                                    <?= $user->getStatusName() ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>ID tài khoản:</label>
                            <p class="form-control-plaintext"><?= $user->id ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Ngày tạo:</label>
                            <p class="form-control-plaintext">
                                <?= $user->ngay_tao ? date('d/m/Y H:i:s', strtotime($user->ngay_tao)) : 'Chưa cập nhật' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Thống kê</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="bi bi-person-circle" style="font-size: 3rem; color: #17a2b8;"></i>
                    <h4 class="mt-2">Tài khoản <?= $user->getRoleName() ?></h4>
                    <p class="text-muted">
                        <?= $user->status ? 'Đang hoạt động' : 'Tạm ngưng' ?>
                    </p>
                </div>

                <hr>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="description-block border-right">
                            <h5 class="description-header text-primary">ID</h5>
                            <span class="description-text">#<?= $user->id ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <h5 class="description-header text-success">
                                <?= date('m/Y', strtotime($user->ngay_tao ?? 'now')) ?>
                            </h5>
                            <span class="description-text">THÁNG TẠO</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin bổ sung -->
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">Lưu ý</h3>
            </div>
            <div class="card-body">
                <?php if ($user->isAdmin()): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Tài khoản Admin:</strong> Có quyền quản lý toàn bộ hệ thống.
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Tài khoản Hướng dẫn viên:</strong> Có quyền xem và quản lý tour.
                    </div>
                <?php endif; ?>

                <?php if (!$user->status): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i>
                        <strong>Tài khoản tạm ngưng:</strong> Không thể đăng nhập vào hệ thống.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Chi tiết tài khoản - Quản lý tài khoản',
    'pageTitle' => $pageTitle ?? 'Chi tiết tài khoản',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>
