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
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th style="width: 150px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $index => $user): ?>
                            <tr class="align-middle">
                                <td><?= $index + 1 ?>.</td>
                                <td><?= htmlspecialchars($user->name) ?></td>
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
                                <td><?= $user->ngay_tao ? date('d/m/Y', strtotime($user->ngay_tao)) : 'Chưa cập nhật' ?></td>
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
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')">
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
                            <td colspan="7">
                                <h3>Chưa có tài khoản nào trong hệ thống</h3>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-end">
                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
            </ul>
        </div>
    </div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Danh sách tài khoản - Quản lý tài khoản',
    'pageTitle' => $pageTitle ?? 'Danh sách tài khoản',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>

<!-- Hiển thị thông báo -->
<?php if (isset($_SESSION['success'])): ?>
<script>
    alert('<?= addslashes($_SESSION['success']) ?>');
</script>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<script>
    alert('Lỗi: <?= addslashes($_SESSION['error']) ?>');
</script>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

