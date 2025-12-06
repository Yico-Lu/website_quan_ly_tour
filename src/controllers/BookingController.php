<?php
class BookingController
{
    // Hiển thị danh sách booking
    public function index(): void
    {
        // Chỉ admin mới được truy cập quản lý booking
        requireAdmin();

        $bookings = Booking::getAll();

        // Hiển thị view danh sách booking
        view('admin.bookings.index', [
            'title' => 'Danh sách Booking - Quản lý Tour',
            'pageTitle' => 'Danh sách Booking',
            'bookings' => $bookings,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách booking', 'url' => BASE_URL . 'bookings', 'active' => true],
            ],
        ]);
    }

    // Hiển thị chi tiết booking
    public function show($id): void
    {
        requireAdmin();

        $booking = Booking::find($id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        view('admin.bookings.show', [
            'title' => 'Chi tiết Booking - ' . $booking->ten_nguoi_dat,
            'pageTitle' => 'Chi tiết Booking',
            'booking' => $booking,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách booking', 'url' => BASE_URL . 'bookings'],
                ['label' => 'Chi tiết booking', 'url' => BASE_URL . 'bookings/show/' . $id, 'active' => true],
            ],
        ]);
    }

    // Hiển thị form thêm booking mới
    public function create(): void
    {
        requireAdmin();

        $tourList = Booking::getTourList();
        $guideList = Booking::getGuideList();

        view('admin.bookings.create', [
            'title' => 'Thêm Booking Mới',
            'pageTitle' => 'Thêm Booking Mới',
            'tourList' => $tourList,
            'guideList' => $guideList,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách booking', 'url' => BASE_URL . 'bookings'],
                ['label' => 'Thêm booking mới', 'url' => BASE_URL . 'bookings/create', 'active' => true],
            ],
        ]);
    }

    // Lưu booking mới
    public function store(): void
    {
        // Chỉ admin mới được tạo booking
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'bookings/create');
            exit;
        }

        // Lấy dữ liệu từ form
        $tai_khoan_id = null; // Không cần khách hàng từ database
        $assigned_hdv_id = trim($_POST['assigned_hdv_id'] ?? '');
        $tour_id = trim($_POST['tour_id'] ?? '');
        $loai_khach = trim($_POST['loai_khach'] ?? 'le');
        $ten_nguoi_dat = trim($_POST['ten_nguoi_dat'] ?? '');
        $so_luong = (int)($_POST['so_luong'] ?? 1);
        $thoi_gian_tour = trim($_POST['thoi_gian_tour'] ?? '');
        $lien_he = trim($_POST['lien_he'] ?? '');
        $yeu_cau_dac_biet = trim($_POST['yeu_cau_dac_biet'] ?? '');
        $trang_thai = trim($_POST['trang_thai'] ?? 'cho_xac_nhan');
        $ghi_chu = trim($_POST['ghi_chu'] ?? '');

        // Kiểm tra dữ liệu
        $errors = [];
        if (empty($tour_id)) $errors[] = 'Vui lòng chọn tour';
        if (empty($ten_nguoi_dat)) $errors[] = 'Vui lòng nhập tên người đặt';
        if ($so_luong <= 0) $errors[] = 'Số lượng phải lớn hơn 0';
        if (empty($thoi_gian_tour)) $errors[] = 'Vui lòng chọn thời gian tour';
        if (empty($lien_he)) $errors[] = 'Vui lòng nhập thông tin liên hệ';

        if (!empty($errors)) {
            $tourList = Booking::getTourList();
            $guideList = Booking::getGuideList();

            view('admin.bookings.create', [
                'title' => 'Thêm booking mới',
                'pageTitle' => 'Thêm booking mới',
                'tourList' => $tourList,
                'guideList' => $guideList,
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách booking', 'url' => BASE_URL . 'bookings'],
                    ['label' => 'Thêm booking mới', 'url' => BASE_URL . 'bookings/create', 'active' => true],
                ],
            ]);
            return;
        }

        // Tạo booking mới
        $booking = new Booking([
            'tai_khoan_id' => $tai_khoan_id,
            'assigned_hdv_id' => $assigned_hdv_id ?: null,
            'tour_id' => $tour_id,
            'loai_khach' => $loai_khach,
            'ten_nguoi_dat' => $ten_nguoi_dat,
            'so_luong' => $so_luong,
            'thoi_gian_tour' => $thoi_gian_tour,
            'lien_he' => $lien_he,
            'yeu_cau_dac_biet' => $yeu_cau_dac_biet,
            'trang_thai' => $trang_thai,
            'ghi_chu' => $ghi_chu
        ]);

        $bookingId = Booking::create($booking);
        if ($bookingId) {
            $_SESSION['success'] = 'Thêm booking mới thành công';
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        } else {
            $_SESSION['error'] = 'Thêm booking mới thất bại';
            header('Location: ' . BASE_URL . 'bookings/create');
            exit;
        }
    }

    // Hiển thị form sửa booking
    public function edit($id): void
    {
        requireAdmin();

        $booking = Booking::find($id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        $tourList = Booking::getTourList();
        $guideList = Booking::getGuideList();

        view('admin.bookings.edit', [
            'title' => 'Sửa Booking',
            'pageTitle' => 'Sửa Booking',
            'booking' => $booking,
            'tourList' => $tourList,
            'guideList' => $guideList,
            'errors' => [], // Đảm bảo không có lỗi khi vào trang edit lần đầu
            'old' => [], // Đảm bảo không có old data khi vào trang edit lần đầu
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách booking', 'url' => BASE_URL . 'bookings'],
                ['label' => 'Sửa booking', 'url' => BASE_URL . 'bookings/edit/' . $id, 'active' => true],
            ],
        ]);
    }

    // Cập nhật booking
    public function update(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        // Lấy dữ liệu từ form
        $tai_khoan_id = null; // Không cần khách hàng từ database
        $assigned_hdv_id = trim($_POST['assigned_hdv_id'] ?? '');
        $tour_id = trim($_POST['tour_id'] ?? '');
        $loai_khach = trim($_POST['loai_khach'] ?? 'le');
        $ten_nguoi_dat = trim($_POST['ten_nguoi_dat'] ?? '');
        $so_luong = (int)($_POST['so_luong'] ?? 1);
        $thoi_gian_tour = trim($_POST['thoi_gian_tour'] ?? '');
        $lien_he = trim($_POST['lien_he'] ?? '');
        $yeu_cau_dac_biet = trim($_POST['yeu_cau_dac_biet'] ?? '');
        $trang_thai = trim($_POST['trang_thai'] ?? 'cho_xac_nhan');
        $ghi_chu = trim($_POST['ghi_chu'] ?? '');

        // Kiểm tra dữ liệu
        $errors = [];
        if (empty($tour_id)) $errors[] = 'Vui lòng chọn tour';
        if (empty($ten_nguoi_dat)) $errors[] = 'Vui lòng nhập tên người đặt';
        if ($so_luong <= 0) $errors[] = 'Số lượng phải lớn hơn 0';
        if (empty($thoi_gian_tour)) $errors[] = 'Vui lòng chọn thời gian tour';
        if (empty($lien_he)) $errors[] = 'Vui lòng nhập thông tin liên hệ';

        if (!empty($errors)) {
            $booking = Booking::find($id);
            $tourList = Booking::getTourList();
            $guideList = Booking::getGuideList();

            view('admin.bookings.edit', [
                'title' => 'Sửa booking',
                'pageTitle' => 'Sửa booking',
                'booking' => $booking,
                'tourList' => $tourList,
                'guideList' => $guideList,
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách booking', 'url' => BASE_URL . 'bookings'],
                    ['label' => 'Sửa booking', 'url' => BASE_URL . 'bookings/edit/' . $id, 'active' => true],
                ],
            ]);
            return;
        }

        // Tạo booking để cập nhật
        $booking = new Booking([
            'id' => $id,
            'tai_khoan_id' => $tai_khoan_id,
            'assigned_hdv_id' => !empty($assigned_hdv_id) ? (int)$assigned_hdv_id : null,
            'tour_id' => !empty($tour_id) ? (int)$tour_id : null,
            'loai_khach' => $loai_khach,
            'ten_nguoi_dat' => $ten_nguoi_dat,
            'so_luong' => $so_luong,
            'thoi_gian_tour' => $thoi_gian_tour,
            'lien_he' => $lien_he,
            'yeu_cau_dac_biet' => $yeu_cau_dac_biet,
            'trang_thai' => $trang_thai,
            'ghi_chu' => $ghi_chu
        ]);

        $result = Booking::update($booking);
        if ($result) {
            $_SESSION['success'] = 'Cập nhật booking thành công';
            // Đảm bảo không có output trước header
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Location: ' . BASE_URL . 'bookings');
            exit();
        } else {
            $_SESSION['error'] = 'Cập nhật booking thất bại. Vui lòng kiểm tra lại dữ liệu.';
            // Đảm bảo không có output trước header
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Location: ' . BASE_URL . 'bookings/edit/' . $id);
            exit();
        }
    }

    // Xóa booking
    public function delete(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        if (Booking::delete($id)) {
            $_SESSION['success'] = 'Đã xóa booking thành công';
        } else {
            $_SESSION['error'] = 'Không thể xóa booking này do có dữ liệu liên quan hoặc lỗi hệ thống';
        }
        header('Location: ' . BASE_URL . 'bookings');
        exit;
    }

    // Import danh sách khách hàng từ file Excel
    public function importKhach(): void
    {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        $booking_id = $_POST['booking_id'] ?? null;
        if (!$booking_id) {
            $_SESSION['error'] = 'ID booking không hợp lệ';
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        $booking = Booking::find($booking_id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        // Kiểm tra file upload
        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Vui lòng chọn file Excel/CSV để import';
            header('Location: ' . BASE_URL . 'bookings/edit/' . $booking_id);
            exit;
        }

        $file = $_FILES['excel_file'];
        $allowedExtensions = ['csv', 'xls', 'xlsx'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            $_SESSION['error'] = 'File không hợp lệ. Chỉ chấp nhận file CSV, XLS, XLSX';
            header('Location: ' . BASE_URL . 'bookings/edit/' . $booking_id);
            exit;
        }

        // Lưu file tạm
        $tempFile = sys_get_temp_dir() . '/' . uniqid() . '_' . $file['name'];
        if (!move_uploaded_file($file['tmp_name'], $tempFile)) {
            $_SESSION['error'] = 'Không thể upload file';
            header('Location: ' . BASE_URL . 'bookings/edit/' . $booking_id);
            exit;
        }

        // Đọc file Excel/CSV
        try {
            $data = readExcelFile($tempFile, 2); // Bắt đầu từ dòng 2 (bỏ header)
        } catch (Exception $e) {
            @unlink($tempFile);
            $_SESSION['error'] = 'Lỗi đọc file: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'bookings/edit/' . $booking_id);
            exit;
        }

        // Xóa file tạm
        @unlink($tempFile);

        if ($data === null || empty($data)) {
            $_SESSION['error'] = 'Không thể đọc file hoặc file trống. Vui lòng kiểm tra định dạng file.';
            header('Location: ' . BASE_URL . 'bookings/edit/' . $booking_id);
            exit;
        }

        // Import dữ liệu
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            // Format: [Họ tên, Giới tính, Năm sinh, Số giấy tờ, Yêu cầu cá nhân]
            $ho_ten = trim($row[0] ?? '');
            if (empty($ho_ten)) {
                $errorCount++;
                $errors[] = "Dòng " . ($index + 2) . ": Thiếu họ tên";
                continue;
            }

            $gioi_tinh = trim($row[1] ?? '');
            $nam_sinh = !empty($row[2]) ? (int)$row[2] : null;
            $so_giay_to = trim($row[3] ?? '');
            $yeu_cau_ca_nhan = trim($row[4] ?? '');
            
            // Mặc định tình trạng thanh toán là chưa thanh toán
            $tinh_trang_thanh_toan = 'chua_thanh_toan';

            // Thêm khách hàng
            if ($booking->addKhach($ho_ten, $gioi_tinh, $nam_sinh, $so_giay_to, $tinh_trang_thanh_toan, $yeu_cau_ca_nhan)) {
                $successCount++;
            } else {
                $errorCount++;
                $errors[] = "Dòng " . ($index + 2) . ": Không thể thêm khách hàng " . $ho_ten;
            }
        }

        // Thông báo kết quả
        if ($successCount > 0) {
            $_SESSION['success'] = "Import thành công {$successCount} khách hàng";
            if ($errorCount > 0) {
                $_SESSION['error'] = "Có {$errorCount} khách hàng không thể import. " . implode(', ', array_slice($errors, 0, 5));
            }
            // Redirect về trang show để xem kết quả
            header('Location: ' . BASE_URL . 'bookings/show/' . $booking_id);
        } else {
            $_SESSION['error'] = "Không thể import khách hàng nào. " . implode(', ', array_slice($errors, 0, 5));
            // Nếu không import được gì, quay lại trang edit
            header('Location: ' . BASE_URL . 'bookings/edit/' . $booking_id);
        }
        exit;
    }
}
?>
