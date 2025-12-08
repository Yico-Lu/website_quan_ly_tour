<?php
ob_start();
?>

<div class="row">
    <!-- Thông tin cơ bản -->
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Thông tin tài khoản</h3>
                <div class="card-tools">
                    <a href="<?= BASE_URL ?>accounts/edit/<?= $user->id ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                    <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>ID tài khoản:</strong>
                    </div>
                    <div class="col-sm-9">
                        #<?= $user->id ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Họ tên:</strong>
                    </div>
                    <div class="col-sm-9">
                        <strong><?= htmlspecialchars($user->name) ?></strong>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?= htmlspecialchars($user->email) ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Số điện thoại:</strong>
                    </div>
                    <div class="col-sm-9">
                        <?= htmlspecialchars($user->sdt ?? 'Chưa cập nhật') ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Vai trò (Phân quyền):</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge <?= $user->getRoleBadgeClass() ?> fs-6">
                            <?= $user->getRoleName() ?>
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Trạng thái:</strong>
                    </div>
                    <div class="col-sm-9">
                        <span class="badge <?= $user->getStatusBadgeClass() ?> fs-6">
                            <?= $user->getStatusName() ?>
                        </span>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Ngày tạo:</strong>
                    </div>
                    <div class="col-sm-9">
                        <i class="bi bi-calendar-plus"></i>
                        <?= $user->ngay_tao ? date('d/m/Y H:i:s', strtotime($user->ngay_tao)) : 'Chưa cập nhật' ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <strong>Ngày cập nhật:</strong>
                    </div>
                    <div class="col-sm-9">
                        <i class="bi bi-calendar-check"></i>
                        <?= $user->ngay_cap_nhat ? date('d/m/Y H:i:s', strtotime($user->ngay_cap_nhat)) : 'Chưa cập nhật' ?>
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

    <!-- Thống kê và thông tin bổ sung -->
    <div class="col-md-4">
        <div class="card card-info mb-3">
            <div class="card-header">
                <h3 class="card-title">Thống kê</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <i class="bi bi-person-circle" style="font-size: 3rem; color: #17a2b8;"></i>
                    <h4 class="mt-2"><?= htmlspecialchars($user->name) ?></h4>
                    <p class="text-muted">
                        <span class="badge <?= $user->getRoleBadgeClass() ?>">
                            <?= $user->getRoleName() ?>
                        </span>
                    </p>
                    <p class="text-muted">
                        <span class="badge <?= $user->getStatusBadgeClass() ?>">
                            <?= $user->getStatusName() ?>
                        </span>
                    </p>
                </div>

                <hr>

                <div class="row text-center">
                    <div class="col-6">
                        <div class="description-block border-end">
                            <h5 class="description-header text-primary">ID</h5>
                            <span class="description-text">#<?= $user->id ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="description-block">
                            <h5 class="description-header text-success">
                                <?= $user->ngay_tao ? date('m/Y', strtotime($user->ngay_tao)) : 'N/A' ?>
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
                <?php endif; ?>

                <?php if (!$user->status): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i>
                        <strong>Tài khoản tạm ngưng:</strong> Không thể đăng nhập vào hệ thống.
                    </div>
                <?php endif; ?>

                <div class="alert alert-secondary">
                    <i class="bi bi-info-circle"></i>
                    <strong>Thông tin:</strong><br>
                    <small>
                        Email: <?= htmlspecialchars($user->email) ?><br>
                        Số điện thoại: <?= htmlspecialchars($user->sdt ?? 'Chưa cập nhật') ?><br>
                        Phân quyền: <?= $user->getRoleName() ?><br>
                        Trạng thái: <?= $user->getStatusName() ?>
                    </small>
                </div>
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
