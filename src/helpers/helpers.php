<?php

// Hàm xác định đường dẫn tuyệt đối tới file view tương ứng
function view_path(string $view): string
{
    $normalized = str_replace('.', DIRECTORY_SEPARATOR, $view);
    return BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $normalized . '.php';
}

// Hàm xác định đường dẫn tuyệt đối tới file block tương ứng(thành phần layouts)
function block_path(string $block): string
{
    return BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . $block . '.php';
}

// Hàm view: nạp dữ liệu và hiển thị giao diện
function view(string $view, array $data = []): void
{
    $file = view_path($view);

    if (!file_exists($file)) {
        throw new RuntimeException("View '{$view}' not found at {$file}");
    }

    extract($data, EXTR_OVERWRITE); // biến hóa mảng $data thành biến riêng lẻ
    include $file;
}

// Hàm include block: nạp một block từ thư mục blocks(thành phần layouts)
function block(string $block, array $data = []): void
{
    $file = block_path($block);

    if (!file_exists($file)) {
        throw new RuntimeException("Block '{$block}' not found at {$file}");
    }

    extract($data, EXTR_OVERWRITE); // biến hóa mảng $data thành biến riêng lẻ
    include $file;
}

// Tạo đường dẫn tới asset (css/js/images) trong thư mục public(tài nguyên)
function asset(string $path): string
{
    $trimmed = ltrim($path, '/');
    return rtrim(BASE_URL, '/') . '/public/' . $trimmed;
}

// Khởi động session nếu chưa khởi động(session là một cơ chế để lưu trữ dữ liệu trên server)
function startSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Lưu thông tin user vào session sau khi đăng nhập thành công
// @param User $user Đối tượng User cần lưu vào session
function loginUser($user)
{
    startSession();
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_role'] = $user->role;
}

// Đăng xuất: xóa toàn bộ thông tin user khỏi session
function logoutUser()
{
    startSession();
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_role']);
    session_destroy();
}

// Kiểm tra xem user đã đăng nhập chưa
// @return bool true nếu đã đăng nhập, false nếu chưa
function isLoggedIn()
{
    startSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Lấy thông tin user hiện tại từ session
// @return User|null Trả về đối tượng User nếu đã đăng nhập, null nếu chưa
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    startSession();
    return new User([
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role'],
    ]);
}

// Kiểm tra xem user hiện tại có phải là admin không
// @return bool true nếu là admin, false nếu không
function isAdmin()
{
    $user = getCurrentUser();
    return $user && $user->isAdmin();
}

// Kiểm tra xem user hiện tại có phải là hướng dẫn viên không
// @return bool true nếu là hướng dẫn viên, false nếu không
function isGuide()
{
    $user = getCurrentUser();
    return $user && $user->isGuide();
}

// Yêu cầu đăng nhập: nếu chưa đăng nhập thì chuyển hướng về trang login
// @param string $redirectUrl URL chuyển hướng sau khi đăng nhập (mặc định là trang hiện tại)
function requireLogin($redirectUrl = null)
{
    if (!isLoggedIn()) {
        $redirect = $redirectUrl ?: $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . '?act=login&redirect=' . urlencode($redirect));
        exit;
    }
}

// Yêu cầu quyền admin: nếu không phải admin thì chuyển hướng về trang chủ
function requireAdmin()
{
    requireLogin();
    
    if (!isAdmin()) {
        header('Location: ' . BASE_URL);
        exit;
    }
}

// Yêu cầu quyền hướng dẫn viên hoặc admin
function requireGuideOrAdmin()
{
    requireLogin();

    if (!isGuide() && !isAdmin()) {
        header('Location: ' . BASE_URL);
        exit;
    }
}

// Render nội dung view và trả về chuỗi (không echo)
function view_content(string $view, array $data = []): string
{
    ob_start();
    view($view, $data);
    return ob_get_clean();
}

// Hiển thị thông báo thành công/lỗi
function showAlert()
{
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>' . $_SESSION['success'] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>' . $_SESSION['error'] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>';
        unset($_SESSION['error']);
    }
}

// Helper functions cho account views

/**
 * Render thông tin cơ bản của account
 */
