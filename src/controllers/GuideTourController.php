<?php
class GuideTourController
{
    // Danh sách booking của HDV hiện tại
    public function myBookings(): void
    {
        requireGuideOrAdmin();

        // Lấy user hiện tại
        $user = getCurrentUser();
        if (!$user || !isGuide()) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy danh sách booking được gán cho HDV này
        $pdo = getDB();
        
        // Lấy HDV id từ tai_khoan_id để kiểm tra booking_hdv
        require_once __DIR__ . '/../models/HDV.php';
        $hdv = HDV::findByTaiKhoanId($user->id);
        $hdvId = $hdv ? $hdv->id : null;
        
        // Lấy booking chỉ qua bảng booking_hdv (đúng HDV đang đăng nhập)
        if ($hdvId) {
            $sql = "SELECT DISTINCT b.*, t.ten_tour, t.gia AS gia_tour
                    FROM booking b
                    INNER JOIN booking_hdv bh ON b.id = bh.booking_id
                    LEFT JOIN tour t ON b.tour_id = t.id
                    WHERE bh.hdv_id = ?
                    AND b.trang_thai IN ('cho_xac_nhan', 'da_coc', 'da_thanh_toan', 'da_huy', 'hoan_thanh')
                    ORDER BY b.ngay_tao DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$hdvId]);
        } else {
            // Không có HDV tương ứng với tài khoản => không có booking nào
            $bookings = [];
        }
        $bookings = [];
        foreach($stmt->fetchAll() as $row){
            $bookings[] = new Booking($row);
        }
        
        // Lấy thông tin lịch khởi hành cho mỗi booking
        foreach($bookings as $booking){
            $lichKhoiHanh = Booking::getLatestLichKhoiHanh($booking->id);
            if($lichKhoiHanh){
                $booking->lich_khoi_hanh_id = $lichKhoiHanh['id'] ?? null;
                $booking->ngay_gio_xuat_phat = $lichKhoiHanh['ngay_gio_xuat_phat'] ?? null;
                $booking->diem_tap_trung = $lichKhoiHanh['diem_tap_trung'] ?? null;
                $booking->thoi_gian_ket_thuc = $lichKhoiHanh['thoi_gian_ket_thuc'] ?? null;
            }
        }

        view('guide.my-bookings', [
            'title' => 'Danh sách Tour của tôi',
            'pageTitle' => 'Danh sách Tour của tôi',
            'bookings' => $bookings,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide/my-bookings', 'active' => true],
            ],
        ]);
    }

    // Chi tiết booking và danh sách khách
    public function viewBooking($id): void
    {
        requireGuideOrAdmin();

        // Lấy user hiện tại
        $user = getCurrentUser();
        if (!$user || !isGuide()) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy booking
        $booking = Booking::find($id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy danh sách khách, lịch khởi hành, điểm danh
        $khachs = $booking->getKhachs();
        $lichKhoiHanhId = $booking->lich_khoi_hanh_id ?: Booking::getLichKhoiHanhIdByBookingId($id);
        $diemDanh = Booking::getDiemDanhByBooking($id, $lichKhoiHanhId);

        // Đọc danh sách khách từ file Excel đã upload 
        $guestListFromFile = [];
        $uploadDir = BASE_PATH . '/public/uploads/guest_lists/';
        $filePattern = $uploadDir . 'booking_' . $id . '.*';
        $files = glob($filePattern);
        // Tạo map họ tên -> booking_khach_id để gắn check-in nếu trùng tên
        $khachNameMap = [];
        foreach ($khachs as $k) {
            $nameKey = trim(mb_strtolower($k['ho_ten'] ?? ''));
            if ($nameKey !== '') {
                $khachNameMap[$nameKey] = $k['id'];
            }
        }

        if (!empty($files) && is_file($files[0])) {
            $filePath = $files[0];
            try {
                $rawRows = readExcelFile($filePath, 2); // bỏ header, bắt đầu dòng 2
                foreach ($rawRows as $row) {
                    $hoTen = trim($row[0] ?? '');
                    $nameKey = mb_strtolower($hoTen);
                    $bookingKhachId = $khachNameMap[$nameKey] ?? null;

                    $guestListFromFile[] = [
                        'ho_ten' => trim($row[0] ?? ''),
                        'gioi_tinh' => trim($row[1] ?? ''),
                        'nam_sinh' => trim($row[2] ?? ''),
                        'so_giay_to' => trim($row[3] ?? ''),
                        'yeu_cau_ca_nhan' => trim($row[4] ?? ''),
                        'ghi_chu_file' => trim($row[5] ?? ''),
                        'booking_khach_id' => $bookingKhachId,
                    ];
                }
            } catch (Exception $e) {
                // Nếu lỗi đọc file, bỏ qua để không ảnh hưởng trang
            }
        }

        // Lấy lịch trình tour
        $tour = Tour::find($booking->tour_id);
        $lichTrinh = [];
        if ($tour) {
            $lichTrinh = $tour->getLichTrinh();
        }

        view('guide.booking-detail', [
            'title' => 'Chi tiết Tour: ' . $booking->ten_tour,
            'pageTitle' => 'Chi tiết Tour',
            'booking' => $booking,
            'khachs' => $khachs,
            'diemDanh' => $diemDanh,
            'lichKhoiHanhId' => $lichKhoiHanhId,
            'lichTrinh' => $lichTrinh,
            'guestListFromFile' => $guestListFromFile,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide/my-bookings'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide/booking/' . $id, 'active' => true],
            ],
        ]);
    }

    // Xử lý check-in khách
    public function checkIn(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy user hiện tại
        $user = getCurrentUser();
        if (!$user || !isGuide()) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy dữ liệu từ form
        $bookingId = $_POST['booking_id'] ?? null;
        $lichKhoiHanhId = $_POST['lich_khoi_hanh_id'] ?? null;
        $bookingKhachId = $_POST['booking_khach_id'] ?? null;
        $trangThai = trim($_POST['trang_thai'] ?? 'da_den');

        // Thông tin khách (trường hợp chưa có trong hệ thống)
        $hoTen = trim($_POST['ho_ten'] ?? '');
        $gioiTinh = trim($_POST['gioi_tinh'] ?? '');
        $namSinh = trim($_POST['nam_sinh'] ?? '');
        $soGiayTo = trim($_POST['so_giay_to'] ?? '');
        $yeuCauCaNhan = trim($_POST['yeu_cau_ca_nhan'] ?? '');

        if (!$bookingId || !$lichKhoiHanhId || empty($trangThai)) {
            $_SESSION['error'] = 'Thông tin không hợp lệ';
            header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
            exit;
        }

        // Lấy booking
        $booking = Booking::find($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Nếu chưa có booking_khach_id, tạo mới từ dữ liệu gửi lên
        if (empty($bookingKhachId)) {
            if (empty($hoTen)) {
                $_SESSION['error'] = 'Thiếu thông tin khách để check-in';
                header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
                exit;
            }
            $booking->addKhach(
                $hoTen,
                $gioiTinh ?: null,
                !empty($namSinh) ? (int)$namSinh : null,
                $soGiayTo ?: null,
                'chua_thanh_toan',
                $yeuCauCaNhan ?: null
            );
            // Lấy lại danh sách khách để tìm ID vừa tạo
            $khachsTmp = $booking->getKhachs();
            foreach ($khachsTmp as $k) {
                if (trim(mb_strtolower($k['ho_ten'])) === trim(mb_strtolower($hoTen))) {
                    $bookingKhachId = $k['id'];
                    break;
                }
            }
        }

        if (!$bookingKhachId) {
            $_SESSION['error'] = 'Không xác định được khách để check-in';
            header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
            exit;
        }

        // Lưu điểm danh (check-in hoặc cập nhật)
        if (Booking::upsertDiemDanh($lichKhoiHanhId, $bookingKhachId, $trangThai, $ghiChu)) {
            $_SESSION['success'] = 'Check-in thành công';
        } else {
            $_SESSION['error'] = 'Check-in thất bại';
        }

        header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
        exit;
    }

    // Cập nhật yêu cầu đặc biệt của khách
    public function updateYeuCau(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy user hiện tại
        $user = getCurrentUser();
        if (!$user || !isGuide()) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy dữ liệu từ form
        $bookingId = $_POST['booking_id'] ?? null;
        $bookingKhachId = $_POST['booking_khach_id'] ?? null;
        $yeuCauCaNhan = trim($_POST['yeu_cau_ca_nhan'] ?? '');

        if (!$bookingId || !$bookingKhachId) {
            $_SESSION['error'] = 'Thông tin không hợp lệ';
            header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
            exit;
        }

        // Lấy booking
        $booking = Booking::find($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy thông tin khách hiện tại để cập nhật
        $pdo = getDB();
        $sql = "SELECT * FROM booking_khach WHERE id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$bookingKhachId]);
        $khach = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$khach) {
            $_SESSION['error'] = 'Không tìm thấy thông tin khách';
            header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
            exit;
        }

        // Cập nhật yêu cầu đặc biệt
        if (Booking::updateKhach(
            $bookingKhachId,
            $khach['ho_ten'],
            $khach['gioi_tinh'],
            $khach['nam_sinh'],
            $khach['so_giay_to'],
            $khach['tinh_trang_thanh_toan'],
            $yeuCauCaNhan
        )) {
            $_SESSION['success'] = 'Cập nhật yêu cầu đặc biệt thành công';
        } else {
            $_SESSION['error'] = 'Cập nhật yêu cầu đặc biệt thất bại';
        }

        header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
        exit;
    }

    // Cập nhật yêu cầu đặc biệt của cả đoàn
    public function updateYeuCauDoan(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy user hiện tại
        $user = getCurrentUser();
        if (!$user || !isGuide()) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy dữ liệu từ form
        $bookingId = $_POST['booking_id'] ?? null;
        $yeuCauDacBiet = trim($_POST['yeu_cau_dac_biet'] ?? '');

        if (!$bookingId) {
            $_SESSION['error'] = 'Thông tin không hợp lệ';
            header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
            exit;
        }

        // Lấy booking
        $booking = Booking::find($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Cập nhật yêu cầu đặc biệt của cả đoàn
        $booking->yeu_cau_dac_biet = $yeuCauDacBiet;
        if (Booking::update($booking)) {
            $_SESSION['success'] = 'Cập nhật yêu cầu đặc biệt của cả đoàn thành công';
        } else {
            $_SESSION['error'] = 'Cập nhật yêu cầu đặc biệt của cả đoàn thất bại';
        }

        header('Location: ' . BASE_URL . 'guide/booking/' . $bookingId);
        exit;
    }

    // Danh sách nhật ký tour
    public function nhatKyDanhSach($bookingId): void
    {
        requireGuideOrAdmin();

        // Lấy user hiện tại
        $user = getCurrentUser();
        if (!$user || !isGuide()) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy booking
        $booking = Booking::find($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy danh sách nhật ký
        $nhatKys = NhatKyTour::getByBookingId($bookingId);

        view('guide.diary-list', [
            'title' => 'Nhật ký Tour: ' . $booking->ten_tour,
            'pageTitle' => 'Nhật ký Tour',
            'booking' => $booking,
            'nhatKys' => $nhatKys,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide/my-bookings'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide/booking/' . $bookingId],
                ['label' => 'Nhật ký tour', 'url' => BASE_URL . 'guide/diary/' . $bookingId, 'active' => true],
            ],
        ]);
    }

    // Form tạo nhật ký mới
    public function nhatKyTao($bookingId): void
    {
        requireGuideOrAdmin();

        // Lấy user hiện tại
        $user = getCurrentUser();
        if (!$user || !isGuide()) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy booking
        $booking = Booking::find($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        view('guide.diary-form', [
            'title' => 'Thêm nhật ký tour',
            'pageTitle' => 'Thêm nhật ký tour',
            'booking' => $booking,
            'nhatKy' => null,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide/my-bookings'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide/booking/' . $bookingId],
                ['label' => 'Nhật ký tour', 'url' => BASE_URL . 'guide/diary/' . $bookingId],
                ['label' => 'Thêm nhật ký', 'url' => BASE_URL . 'guide/diary/create/' . $bookingId, 'active' => true],
            ],
        ]);
    }

    // lưu nhật ký mới
    public function nhatKyLuu(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy HDV ID từ user hiện tại
        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy dữ liệu từ form
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$bookingId) {
            $_SESSION['error'] = 'Booking ID không hợp lệ';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy booking
        $booking = Booking::find($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }
        // Lấy dữ liệu từ form
        $ngay_gio = trim($_POST['ngay_gio'] ?? '');
        if (!empty($ngay_gio)) {
            $ngay_gio = str_replace('T', ' ', $ngay_gio);
        } else {
            $ngay_gio = date('Y-m-d H:i:s');
        }
        $noi_dung = trim($_POST['noi_dung'] ?? '');
        $danh_gia_hdv = trim($_POST['danh_gia_hdv'] ?? '');

        // Kiểm tra dữ liệu
        if (empty($noi_dung)) {
            $_SESSION['error'] = 'Vui lòng nhập nội dung nhật ký';
            header('Location: ' . BASE_URL . 'guide/diary/create/' . $bookingId);
            exit;
        }

        // Tạo nhật ký mới
        $nhatKy = new NhatKyTour([
            'booking_id' => $bookingId,
            'ngay_gio' => $ngay_gio,
            'noi_dung' => $noi_dung,
            'danh_gia_hdv' => $danh_gia_hdv
        ]);

        if (NhatKyTour::create($nhatKy)) {
            $_SESSION['success'] = 'Thêm nhật ký thành công';
            header('Location: ' . BASE_URL . 'guide/diary/' . $bookingId);
        } else {
            $_SESSION['error'] = 'Thêm nhật ký thất bại';
            header('Location: ' . BASE_URL . 'guide/diary/create/' . $bookingId);
        }
        exit;
    }

    // Form sửa nhật ký
    public function nhatKySua($id): void
    {
        requireGuideOrAdmin();

        // Lấy HDV ID từ user hiện tại
        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy nhật ký, booking
        $nhatKy = NhatKyTour::find($id);
        if (!$nhatKy) {
            $_SESSION['error'] = 'Nhật ký không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

       $booking = Booking::find($nhatKy->booking_id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        view('guide.diary-form', [
            'title' => 'Sửa nhật ký tour',
            'pageTitle' => 'Sửa nhật ký tour',
            'booking' => $booking,
            'nhatKy' => $nhatKy,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide/my-bookings'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide/booking/' . $booking->id],
                ['label' => 'Nhật ký tour', 'url' => BASE_URL . 'guide/diary/' . $booking->id],
                ['label' => 'Sửa nhật ký', 'url' => BASE_URL . 'guide/diary/edit/' . $id, 'active' => true],
            ],
        ]);
    }

    // Xử lý cập nhật nhật ký
    public function nhatKyCapNhat(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy HDV ID từ user hiện tại
        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy dữ liệu từ form
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID nhật ký không hợp lệ';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy nhật ký, booking
        $nhatKy = NhatKyTour::find($id);
        if (!$nhatKy) {
            $_SESSION['error'] = 'Nhật ký không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        $booking = Booking::find($nhatKy->booking_id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy dữ liệu từ form
        $ngay_gio = trim($_POST['ngay_gio'] ?? '');
        if (!empty($ngay_gio)) {
            $ngay_gio = str_replace('T', ' ', $ngay_gio);
        } else {
            $ngay_gio = $nhatKy->ngay_gio;
        }
        $noi_dung = trim($_POST['noi_dung'] ?? '');
        $danh_gia_hdv = trim($_POST['danh_gia_hdv'] ?? '');

        // Kiểm tra dữ liệu
        if (empty($noi_dung)) {
            $_SESSION['error'] = 'Vui lòng nhập nội dung nhật ký';
            header('Location: ' . BASE_URL . 'guide/diary/edit/' . $id);
            exit;
        }

        // Cập nhật nhật ký
        $nhatKy->ngay_gio = $ngay_gio;
        $nhatKy->noi_dung = $noi_dung;
        $nhatKy->danh_gia_hdv = $danh_gia_hdv;

        if (NhatKyTour::update($nhatKy)) {
            $_SESSION['success'] = 'Cập nhật nhật ký thành công';
            header('Location: ' . BASE_URL . 'guide/diary/' . $nhatKy->booking_id);
        } else {
            $_SESSION['error'] = 'Cập nhật nhật ký thất bại';
            header('Location: ' . BASE_URL . 'guide/diary/edit/' . $id);
        }
        exit;
    }

    // Xóa nhật ký
    public function nhatKyXoa(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy HDV ID từ user hiện tại
        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Lấy dữ liệu từ form
        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID nhật ký không hợp lệ';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        // Lấy nhật ký, booking
        $nhatKy = NhatKyTour::find($id);
        if (!$nhatKy) {
            $_SESSION['error'] = 'Nhật ký không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        $booking = Booking::find($nhatKy->booking_id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide/my-bookings');
            exit;
        }

        $bookingId = $nhatKy->booking_id;
        if (NhatKyTour::delete($id)) {
            $_SESSION['success'] = 'Xóa nhật ký thành công';
        } else {
            $_SESSION['error'] = 'Xóa nhật ký thất bại';
        }

        header('Location: ' . BASE_URL . 'guide/diary/' . $bookingId);
        exit;
    }
}
?>

