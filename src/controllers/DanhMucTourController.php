<?php
class DanhMucTourController
{
    // Hiển thị danh sách danh mục tour
    public function index(): void
    {
        // Chỉ admin mới được truy cập quản lý danh mục
        requireAdmin();

        $danhMucs = DanhMucTour::getAll();

        // Hiển thị view danh sách danh mục
        view('admin.categories.index', [
            'title' => 'Danh sách Danh mục Tour - Quản lý Tour',
            'pageTitle' => 'Danh sách Danh mục Tour',
            'danhMucs' => $danhMucs,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories', 'active' => true],
            ],
        ]);
    }

    // Hiển thị chi tiết danh mục tour
    public function show($id): void
    {
        requireAdmin();

        $danhMuc = DanhMucTour::find($id);
        if (!$danhMuc) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            header('Location: ' . BASE_URL . 'categories');
            exit;
        }

        view('admin.categories.show', [
            'title' => 'Chi tiết Danh mục: ' . $danhMuc->ten_danh_muc,
            'pageTitle' => 'Chi tiết Danh mục Tour',
            'danhMuc' => $danhMuc,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                ['label' => 'Chi tiết danh mục', 'url' => BASE_URL . 'categories/show/' . $id, 'active' => true],
            ],
        ]);
    }

    // Hiển thị form thêm danh mục mới
    public function create(): void
    {
        requireAdmin();

        view('admin.categories.create', [
            'title' => 'Thêm Danh mục Tour mới',
            'pageTitle' => 'Thêm Danh mục Tour mới',
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                ['label' => 'Thêm danh mục mới', 'url' => BASE_URL . 'categories/create', 'active' => true],
            ],
        ]);
    }

    // Lưu danh mục mới
    public function store(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'categories/create');
            exit;
        }

        // Lấy dữ liệu từ form
        $ten_danh_muc = trim($_POST['ten_danh_muc'] ?? '');
        $mo_ta = trim($_POST['mo_ta'] ?? '');
        $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

        // Kiểm tra dữ liệu
        $errors = [];
        if (empty($ten_danh_muc)) $errors[] = 'Vui lòng nhập tên danh mục';
        if (empty($mo_ta)) $errors[] = 'Vui lòng nhập mô tả';

        // Kiểm tra tên danh mục đã tồn tại
        if (!empty($ten_danh_muc)) {
            $pdo = getDB();
            $sqlCheck = "SELECT COUNT(*) FROM danh_muc_tour WHERE ten_danh_muc = ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$ten_danh_muc]);
            if ($stmtCheck->fetchColumn() > 0) {
                $errors[] = 'Tên danh mục đã tồn tại';
            }
        }

        if (!empty($errors)) {
            view('admin.categories.create', [
                'title' => 'Thêm danh mục mới',
                'pageTitle' => 'Thêm danh mục mới',
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                    ['label' => 'Thêm danh mục mới', 'url' => BASE_URL . 'categories/create', 'active' => true],
                ],
            ]);
            return;
        }

        // Tạo danh mục mới
        $danhMuc = new DanhMucTour([
            'ten_danh_muc' => $ten_danh_muc,
            'mo_ta' => $mo_ta,
            'trang_thai' => $trang_thai
        ]);

        if (DanhMucTour::create($danhMuc)) {
            $_SESSION['success'] = 'Thêm danh mục mới thành công';
            header('Location: ' . BASE_URL . 'categories');
            exit;
        } else {
            $_SESSION['error'] = 'Thêm danh mục mới thất bại';
            header('Location: ' . BASE_URL . 'categories/create');
            exit;
        }
    }

    // Hiển thị form sửa danh mục
    public function edit($id): void
    {
        requireAdmin();

        $danhMuc = DanhMucTour::find($id);
        if (!$danhMuc) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            header('Location: ' . BASE_URL . 'categories');
            exit;
        }

        view('admin.categories.edit', [
            'title' => 'Sửa Danh mục Tour',
            'pageTitle' => 'Sửa Danh mục Tour',
            'danhMuc' => $danhMuc,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                ['label' => 'Sửa danh mục', 'url' => BASE_URL . 'categories/edit/' . $id, 'active' => true],
            ],
        ]);
    }

    // Cập nhật danh mục
    public function update(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'categories');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ' . BASE_URL . 'categories');
            exit;
        }

        // Lấy dữ liệu từ form
        $ten_danh_muc = trim($_POST['ten_danh_muc'] ?? '');
        $mo_ta = trim($_POST['mo_ta'] ?? '');
        $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

        // Kiểm tra dữ liệu
        $errors = [];
        if (empty($ten_danh_muc)) $errors[] = 'Vui lòng nhập tên danh mục';
        if (empty($mo_ta)) $errors[] = 'Vui lòng nhập mô tả';

        // Kiểm tra tên danh mục đã tồn tại (trừ danh mục hiện tại)
        if (!empty($ten_danh_muc)) {
            $pdo = getDB();
            $sqlCheck = "SELECT COUNT(*) FROM danh_muc_tour WHERE ten_danh_muc = ? AND id != ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$ten_danh_muc, $id]);
            if ($stmtCheck->fetchColumn() > 0) {
                $errors[] = 'Tên danh mục đã tồn tại';
            }
        }

        if (!empty($errors)) {
            $danhMuc = DanhMucTour::find($id);
            view('admin.categories.edit', [
                'title' => 'Sửa danh mục',
                'pageTitle' => 'Sửa danh mục',
                'danhMuc' => $danhMuc,
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                    ['label' => 'Sửa danh mục', 'url' => BASE_URL . 'categories/edit/' . $id, 'active' => true],
                ],
            ]);
            return;
        }

        // Tạo danh mục để cập nhật
        $danhMuc = new DanhMucTour([
            'id' => $id,
            'ten_danh_muc' => $ten_danh_muc,
            'mo_ta' => $mo_ta,
            'trang_thai' => $trang_thai
        ]);

        if (DanhMucTour::update($danhMuc)) {
            $_SESSION['success'] = 'Cập nhật danh mục thành công';
            header('Location: ' . BASE_URL . 'categories');
            exit;
        } else {
            $_SESSION['error'] = 'Cập nhật danh mục thất bại';
            header('Location: ' . BASE_URL . 'categories/edit/' . $id);
            exit;
        }
    }

    // Xóa danh mục
    public function delete(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'categories');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ' . BASE_URL . 'categories');
            exit;
        }

        if (DanhMucTour::delete($id)) {
            $_SESSION['success'] = 'Xóa danh mục thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa danh mục này vì đang có tour sử dụng';
        }
        header('Location: ' . BASE_URL . 'categories');
        exit;
    }
}
?>