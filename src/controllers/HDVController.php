<?php
class HDVController
{
    // Hiển thị danh sách HDV
    public function index(): void
    {
        requireAdmin();

        $hdvs = HDV::getAll();

        view('admin.hdvs.index', [
            'title' => 'Danh sách Hướng dẫn viên - Quản lý Tour',
            'pageTitle' => 'Danh sách Hướng dẫn viên',
            'hdvs' => $hdvs,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách HDV', 'url' => BASE_URL . 'hdvs', 'active' => true],
            ],
        ]);
    }

    // Hiển thị chi tiết HDV
    public function show($id): void
    {
        requireAdmin();

        $hdv = HDV::find($id);
        if (!$hdv) {
            $_SESSION['error'] = 'HDV không tồn tại';
            header('Location: ' . BASE_URL . 'hdvs');
            exit;
        }

        view('admin.hdvs.show', [
            'title' => 'Chi tiết HDV: ' . $hdv->ho_ten,
            'pageTitle' => 'Chi tiết Hướng dẫn viên',
            'hdv' => $hdv,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách HDV', 'url' => BASE_URL . 'hdvs'],
                ['label' => 'Chi tiết HDV', 'url' => BASE_URL . 'hdvs/show/' . $id, 'active' => true],
            ],
        ]);
    }

    // Hiển thị form thêm HDV mới
    public function create(): void
    {
        requireAdmin();

        $availableAccounts = HDV::getAvailableAccounts();

        view('admin.hdvs.create', [
            'title' => 'Thêm Hướng dẫn viên mới',
            'pageTitle' => 'Thêm Hướng dẫn viên mới',
            'availableAccounts' => $availableAccounts,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách HDV', 'url' => BASE_URL . 'hdvs'],
                ['label' => 'Thêm HDV mới', 'url' => BASE_URL . 'hdvs/create', 'active' => true],
            ],
        ]);
    }

    // Lưu HDV mới
    public function store(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'hdvs/create');
            exit;
        }

        // Lấy dữ liệu từ form
        $tai_khoan_id = trim($_POST['tai_khoan_id'] ?? '');
        $ngay_sinh = trim($_POST['ngay_sinh'] ?? '');
        $lien_he = trim($_POST['lien_he'] ?? '');
        $nhom = trim($_POST['nhom'] ?? '');
        $chuyen_mon = trim($_POST['chuyen_mon'] ?? '');

        // Xử lý upload ảnh
        $anh_dai_dien = null;
        if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadedPath = uploadImage($_FILES['anh_dai_dien'], 'hdv', 'uploads/hdvs/');
            if ($uploadedPath) {
                $anh_dai_dien = str_replace('uploads/', '', ltrim($uploadedPath, '/'));
            }
        }

        // Kiểm tra dữ liệu
        $errors = [];

        // Kiểm tra dữ liệu
        if (empty($tai_khoan_id)) $errors[] = 'Vui lòng chọn tài khoản';
        if (empty($nhom)) $errors[] = 'Vui lòng chọn nhóm';
        if (empty($chuyen_mon)) $errors[] = 'Vui lòng nhập chuyên môn';

        if (!empty($errors)) {
            $availableAccounts = HDV::getAvailableAccounts();
            view('admin.hdvs.create', [
                'title' => 'Thêm HDV mới',
                'pageTitle' => 'Thêm HDV mới',
                'availableAccounts' => $availableAccounts,
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách HDV', 'url' => BASE_URL . 'hdvs'],
                    ['label' => 'Thêm HDV mới', 'url' => BASE_URL . 'hdvs/create', 'active' => true],
                ],
            ]);
            return;
        }

        // Tạo HDV mới
        $hdv = new HDV([
            'tai_khoan_id' => $tai_khoan_id,
            'ngay_sinh' => $ngay_sinh ?: null,
            'anh_dai_dien' => $anh_dai_dien,
            'lien_he' => $lien_he ?: null,
            'nhom' => $nhom,
            'chuyen_mon' => $chuyen_mon
        ]);

        $result = HDV::create($hdv);
        if ($result) {
            $_SESSION['success'] = 'Thêm HDV mới thành công';
            // Xóa tất cả output buffer
            while (ob_get_level()) {
                ob_end_clean();
            }
            // Đảm bảo không có output nào
            if (headers_sent($file, $line)) {
                error_log("Headers already sent in {$file} on line {$line}");
            }
            header('Location: ' . BASE_URL . 'hdvs', true, 302);
            exit;
        } else {
            $_SESSION['error'] = 'Thêm HDV mới thất bại. Tài khoản này đã có thông tin HDV hoặc không phải là tài khoản HDV.';
            // Xóa tất cả output buffer
            while (ob_get_level()) {
                ob_end_clean();
            }
            header('Location: ' . BASE_URL . 'hdvs/create', true, 302);
            exit;
        }
    }

    // Hiển thị form sửa HDV
    public function edit($id): void
    {
        requireAdmin();

        $hdv = HDV::find($id);
        if (!$hdv) {
            $_SESSION['error'] = 'HDV không tồn tại';
            header('Location: ' . BASE_URL . 'hdvs');
            exit;
        }

        view('admin.hdvs.edit', [
            'title' => 'Sửa Hướng dẫn viên',
            'pageTitle' => 'Sửa Hướng dẫn viên',
            'hdv' => $hdv,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách HDV', 'url' => BASE_URL . 'hdvs'],
                ['label' => 'Sửa HDV', 'url' => BASE_URL . 'hdvs/edit/' . $id, 'active' => true],
            ],
        ]);
    }

    // Cập nhật HDV
    public function update(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'hdvs');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ' . BASE_URL . 'hdvs');
            exit;
        }

        $hdv = HDV::find($id);
        if (!$hdv) {
            $_SESSION['error'] = 'HDV không tồn tại';
            header('Location: ' . BASE_URL . 'hdvs');
            exit;
        }

        // Lấy dữ liệu từ form
        $ngay_sinh = trim($_POST['ngay_sinh'] ?? '');
        $lien_he = trim($_POST['lien_he'] ?? '');
        $nhom = trim($_POST['nhom'] ?? '');
        $chuyen_mon = trim($_POST['chuyen_mon'] ?? '');

        // Khởi tạo mảng errors
        $errors = [];

        // Xử lý upload ảnh mới (nếu có)
        $anh_dai_dien = $hdv->anh_dai_dien;
        if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadedPath = uploadImage($_FILES['anh_dai_dien'], 'hdv', 'uploads/hdvs/');
            if ($uploadedPath) {
                                if ($hdv->anh_dai_dien) {
                    @unlink(BASE_PATH . '/public/uploads/' . $hdv->anh_dai_dien);
                                }
                $anh_dai_dien = str_replace('uploads/', '', ltrim($uploadedPath, '/'));
            }
        }

        // Kiểm tra dữ liệu
        $errors = [];

        // Kiểm tra dữ liệu
        if (empty($nhom)) $errors[] = 'Vui lòng chọn nhóm';
        if (empty($chuyen_mon)) $errors[] = 'Vui lòng nhập chuyên môn';

        if (!empty($errors)) {
            view('admin.hdvs.edit', [
                'title' => 'Sửa HDV',
                'pageTitle' => 'Sửa HDV',
                'hdv' => $hdv,
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách HDV', 'url' => BASE_URL . 'hdvs'],
                    ['label' => 'Sửa HDV', 'url' => BASE_URL . 'hdvs/edit/' . $id, 'active' => true],
                ],
            ]);
            return;
        }

        // Cập nhật HDV
        $hdv->ngay_sinh = $ngay_sinh ?: null;
        $hdv->anh_dai_dien = $anh_dai_dien;
        $hdv->lien_he = $lien_he ?: null;
        $hdv->nhom = $nhom;
        $hdv->chuyen_mon = $chuyen_mon;

        if (HDV::update($hdv)) {
            $_SESSION['success'] = 'Cập nhật HDV thành công';
            header('Location: ' . BASE_URL . 'hdvs');
            exit;
        } else {
            $_SESSION['error'] = 'Cập nhật HDV thất bại';
            header('Location: ' . BASE_URL . 'hdvs/edit/' . $id);
            exit;
        }
    }

    // Xóa HDV
    public function delete(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'hdvs');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ' . BASE_URL . 'hdvs');
            exit;
        }

        $hdv = HDV::find($id);
        if ($hdv && HDV::delete($id)) {
            // Xóa ảnh đại diện nếu có
            if ($hdv->anh_dai_dien) {
                $imagePath = BASE_PATH . '/public/uploads/' . $hdv->anh_dai_dien;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $_SESSION['success'] = 'Xóa HDV thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa HDV này';
        }
        header('Location: ' . BASE_URL . 'hdvs');
        exit;
    }
}

