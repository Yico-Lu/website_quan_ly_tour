<?php

// Controller xử lý quản lý tài khoản
class AccountController
{
    // Hiển thị danh sách tất cả tài khoản
    public function index()
    {
        // Kiểm tra quyền admin
        requireAdmin();

        $filter = $_GET['filter'] ?? 'all';
        $search = $_GET['search'] ?? '';

        switch ($filter) {
            case 'admin':
                $accounts = Account::getAdmins();
                $title = 'Danh sách quản trị viên';
                $pageTitle = 'Quản lý Quản trị viên';
                break;
            case 'hdv':
                $accounts = Account::getGuides();
                $title = 'Danh sách hướng dẫn viên';
                $pageTitle = 'Quản lý Hướng dẫn viên';
                break;
            default:
                $accounts = Account::all();
                $title = 'Danh sách tài khoản';
                $pageTitle = 'Quản lý Tài khoản';
        }

        // Lọc theo từ khóa tìm kiếm
        if (!empty($search)) {
            $accounts = array_filter($accounts, function($account) use ($search) {
                return stripos($account->ho_ten, $search) !== false ||
                       stripos($account->email, $search) !== false ||
                       stripos($account->ten_dang_nhap, $search) !== false;
            });
        }

        // Lấy dữ liệu từ session nếu có lỗi khi tạo tài khoản
        $newAccount = $_SESSION['new_account_data'] ?? [];
        $formErrors = $_SESSION['form_errors'] ?? [];

        // Đảm bảo $newAccount luôn là array
        if (!is_array($newAccount)) {
            $newAccount = [];
        }

        // Xóa session sau khi sử dụng
        unset($_SESSION['new_account_data'], $_SESSION['form_errors']);

        view('layouts.AdminLayout', [
            'title' => $pageTitle,
            'pageTitle' => $pageTitle,
            'content' => view_content('admin.accounts.index', [
                'accounts' => $accounts,
                'filter' => $filter,
                'search' => $search,
                'title' => $title,
                'newAccount' => $newAccount,
                'errors' => $formErrors
            ]),
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => $pageTitle, 'url' => BASE_URL . 'accounts?filter=' . $filter, 'active' => true],
            ],
        ]);
    }


    // Xử lý lưu tài khoản mới
    public function store()
    {
        // Kiểm tra quyền admin
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'accounts/create');
            exit;
        }

        $data = $_POST;
        $errors = $this->validateAccountData($data);

        if (!empty($errors)) {
            // Lưu dữ liệu và lỗi vào session để hiển thị trên trang index
            $_SESSION['new_account_data'] = $data;
            $_SESSION['form_errors'] = $errors;

            // Redirect về trang hiện tại
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'accounts';
            header('Location: ' . $redirectUrl);
            exit;
        }

        // Hash mật khẩu
        $data['mat_khau'] = Account::hashPassword($data['mat_khau']);

        $account = new Account($data);
        if ($account->save()) {
            $_SESSION['success'] = 'Tài khoản đã được tạo thành công!';
            // Redirect về trang hiện tại
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'accounts';
            header('Location: ' . $redirectUrl);
            exit;
        } else {
            // Lưu lỗi vào session nếu save thất bại
            $_SESSION['new_account_data'] = $data;
            $_SESSION['form_errors'] = ['Có lỗi xảy ra khi tạo tài khoản. Vui lòng thử lại.'];

            // Redirect về trang hiện tại
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'accounts';
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    // Hiển thị chi tiết tài khoản
    public function show($id)
    {
        // Kiểm tra quyền admin
        requireAdmin();

        $account = Account::find($id);
        if (!$account) {
            $this->notFound();
            return;
        }

        view('layouts.AdminLayout', [
            'title' => 'Chi tiết tài khoản: ' . $account->getDisplayName(),
            'pageTitle' => 'Chi tiết tài khoản',
            'content' => view_content('admin.accounts.show', [
                'account' => $account,
            ]),
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => 'Chi tiết', 'url' => BASE_URL . 'accounts/show/' . $id, 'active' => true],
            ],
        ]);
    }

    // Hiển thị form chỉnh sửa tài khoản
    public function edit($id)
    {
        // Kiểm tra quyền admin
        requireAdmin();

        $account = Account::find($id);
        if (!$account) {
            $this->notFound();
            return;
        }

        view('layouts.AdminLayout', [
            'title' => 'Chỉnh sửa tài khoản: ' . $account->getDisplayName(),
            'pageTitle' => 'Chỉnh sửa tài khoản',
            'content' => view_content('admin.accounts.edit', [
                'account' => $account,
                'errors' => [],
            ]),
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => 'Chỉnh sửa', 'url' => BASE_URL . 'accounts/edit/' . $id, 'active' => true],
            ],
        ]);
    }

    // Xử lý cập nhật tài khoản
    public function update($id)
    {
        // Kiểm tra quyền admin
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'accounts/edit/' . $id);
            exit;
        }

        $account = Account::find($id);
        if (!$account) {
            $this->notFound();
            return;
        }

        $data = $_POST;
        $errors = $this->validateAccountData($data, $id);

        if (!empty($errors)) {
            view('layouts.AdminLayout', [
                'title' => 'Chỉnh sửa tài khoản: ' . $account->getDisplayName(),
                'pageTitle' => 'Chỉnh sửa tài khoản',
                'content' => view_content('admin.accounts.edit', [
                    'account' => new Account(array_merge($account->toArray(), $data)),
                    'errors' => $errors,
                ]),
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                    ['label' => 'Chỉnh sửa', 'url' => BASE_URL . 'accounts/edit/' . $id, 'active' => true],
                ],
            ]);
            return;
        }

        // Cập nhật dữ liệu
        $account->ten_dang_nhap = $data['ten_dang_nhap'];
        $account->ho_ten = $data['ho_ten'];
        $account->email = $data['email'];
        $account->sdt = $data['sdt'];
        $account->phan_quyen = $data['phan_quyen'];
        $account->trang_thai = $data['trang_thai'];

        // Thông tin HDV
        if ($account->phan_quyen === 'hdv') {
            $account->ngay_sinh = $data['ngay_sinh'] ?? null;
            $account->anh_dai_dien = $data['anh_dai_dien'] ?? '';
            $account->lien_he = $data['lien_he'] ?? '';
            $account->nhom = $data['nhom'] ?? '';
            $account->chuyen_mon = $data['chuyen_mon'] ?? '';
        }

        if ($account->save()) {
            $_SESSION['success'] = 'Tài khoản đã được cập nhật thành công!';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        } else {
            $errors[] = 'Có lỗi xảy ra khi cập nhật tài khoản. Vui lòng thử lại.';
            view('layouts.AdminLayout', [
                'title' => 'Chỉnh sửa tài khoản: ' . $account->getDisplayName(),
                'pageTitle' => 'Chỉnh sửa tài khoản',
                'content' => view_content('admin.accounts.edit', [
                    'account' => $account,
                    'errors' => $errors,
                ]),
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                    ['label' => 'Chỉnh sửa', 'url' => BASE_URL . 'accounts/edit/' . $id, 'active' => true],
                ],
            ]);
        }
    }

    // Xóa tài khoản
    public function delete($id)
    {
        // Kiểm tra quyền admin
        requireAdmin();

        $account = Account::find($id);
        if (!$account) {
            $this->notFound();
            return;
        }

        // Không cho phép xóa tài khoản admin hiện tại
        $currentUser = getCurrentUser();
        if ($account->id == $currentUser->id) {
            $_SESSION['error'] = 'Không thể xóa tài khoản đang đăng nhập!';
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        if ($account->delete()) {
            $_SESSION['success'] = 'Tài khoản đã được xóa thành công!';
        } else {
            $_SESSION['error'] = 'Không thể xóa tài khoản này: ' . $account->getDeleteReason();
        }

        header('Location: ' . BASE_URL . 'accounts');
        exit;
    }

    // Đổi mật khẩu
    public function changePassword($id)
    {
        // Kiểm tra quyền admin hoặc chính chủ tài khoản
        $currentUser = getCurrentUser();
        if (!$currentUser->isAdmin() && $currentUser->id != $id) {
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'accounts');
            exit;
        }

        $account = Account::find($id);
        if (!$account) {
            $this->notFound();
            return;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        // Validate mật khẩu hiện tại (chỉ khi không phải admin đổi cho người khác)
        if ($currentUser->id == $id && !$account->verifyPassword($currentPassword)) {
            $errors[] = 'Mật khẩu hiện tại không đúng';
        }

        // Validate mật khẩu mới
        if (strlen($newPassword) < 6) {
            $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . 'accounts/edit/' . $id);
            exit;
        }

        if ($account->changePassword(Account::hashPassword($newPassword))) {
            $_SESSION['success'] = 'Mật khẩu đã được thay đổi thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi thay đổi mật khẩu!';
        }

        header('Location: ' . BASE_URL . 'accounts/edit/' . $id);
        exit;
    }

    // Validation dữ liệu tài khoản
    private function validateAccountData($data, $excludeId = null)
    {
        $errors = [];

        // Validate tên đăng nhập
        if (empty($data['ten_dang_nhap'])) {
            $errors[] = 'Vui lòng nhập tên đăng nhập';
        } elseif (strlen($data['ten_dang_nhap']) < 3) {
            $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
        } else {
            // Kiểm tra tên đăng nhập đã tồn tại
            $existing = Account::findByUsername($data['ten_dang_nhap']);
            if ($existing && $existing->id != $excludeId) {
                $errors[] = 'Tên đăng nhập đã tồn tại';
            }
        }

        // Validate mật khẩu (chỉ khi tạo mới)
        if (!$excludeId && empty($data['mat_khau'])) {
            $errors[] = 'Vui lòng nhập mật khẩu';
        } elseif (!$excludeId && strlen($data['mat_khau']) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        // Validate họ tên
        if (empty($data['ho_ten'])) {
            $errors[] = 'Vui lòng nhập họ tên';
        }

        // Validate email
        if (empty($data['email'])) {
            $errors[] = 'Vui lòng nhập email';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        } else {
            // Kiểm tra email đã tồn tại
            $existing = Account::findByEmail($data['email']);
            if ($existing && $existing->id != $excludeId) {
                $errors[] = 'Email đã tồn tại';
            }
        }

        // Validate SĐT
        if (!empty($data['sdt']) && !preg_match('/^[0-9+\-\s()]+$/', $data['sdt'])) {
            $errors[] = 'Số điện thoại không hợp lệ';
        }

        // Validate vai trò
        if (empty($data['phan_quyen']) || !in_array($data['phan_quyen'], ['admin', 'hdv'])) {
            $errors[] = 'Vai trò không hợp lệ';
        }

        // Validate trạng thái
        if (empty($data['trang_thai']) || !in_array($data['trang_thai'], ['hoat_dong', 'ngung'])) {
            $errors[] = 'Trạng thái không hợp lệ';
        }

        return $errors;
    }

    // Hiển thị danh sách quản trị viên
    public function admins()
    {
        // Kiểm tra quyền admin
        requireAdmin();

        $search = $_GET['search'] ?? '';
        $accounts = Account::getAdmins();

        // Lọc theo từ khóa tìm kiếm
        if (!empty($search)) {
            $accounts = array_filter($accounts, function($account) use ($search) {
                return stripos($account->ho_ten, $search) !== false ||
                       stripos($account->email, $search) !== false ||
                       stripos($account->ten_dang_nhap, $search) !== false;
            });
        }

        // Lấy dữ liệu từ session nếu có lỗi khi tạo tài khoản
        $newAccount = $_SESSION['new_account_data'] ?? [];
        $formErrors = $_SESSION['form_errors'] ?? [];

        // Đảm bảo $newAccount luôn là array
        if (!is_array($newAccount)) {
            $newAccount = [];
        }

        // Xóa session sau khi sử dụng
        unset($_SESSION['new_account_data'], $_SESSION['form_errors']);

        view('layouts.AdminLayout', [
            'title' => 'Quản lý Quản trị viên',
            'pageTitle' => 'Quản lý Quản trị viên',
            'content' => view_content('admin.accounts.index', [
                'accounts' => $accounts,
                'filter' => 'admin',
                'search' => $search,
                'title' => 'Danh sách quản trị viên',
                'newAccount' => $newAccount,
                'errors' => $formErrors
            ]),
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => 'Quản trị viên', 'url' => BASE_URL . 'accounts/admins', 'active' => true],
            ],
        ]);
    }


    // Trang riêng để thêm tài khoản (fallback nếu modal không hoạt động)
    public function create()
    {
        // Kiểm tra quyền admin
        requireAdmin();

        view('layouts.AdminLayout', [
            'title' => 'Thêm tài khoản mới',
            'pageTitle' => 'Thêm tài khoản mới',
            'content' => view_content('admin.accounts.create', [
                'account' => $_SESSION['new_account_data'] ?? [],
                'errors' => $_SESSION['form_errors'] ?? [],
            ]),
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => 'Thêm mới', 'url' => BASE_URL . 'accounts/create', 'active' => true],
            ],
        ]);

        // Xóa session sau khi sử dụng
        unset($_SESSION['new_account_data'], $_SESSION['form_errors']);
    }

    // Trang 404
    private function notFound()
    {
        http_response_code(404);
        view('layouts.AdminLayout', [
            'title' => 'Không tìm thấy tài khoản',
            'pageTitle' => 'Không tìm thấy',
            'content' => view_content('not_found', [
                'message' => 'Tài khoản không tồn tại hoặc đã bị xóa.',
                'back_url' => BASE_URL . 'accounts',
                'back_text' => 'Quay lại danh sách tài khoản'
            ]),
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Quản lý tài khoản', 'url' => BASE_URL . 'accounts'],
                ['label' => 'Không tìm thấy', 'url' => '#', 'active' => true],
            ],
        ]);
    }
}
