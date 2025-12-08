<?php
ob_start();
?>

<div class="card card-warning card-outline">
    <div class="card-header">
        <h3 class="card-title">Chỉnh sửa tài khoản</h3>
        <div class="card-tools">
            <a href="<?= BASE_URL ?>accounts/show/<?= $user->id ?>" class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Xem chi tiết
            </a>
            <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <form method="POST" action="<?= BASE_URL ?>accounts/update">
        <input type="hidden" name="id" value="<?= $user->id ?>">
        <div class="card-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <strong>Lỗi:</strong> <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

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
                           value="<?= htmlspecialchars($old['name'] ?? $user->name) ?>"
                           placeholder="Nhập họ tên" required>
                    <div class="form-text">Tên đầy đủ của người dùng</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?= htmlspecialchars($old['email'] ?? $user->email) ?>"
                           placeholder="Nhập địa chỉ email" required>
                    <div class="form-text">Email sẽ được dùng để đăng nhập</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sdt" class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" id="sdt" name="sdt"
                           value="<?= htmlspecialchars($old['sdt'] ?? ($user->sdt ?? '')) ?>"
                           placeholder="Nhập số điện thoại">
                    <div class="form-text">Ví dụ: 0912345678</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label">Vai trò (Phân quyền) <span class="text-danger">*</span></label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="huong_dan_vien" <?= ($old['role'] ?? $user->role) === 'huong_dan_vien' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                        <option value="admin" <?= ($old['role'] ?? $user->role) === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                    </select>
                    <div class="form-text">Quyền truy cập của tài khoản trong hệ thống</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Trạng thái</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" id="status" name="status"
                               <?= isset($old['status']) ? ($old['status'] ? 'checked' : '') : ($user->status ? 'checked' : '') ?>>
                        <label class="form-check-label" for="status">
                            Tài khoản hoạt động
                        </label>
                    </div>
                    <div class="form-text">Bỏ check nếu muốn tạm ngưng tài khoản</div>
                </div>
            </div>

            <hr>
            <h5><i class="bi bi-key"></i> Đổi mật khẩu</h5>
            <p class="text-muted">Để trống nếu không muốn đổi mật khẩu</p>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="change_password" name="change_password"
                               onchange="togglePasswordFields()">
                        <label class="form-check-label" for="change_password">
                            Đổi mật khẩu
                        </label>
                    </div>
                </div>
            </div>

            <div class="row" id="password_fields" style="display: none;">
                <div class="col-md-6 mb-3">
                    <label for="new_password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="new_password" name="new_password"
                           placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)">
                    <div class="form-text">Mật khẩu tối thiểu 6 ký tự</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                           placeholder="Nhập lại mật khẩu mới">
                    <div class="form-text">Nhập lại mật khẩu để xác nhận</div>
                </div>
            </div>

            <!-- Thông tin hệ thống -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded">
                        <h6><i class="bi bi-info-circle"></i> Thông tin hệ thống</h6>
                        <small class="text-muted">
                            <strong>ID tài khoản:</strong> #<?= $user->id ?><br>
                            <strong>Ngày tạo:</strong> <?= $user->ngay_tao ? date('d/m/Y H:i', strtotime($user->ngay_tao)) : 'Chưa cập nhật' ?><br>
                            <strong>Ngày cập nhật:</strong> <?= $user->ngay_cap_nhat ? date('d/m/Y H:i', strtotime($user->ngay_cap_nhat)) : 'Chưa cập nhật' ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                <i class="bi bi-check-circle"></i> Cập nhật tài khoản
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
    'title' => $title ?? 'Chỉnh sửa tài khoản - Quản lý tài khoản',
    'pageTitle' => $pageTitle ?? 'Chỉnh sửa tài khoản',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js', 'js/account-edit.js'],
]);
?>
