<?php
    ob_start();
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa tài khoản</h3>
    </div>

    <form method="POST" action="<?= BASE_URL ?>accounts/update">
        <input type="hidden" name="id" value="<?= $user->id ?>">
        <div class="card-body">
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="form-group mb-3">
                <label for="name">Họ tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?= htmlspecialchars($old['name'] ?? $user->name) ?>"
                       placeholder="Nhập họ tên" required>
            </div>

            <div class="form-group mb-3">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($old['email'] ?? $user->email) ?>"
                       placeholder="Nhập địa chỉ email" required>
            </div>

            <div class="form-group mb-3">
                <label for="role">Vai trò <span class="text-danger">*</span></label>
                <select class="form-control" id="role" name="role" required>
                    <option value="huong_dan_vien" <?= ($old['role'] ?? $user->role) === 'huong_dan_vien' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                    <option value="admin" <?= ($old['role'] ?? $user->role) === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="status" name="status"
                           <?= isset($old['status']) ? ($old['status'] ? 'checked' : '') : ($user->status ? 'checked' : '') ?>>
                    <label class="form-check-label" for="status">
                        Tài khoản hoạt động
                    </label>
                </div>
            </div>

            <hr>
            <h5>Đổi mật khẩu</h5>
            <p class="text-muted">Để trống nếu không muốn đổi mật khẩu</p>

            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="change_password" name="change_password"
                           onchange="togglePasswordFields()">
                    <label class="form-check-label" for="change_password">
                        Đổi mật khẩu
                    </label>
                </div>
            </div>

            <div class="form-group mb-3" id="password_fields" style="display: none;">
                <label for="new_password">Mật khẩu mới <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="new_password" name="new_password"
                       placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)">
            </div>

            <div class="form-group mb-3" id="confirm_fields" style="display: none;">
                <label for="confirm_password">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                       placeholder="Nhập lại mật khẩu mới">
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Cập nhật tài khoản</button>
            <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary ml-2">Hủy</a>
        </div>
    </form>
</div>

<script>
function togglePasswordFields() {
    const changePassword = document.getElementById('change_password');
    const passwordFields = document.getElementById('password_fields');
    const confirmFields = document.getElementById('confirm_fields');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    if (changePassword.checked) {
        passwordFields.style.display = 'block';
        confirmFields.style.display = 'block';
        newPassword.required = true;
        confirmPassword.required = true;
    } else {
        passwordFields.style.display = 'none';
        confirmFields.style.display = 'none';
        newPassword.required = false;
        confirmPassword.required = false;
        newPassword.value = '';
        confirmPassword.value = '';
    }
}
</script>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Chỉnh sửa tài khoản - Quản lý tài khoản',
    'pageTitle' => $pageTitle ?? 'Chỉnh sửa tài khoản',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>
