<?php
ob_start();
?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Danh sách tài khoản</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>accounts/create" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle"></i> Thêm tài khoản mới
            </a>
        </div>
    </div>
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
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Vai trò (Phân quyền)</th>
                    <th>Trạng thái</th>
                    <th style="width: 150px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $index => $user): ?>
                        <tr class="align-middle">
                            <td><?= $index + 1 ?>.</td>
                            <td>#<?= $user->id ?></td>
                            <td><strong><?= htmlspecialchars($user->name) ?></strong></td>
                            <td><?= htmlspecialchars($user->email) ?></td>
                            <td>
                                <span class="badge <?= $user->getRoleBadgeClass() ?>">
                                    <?= $user->getRoleName() ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $user->getStatusBadgeClass() ?>">
                                    <?= $user->getStatusName() ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm gap-1">
                                    <a href="<?= BASE_URL ?>accounts/show/<?= $user->id ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>accounts/edit/<?= $user->id ?>" class="btn btn-warning btn-sm" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($user->id != getCurrentUser()->id): ?>
                                        <form method="POST" action="<?= BASE_URL ?>accounts/delete"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')"
                                              class="d-inline">
                                            <input type="hidden" name="id" value="<?= $user->id ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-person-x text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Chưa có tài khoản nào trong hệ thống</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Danh sách tài khoản - Quản lý tài khoản',
    'pageTitle' => $pageTitle ?? 'Danh sách tài khoản',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>
