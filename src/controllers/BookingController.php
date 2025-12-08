<?php
class BookingController
{
    /**
     * Lấy danh sách tour và HDV dùng chung cho form
     */
    private function getFormLists(): array
    {
        return [
            'tourList' => Booking::getTourList(),
            'guideList' => Booking::getGuideList(),
        ];
    }

    private function renderCreateWithErrors(array $errors, array $old = []): void
    {
        $lists = $this->getFormLists();
        view('admin.bookings.create', [
            'title' => 'Thêm booking mới',
            'pageTitle' => 'Thêm booking mới',
            'tourList' => $lists['tourList'],
            'guideList' => $lists['guideList'],
            'errors' => $errors,
            'old' => $old,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách booking', 'url' => BASE_URL . 'bookings'],
                ['label' => 'Thêm booking mới', 'url' => BASE_URL . 'bookings/create', 'active' => true],
            ],
        ]);
    }

    private function renderEditWithErrors($id, array $errors, array $old): void
    {
        $booking = Booking::find($id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'bookings');
            exit;
        }

        $lists = $this->getFormLists();
        $hdvs = $booking->getHdvs();
        $currentHdv = !empty($hdvs) ? $hdvs[0] : null;
        $currentKhachs = $booking->getKhachs();
        $lichKhoiHanhId = $booking->lich_khoi_hanh_id ?: Booking::getLichKhoiHanhIdByBookingId($id);
        $diemDanh = Booking::getDiemDanhByBooking($id, $lichKhoiHanhId);

        view('admin.bookings.edit', [
            'title' => 'Sửa booking',
            'pageTitle' => 'Sửa booking',
            'booking' => $booking,
            'tourList' => $lists['tourList'],
            'guideList' => $lists['guideList'],
            'currentHdv' => $currentHdv,
            'currentKhachs' => $currentKhachs,
            'diemDanh' => $diemDanh,
            'errors' => $errors,
            'old' => $old,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách booking', 'url' => BASE_URL . 'bookings'],
                ['label' => 'Sửa booking', 'url' => BASE_URL . 'bookings/edit/' . $id, 'active' => true],
            ],
        ]);
    }

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

        $hdvs = $booking->getHdvs();
        $khachs = $booking->getKhachs();
        $lichKhoiHanhId = $booking->lich_khoi_hanh_id ?: Booking::getLichKhoiHanhIdByBookingId($id);
        $diemDanh = Booking::getDiemDanhByBooking($id, $lichKhoiHanhId);

        view('admin.bookings.show', [
            'title' => 'Chi tiết Booking - ' . $booking->ten_nguoi_dat,
            'pageTitle' => 'Chi tiết Booking',
            'booking' => $booking,
            'hdvs' => $hdvs,
            'khachs' => $khachs,
            'diemDanh' => $diemDanh,
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
        $ngay_gio_xuat_phat = trim($_POST['ngay_gio_xuat_phat'] ?? '');
        $diem_tap_trung = trim($_POST['diem_tap_trung'] ?? '');
        $thoi_gian_ket_thuc = trim($_POST['thoi_gian_ket_thuc'] ?? '');
        $ngay_gio_xuat_phat = trim($_POST['ngay_gio_xuat_phat'] ?? '');
        $diem_tap_trung = trim($_POST['diem_tap_trung'] ?? '');
        $thoi_gian_ket_thuc = trim($_POST['thoi_gian_ket_thuc'] ?? '');

        // Kiểm tra dữ liệu
        $errors = [];
        if (empty($tour_id)) $errors[] = 'Vui lòng chọn tour';
        if (empty($ten_nguoi_dat)) $errors[] = 'Vui lòng nhập tên người đặt';
        if ($so_luong <= 0) $errors[] = 'Số lượng phải lớn hơn 0';
        if (empty($thoi_gian_tour)) $errors[] = 'Vui lòng chọn thời gian tour';
        if (empty($lien_he)) $errors[] = 'Vui lòng nhập thông tin liên hệ';

        if (!empty($errors)) {
            $this->renderCreateWithErrors($errors, $_POST);
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
            // Xử lý thêm HDV vào booking_hdv nếu có
            $hdv_id = trim($_POST['hdv_id'] ?? '');
            $vai_tro = trim($_POST['vai_tro'] ?? 'hdv');
            $chi_tiet = trim($_POST['chi_tiet'] ?? '');
            
            if (!empty($hdv_id)) {
                $booking->id = $bookingId;
                $booking->addHdv($hdv_id, $vai_tro, $chi_tiet);
            }
            
            // Xử lý thêm khách hàng đại diện
            if (isset($_POST['khach']) && is_array($_POST['khach']) && !empty($_POST['khach'])) {
                $khachData = $_POST['khach'][0]; // Lấy khách hàng đầu tiên (người đại diện)
                $ho_ten = trim($khachData['ho_ten'] ?? '');
                $gioi_tinh = trim($khachData['gioi_tinh'] ?? '');
                $nam_sinh = !empty($khachData['nam_sinh']) ? (int)$khachData['nam_sinh'] : null;
                $so_giay_to = trim($khachData['so_giay_to'] ?? '');
                $tinh_trang_thanh_toan = trim($khachData['tinh_trang_thanh_toan'] ?? 'chua_thanh_toan');
                $yeu_cau_ca_nhan = trim($khachData['yeu_cau_ca_nhan'] ?? '');
                
                if (!empty($ho_ten)) {
                    $booking->addKhach(
                        $ho_ten,
                        $gioi_tinh ?: null,
                        $nam_sinh,
                        $so_giay_to ?: null,
                        $tinh_trang_thanh_toan,
                        $yeu_cau_ca_nhan ?: null
                    );
                }
            }

            // Lưu lịch khởi hành (ngay_gio_xuat_phat, diem_tap_trung, thoi_gian_ket_thuc)
            Booking::upsertLichKhoiHanh(
                $bookingId,
                $ngay_gio_xuat_phat ?: null,
                $diem_tap_trung ?: null,
                $thoi_gian_ket_thuc ?: null
            );
            
            // Xử lý upload file danh sách khách hàng
            if (isset($_FILES['guest_list_file']) && $_FILES['guest_list_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['guest_list_file'];
                $allowedExtensions = ['xlsx', 'xls', 'csv'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $allowedExtensions)) {
                    $uploadDir = BASE_PATH . '/public/uploads/guest_lists/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    // Xóa file cũ nếu có (booking_{id}.*)
                    $oldFiles = glob($uploadDir . 'booking_' . $bookingId . '.*');
                    foreach ($oldFiles as $oldFile) {
                        if (is_file($oldFile)) {
                            unlink($oldFile);
                        }
                    }
                    
                    // Đổi tên file thành booking_{id}.extension
                    $newFileName = 'booking_' . $bookingId . '.' . $extension;
                    $filePath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        // File uploaded successfully
                    }
                }
            }
            
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

        // Lấy HDV hiện tại (nếu có)
        $currentHdv = null;
        $hdvs = $booking->getHdvs();
        if (!empty($hdvs)) {
            $currentHdv = $hdvs[0]; // Lấy HDV đầu tiên
        }

        // Lấy khách hàng hiện tại (người đại diện)
        $currentKhachs = $booking->getKhachs();
        $lichKhoiHanhId = $booking->lich_khoi_hanh_id ?: Booking::getLichKhoiHanhIdByBookingId($id);
        $diemDanh = Booking::getDiemDanhByBooking($id, $lichKhoiHanhId);

        view('admin.bookings.edit', [
            'title' => 'Sửa Booking',
            'pageTitle' => 'Sửa Booking',
            'booking' => $booking,
            'tourList' => $tourList,
            'guideList' => $guideList,
            'currentHdv' => $currentHdv,
            'currentKhachs' => $currentKhachs,
            'diemDanh' => $diemDanh,
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

        // Lấy dữ liệu cũ trước khi update (để so sánh và ghi log)
        $oldBooking = Booking::find($id);
        if (!$oldBooking) {
            $_SESSION['error'] = 'Booking không tồn tại';
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
            $this->renderEditWithErrors($id, $errors, $_POST);
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
            // Xử lý cập nhật HDV trong booking_hdv
            $hdv_id = trim($_POST['hdv_id'] ?? '');
            $vai_tro = trim($_POST['vai_tro'] ?? 'hdv');
            $chi_tiet = trim($_POST['chi_tiet'] ?? '');
            
            // Lấy danh sách HDV hiện tại
            $existingHdvs = $booking->getHdvs();
            
            if (!empty($hdv_id)) {
                // Nếu có HDV mới được chọn
                if (!empty($existingHdvs) && isset($existingHdvs[0]['hdv_id']) && $existingHdvs[0]['hdv_id'] == $hdv_id) {
                    // Cùng HDV, chỉ cập nhật vai_tro và chi_tiet
                    if (!empty($existingHdvs[0]['id'])) {
                        Booking::updateHdv($existingHdvs[0]['id'], $hdv_id, $vai_tro, $chi_tiet);
                    }
                } else {
                    // HDV khác hoặc chưa có, xóa cũ và thêm mới
                    foreach ($existingHdvs as $existingHdv) {
                        if (!empty($existingHdv['id'])) {
                            Booking::deleteHdv($existingHdv['id']);
                        }
                    }
                    $booking->addHdv($hdv_id, $vai_tro, $chi_tiet);
                }
            } else {
                // Không chọn HDV, xóa tất cả
                foreach ($existingHdvs as $existingHdv) {
                    if (!empty($existingHdv['id'])) {
                        Booking::deleteHdv($existingHdv['id']);
                    }
                }
            }
            
            // Xử lý cập nhật thông tin khách hàng (người đại diện)
            if (isset($_POST['khach']) && is_array($_POST['khach'])) {
                $khachs = $_POST['khach'];
                $existingKhachs = $booking->getKhachs();
                
                // Lấy khách hàng đầu tiên (người đại diện)
                if (!empty($khachs)) {
                    $khachData = $khachs[0];
                    $khach_id = $khachData['id'] ?? null;
                    $ho_ten = trim($khachData['ho_ten'] ?? '');
                    $gioi_tinh = trim($khachData['gioi_tinh'] ?? '');
                    $nam_sinh = !empty($khachData['nam_sinh']) ? (int)$khachData['nam_sinh'] : null;
                    $so_giay_to = trim($khachData['so_giay_to'] ?? '');
                    $tinh_trang_thanh_toan = trim($khachData['tinh_trang_thanh_toan'] ?? 'chua_thanh_toan');
                    $yeu_cau_ca_nhan = trim($khachData['yeu_cau_ca_nhan'] ?? '');
                    
                    if (!empty($ho_ten)) {
                        if ($khach_id && !empty($existingKhachs)) {
                            // Cập nhật khách hàng hiện có
                            Booking::updateKhach(
                                $khach_id,
                                $ho_ten,
                                $gioi_tinh ?: null,
                                $nam_sinh,
                                $so_giay_to ?: null,
                                $tinh_trang_thanh_toan,
                                $yeu_cau_ca_nhan ?: null
                            );
                        } else {
                            // Thêm mới nếu chưa có
                            $booking->addKhach(
                                $ho_ten,
                                $gioi_tinh ?: null,
                                $nam_sinh,
                                $so_giay_to ?: null,
                                $tinh_trang_thanh_toan,
                                $yeu_cau_ca_nhan ?: null
                            );
                        }
                    }
                }
            }

            // Xử lý điểm danh khách (nếu có gửi lên)
            $lichKhoiHanhId = $oldBooking->lich_khoi_hanh_id ?: Booking::getLichKhoiHanhIdByBookingId($id);
            if (!empty($lichKhoiHanhId) && isset($_POST['attendance']) && is_array($_POST['attendance'])) {
                foreach ($_POST['attendance'] as $att) {
                    $bkId = (int)($att['booking_khach_id'] ?? 0);
                    $status = trim($att['trang_thai'] ?? '');
                    $note = trim($att['ghi_chu'] ?? '');
                    $lkhRow = !empty($att['lich_khoi_hanh_id']) ? (int)$att['lich_khoi_hanh_id'] : $lichKhoiHanhId;
                    if (!$bkId) {
                        continue;
                    }
                    // Chỉ nhận các trạng thái hợp lệ
                    $allowed = ['da_den', 'vang', 'vang_mat', 'tre'];
                    if (!in_array($status, $allowed, true)) {
                        $status = null;
                    }
                    if ($status && $lkhRow) {
                        Booking::upsertDiemDanh($lkhRow, $bkId, $status, $note ?: null);
                    }
                }
            }

            // Lưu/ cập nhật lịch khởi hành
            Booking::upsertLichKhoiHanh(
                $id,
                $ngay_gio_xuat_phat ?: null,
                $diem_tap_trung ?: null,
                $thoi_gian_ket_thuc ?: null
            );
            
            // Xử lý upload file danh sách khách hàng
            if (isset($_FILES['guest_list_file']) && $_FILES['guest_list_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['guest_list_file'];
                $allowedExtensions = ['xlsx', 'xls', 'csv'];
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                if (in_array($extension, $allowedExtensions)) {
                    $uploadDir = BASE_PATH . '/public/uploads/guest_lists/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    // Xóa file cũ nếu có (booking_{id}.*)
                    $oldFiles = glob($uploadDir . 'booking_' . $id . '.*');
                    foreach ($oldFiles as $oldFile) {
                        if (is_file($oldFile)) {
                            unlink($oldFile);
                        }
                    }
                    
                    // Đổi tên file thành booking_{id}.extension
                    $newFileName = 'booking_' . $id . '.' . $extension;
                    $filePath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($file['tmp_name'], $filePath)) {
                        // File uploaded successfully
                    }
                }
            }
            
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
            // Xóa file upload danh sách khách hàng nếu có
            $uploadDir = BASE_PATH . '/public/uploads/guest_lists/';
            $filePattern = $uploadDir . 'booking_' . $id . '.*';
            $existingFiles = glob($filePattern);
            foreach ($existingFiles as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            
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