function renderAccountBasicInfo($account)
{
    ?>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Tên đăng nhập</label>
                <p class="mb-0"><?= htmlspecialchars($account->ten_dang_nhap) ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Họ tên</label>
                <p class="mb-0"><?= htmlspecialchars($account->ho_ten) ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Email</label>
                <p class="mb-0">
                    <a href="mailto:<?= htmlspecialchars($account->email) ?>" class="text-decoration-none">
                        <?= htmlspecialchars($account->email) ?>
                    </a>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Số điện thoại</label>
                <p class="mb-0">
                    <?php if ($account->sdt): ?>
                        <a href="tel:<?= htmlspecialchars($account->sdt) ?>" class="text-decoration-none">
                            <?= htmlspecialchars($account->sdt) ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Chưa cập nhật</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Vai trò</label>
                <p class="mb-0">
                    <span class="badge bg-<?= $account->phan_quyen === 'admin' ? 'primary' : 'info' ?> fs-6">
                        <i class="bi <?= $account->getRoleIcon() ?> me-1"></i>
                        <?= $account->getRoleText() ?>
                    </span>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Trạng thái</label>
                <p class="mb-0">
                    <span class="badge bg-<?= $account->getStatusColor() ?> fs-6">
                        <?= $account->getStatusText() ?>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Ngày tạo</label>
                <p class="mb-0">
                    <?php if ($account->ngay_tao): ?>
                        <?= date('d/m/Y H:i', strtotime($account->ngay_tao)) ?>
                    <?php else: ?>
                        <span class="text-muted">Chưa cập nhật</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold text-muted">Ngày cập nhật</label>
                <p class="mb-0">
                    <?php if ($account->ngay_cap_nhat): ?>
                        <?= date('d/m/Y H:i', strtotime($account->ngay_cap_nhat)) ?>
                    <?php else: ?>
                        <span class="text-muted">Chưa cập nhật</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render avatar và thống kê của account
 */
function renderAccountAvatarStats($account)
{
    ?>
    <?php
    $imagePath = BASE_PATH . '/public/dist/assets/img/' . $account->anh_dai_dien;
    if ($account->anh_dai_dien && file_exists($imagePath)):
    ?>
        <img src="<?= asset('dist/assets/img/' . $account->anh_dai_dien) ?>"
             class="rounded-circle mb-3"
             alt="Avatar"
             style="width: 120px; height: 120px; object-fit: cover;">
    <?php else: ?>
        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
             style="width: 120px; height: 120px;">
            <i class="bi bi-person text-white" style="font-size: 3rem;"></i>
        </div>
    <?php endif; ?>
    <p class="text-muted small mb-0">Ảnh đại diện</p>

    <!-- Thống kê nhanh -->
    <div class="row text-center mt-4">
        <div class="col-12">
            <div class="mb-2">
                <span class="badge bg-<?= $account->phan_quyen === 'admin' ? 'primary' : 'info' ?> fs-6">
                    <?= $account->getRoleText() ?>
                </span>
            </div>
            <small class="text-muted">Vai trò hiện tại</small>
        </div>
    </div>
    <?php
}

/**
 * Render thông tin hướng dẫn viên
 */
function renderGuideInfo($account)
{
    if ($account->phan_quyen !== 'hdv') return;

    ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-badge me-2"></i>
                        Thông tin hướng dẫn viên
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Ngày sinh</label>
                                <p class="mb-0">
                                    <?php if ($account->ngay_sinh): ?>
                                        <?= date('d/m/Y', strtotime($account->ngay_sinh)) ?>
                                        <small class="text-muted">(<?= date_diff(date_create($account->ngay_sinh), date_create('today'))->y ?> tuổi)</small>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Liên hệ</label>
                                <p class="mb-0">
                                    <?= $account->lien_he ? htmlspecialchars($account->lien_he) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Nhóm</label>
                                <p class="mb-0">
                                    <?php
                                    $nhomText = match($account->nhom) {
                                        'noi_dia' => 'Nội địa',
                                        'quoc_te' => 'Quốc tế',
                                        'yeu_cau' => 'Theo yêu cầu',
                                        default => 'Chưa phân loại'
                                    };
                                    ?>
                                    <span class="badge bg-secondary"><?= $nhomText ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted">Chuyên môn</label>
                                <p class="mb-0">
                                    <?= $account->chuyen_mon ? htmlspecialchars($account->chuyen_mon) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render modal xác nhận xóa
 */
function renderDeleteModal()
{
    ?>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                        Xác nhận xóa tài khoản
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa tài khoản <strong id="deleteAccountName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Hành động này không thể hoàn tác!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <a id="deleteLink" href="#" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Xóa tài khoản
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render JavaScript cho confirm delete
 */
function renderDeleteScript()
{
    ?>
    <script>
    function confirmDelete(accountId, accountName) {
        document.getElementById('deleteAccountName').textContent = accountName;
        document.getElementById('deleteLink').href = '<?= BASE_URL ?>accounts/delete/' + accountId;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    </script>
    <?php
}