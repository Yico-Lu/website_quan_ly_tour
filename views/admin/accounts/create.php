<?php
    ob_start();
?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Thêm tài khoản mới</h3>
    </div>

    <form method="POST" action="<?= BASE_URL ?>accounts/store">
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
                       value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                       placeholder="Nhập họ tên" required>
            </div>

            <div class="form-group mb-3">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       placeholder="Nhập địa chỉ email" required>
            </div>

            <div class="form-group mb-3">
                <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" required>
            </div>

            <div class="form-group mb-3">
                <label for="confirm_password">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                       placeholder="Nhập lại mật khẩu" required>
            </div>

            <div class="form-group mb-3">
                <label for="role">Vai trò <span class="text-danger">*</span></label>
                <select class="form-control" id="role" name="role" required>
                    <option value="huong_dan_vien" <?= ($old['role'] ?? '') === 'huong_dan_vien' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                    <option value="admin" <?= ($old['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="status" name="status"
                           <?= isset($old['status']) ? 'checked' : 'checked' ?>>
                    <label class="form-check-label" for="status">
                        Tài khoản hoạt động
                    </label>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
            <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary ml-2">Hủy</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Thêm tài khoản mới - Quản lý tài khoản',
    'pageTitle' => $pageTitle ?? 'Thêm tài khoản mới',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>

