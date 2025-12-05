<?php

class AccountController
{
    // Hiển thị danh sách tài khoản
    public function index(): void
    {
        requireAdmin();

        $users = User::getAll();

        view('admin.accounts.index', [
            'title' => 'Quản lý tài khoản',
            'pageTitle' => 'Danh sách tài khoản',
            'users' => $users,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts', 'active' => true],
            ],
        ]);
    }

    // Hiển thị form tạo tài khoản mới
    public function create(): void
    {
        requireAdmin();

        view('admin.accounts.create', [
            'title' => 'Thêm tài khoản mới',
            'pageTitle' => 'Thêm tài khoản mới',
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => 'Thêm tài khoản mới', 'url' => BASE_URL . 'accounts/create', 'active' => true],
            ],
        ]);
    }

    // Xử lý tạo tài khoản mới
    public function store(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'accounts/create');
            exit;
        }

        // Lấy dữ liệu từ form
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? 'huong_dan_vien';
        $status = isset($_POST['status']);

        // Validate dữ liệu
        $errors = [];
        if (empty($name)) $errors[] = 'Vui lòng nhập họ tên';
        if (empty($email)) $errors[] = 'Vui lòng nhập email';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';
        if (empty($password)) $errors[] = 'Vui lòng nhập mật khẩu';
        elseif (strlen($password) < 6) $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        if ($password !== $confirmPassword) $errors[] = 'Mật khẩu xác nhận không khớp';
        if (!in_array($role, ['admin', 'huong_dan_vien'])) $errors[] = 'Vai trò không hợp lệ';

        if (!empty($errors)) {
            view('admin.accounts.create', [
                'title' => 'Thêm tài khoản mới',
                'pageTitle' => 'Thêm tài khoản mới',
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                    ['label' => 'Thêm tài khoản mới', 'url' => BASE_URL . 'accounts/create', 'active' => true],
                ],
            ]);
            return;
        }

        // Tạo tài khoản mới
        $user = new User([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => $status,
        ]);

        if (User::create($user, $password)) {
            $_SESSION['success'] = 'Thêm tài khoản mới thành công';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        } else {
            $_SESSION['error'] = 'Email đã tồn tại trong hệ thống';
            header('Location: ' . BASE_URL . 'accounts/create');
            exit;
        }
    }

    // Hiển thị form chỉnh sửa tài khoản
    public function edit($id): void
    {
        requireAdmin();

        $user = User::find($id);
        if (!$user) {
            $_SESSION['error'] = 'Tài khoản không tồn tại';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        view('admin.accounts.edit', [
            'title' => 'Chỉnh sửa tài khoản',
            'pageTitle' => 'Chỉnh sửa tài khoản',
            'user' => $user,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => 'Chỉnh sửa tài khoản', 'url' => BASE_URL . 'accounts/edit/' . $id, 'active' => true],
            ],
        ]);
    }

    // Xử lý cập nhật tài khoản
    public function update(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        // Lấy dữ liệu từ form
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'huong_dan_vien';
        $status = isset($_POST['status']);
        $changePassword = isset($_POST['change_password']);
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate dữ liệu
        $errors = [];
        if (empty($name)) $errors[] = 'Vui lòng nhập họ tên';
        if (empty($email)) $errors[] = 'Vui lòng nhập email';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';
        if (!in_array($role, ['admin', 'huong_dan_vien'])) $errors[] = 'Vai trò không hợp lệ';

        if ($changePassword) {
            if (empty($newPassword)) $errors[] = 'Vui lòng nhập mật khẩu mới';
            elseif (strlen($newPassword) < 6) $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            if ($newPassword !== $confirmPassword) $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        if (!empty($errors)) {
            $user = User::find($id);
            view('admin.accounts.edit', [
                'title' => 'Chỉnh sửa tài khoản',
                'pageTitle' => 'Chỉnh sửa tài khoản',
                'user' => $user,
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                    ['label' => 'Chỉnh sửa tài khoản', 'url' => BASE_URL . 'accounts/edit/' . $id, 'active' => true],
                ],
            ]);
            return;
        }

        // Cập nhật tài khoản
        $user = new User([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => $status,
        ]);

        if (User::update($user)) {
            // Cập nhật mật khẩu nếu có yêu cầu
            if ($changePassword && !empty($newPassword)) {
                User::updatePassword($id, $newPassword);
            }

            $_SESSION['success'] = 'Cập nhật tài khoản thành công';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        } else {
            $_SESSION['error'] = 'Email đã tồn tại trong hệ thống';
            header('Location: ' . BASE_URL . 'accounts/edit/' . $id);
            exit;
        }
    }

    // Hiển thị chi tiết tài khoản
    public function show($id): void
    {
        requireAdmin();

        $user = User::find($id);
        if (!$user) {
            $_SESSION['error'] = 'Tài khoản không tồn tại';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        view('admin.accounts.show', [
            'title' => 'Chi tiết tài khoản',
            'pageTitle' => 'Chi tiết tài khoản: ' . htmlspecialchars($user->name),
            'user' => $user,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => 'Chi tiết tài khoản', 'url' => BASE_URL . 'accounts/show/' . $id, 'active' => true],
            ],
        ]);
    }

    // Xóa tài khoản
    public function delete(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        // Không cho phép xóa tài khoản hiện tại
        $currentUser = getCurrentUser();
        if ($currentUser && $currentUser->id == $id) {
            $_SESSION['error'] = 'Không thể xóa tài khoản đang đăng nhập';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        // Không cho phép xóa tài khoản admin cuối cùng đang hoạt động
        $user = User::find($id);
        if ($user && $user->isAdmin() && $user->status == 1) {
            $activeAdminCount = count(array_filter(User::getAll(), function($u) {
                return $u->isAdmin() && $u->status == 1;
            }));
            if ($activeAdminCount <= 1) {
                $_SESSION['error'] = 'Không thể xóa tài khoản admin cuối cùng đang hoạt động';
                header('Location: ' . BASE_URL . 'accounts');
                exit;
            }
        }

        if (User::delete($id)) {
            $_SESSION['success'] = 'Xóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa tài khoản này vì đang được sử dụng trong hệ thống (booking hoặc báo cáo)';
        }

        header('Location: ' . BASE_URL . 'accounts');
        exit;
    }
}