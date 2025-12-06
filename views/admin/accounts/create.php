<?php
ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Thêm tài khoản mới</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form method="POST" action="<?= BASE_URL ?>accounts/store">
        <div class="card-body">
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>Có lỗi:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                           placeholder="Nhập họ tên" required>
                    <div class="form-text">Tên đầy đủ của người dùng</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                           placeholder="Nhập địa chỉ email" required>
                    <div class="form-text">Email sẽ được dùng để đăng nhập</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sdt" class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" id="sdt" name="sdt"
                           value="<?= htmlspecialchars($old['sdt'] ?? '') ?>"
                           placeholder="Nhập số điện thoại">
                    <div class="form-text">Ví dụ: 0912345678</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" required>
                    <div class="form-text">Mật khẩu tối thiểu 6 ký tự</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                           placeholder="Nhập lại mật khẩu" required>
                    <div class="form-text">Nhập lại mật khẩu để xác nhận</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Vai trò (Phân quyền) <span class="text-danger">*</span></label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="huong_dan_vien" <?= ($old['role'] ?? '') === 'huong_dan_vien' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                        <option value="admin" <?= ($old['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                    </select>
                    <div class="form-text">Quyền truy cập của tài khoản trong hệ thống</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Trạng thái</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="status" name="status" 
                               <?= isset($old['status']) ? ($old['status'] ? 'checked' : '') : 'checked' ?>>
                        <label class="form-check-label" for="status">
                            Tài khoản hoạt động
                        </label>
                    </div>
                    <div class="form-text">Bỏ check nếu muốn tạm ngưng tài khoản</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Thêm tài khoản
            </button>
            <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
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
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>
