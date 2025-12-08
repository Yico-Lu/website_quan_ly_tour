<?php
class GuideTourController
{
    // Danh sách booking của HDV hiện tại
    public function myBookings(): void
    {
        requireGuideOrAdmin();

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $pdo = getDB();
        // Lấy danh sách booking được gán cho HDV này
        // Bao gồm các trạng thái: cho_xac_nhan, da_coc, da_thanh_toan, da_huy, da_hoan_thanh
        $sql = "SELECT b.*,
                       t.ten_tour,
                       t.gia AS gia_tour,
                       (SELECT l1.id FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS lich_khoi_hanh_id,
                       (SELECT l1.ngay_gio_xuat_phat FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS ngay_gio_xuat_phat,
                       (SELECT l1.diem_tap_trung FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS diem_tap_trung
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                WHERE b.assigned_hdv_id = ?
                AND b.trang_thai IN ('cho_xac_nhan', 'da_coc', 'da_thanh_toan', 'da_huy', 'da_hoan_thanh')
                ORDER BY b.ngay_tao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hdvId]);
        $bookings = [];
        foreach($stmt->fetchAll() as $row){
            $bookings[] = new Booking($row);
        }

        view('guide.my-bookings', [
            'title' => 'Danh sách Tour của tôi',
            'pageTitle' => 'Danh sách Tour của tôi',
            'bookings' => $bookings,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide-tours/my-bookings', 'active' => true],
            ],
        ]);
    }

    // Chi tiết booking và danh sách khách
    public function viewBooking($id): void
    {
        requireGuideOrAdmin();

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $booking = Booking::find($id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Kiểm tra quyền: chỉ HDV được gán mới xem được
        if ($booking->assigned_hdv_id != $hdvId) {
            $_SESSION['error'] = 'Bạn không có quyền xem booking này';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Lấy danh sách khách
        $khachs = $booking->getKhachs();
        
        // Lấy lịch khởi hành
        $lichKhoiHanhId = $booking->lich_khoi_hanh_id ?: Booking::getLichKhoiHanhIdByBookingId($id);
        
        // Lấy điểm danh
        $diemDanh = Booking::getDiemDanhByBooking($id, $lichKhoiHanhId);

        view('guide.booking-detail', [
            'title' => 'Chi tiết Tour: ' . $booking->ten_tour,
            'pageTitle' => 'Chi tiết Tour',
            'booking' => $booking,
            'khachs' => $khachs,
            'diemDanh' => $diemDanh,
            'lichKhoiHanhId' => $lichKhoiHanhId,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide-tours/my-bookings'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide-tours/booking/' . $id, 'active' => true],
            ],
        ]);
    }

    // Xử lý check-in khách
    public function checkIn(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $bookingId = $_POST['booking_id'] ?? null;
        $lichKhoiHanhId = $_POST['lich_khoi_hanh_id'] ?? null;
        $bookingKhachId = $_POST['booking_khach_id'] ?? null;

        if (!$bookingId || !$lichKhoiHanhId || !$bookingKhachId) {
            $_SESSION['error'] = 'Thông tin không hợp lệ';
            header('Location: ' . BASE_URL . 'guide-tours/booking/' . $bookingId);
            exit;
        }

        // Kiểm tra quyền
        $booking = Booking::find($bookingId);
        if (!$booking || $booking->assigned_hdv_id != $hdvId) {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Lưu điểm danh (check-in)
        $trangThai = 'co_mat'; // Có mặt
        $ghiChu = trim($_POST['ghi_chu'] ?? '');
        
        if (Booking::upsertDiemDanh($lichKhoiHanhId, $bookingKhachId, $trangThai, $ghiChu)) {
            $_SESSION['success'] = 'Check-in thành công';
        } else {
            $_SESSION['error'] = 'Check-in thất bại';
        }

        header('Location: ' . BASE_URL . 'guide-tours/booking/' . $bookingId);
        exit;
    }

    // Danh sách nhật ký tour
    public function diaryList($bookingId): void
    {
        requireGuideOrAdmin();

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Kiểm tra quyền
        if ($booking->assigned_hdv_id != $hdvId) {
            $_SESSION['error'] = 'Bạn không có quyền xem booking này';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $nhatKys = NhatKyTour::getByBookingId($bookingId);

        view('guide.diary-list', [
            'title' => 'Nhật ký Tour: ' . $booking->ten_tour,
            'pageTitle' => 'Nhật ký Tour',
            'booking' => $booking,
            'nhatKys' => $nhatKys,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide-tours/my-bookings'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide-tours/booking/' . $bookingId],
                ['label' => 'Nhật ký tour', 'url' => BASE_URL . 'guide-tours/diary/' . $bookingId, 'active' => true],
            ],
        ]);
    }

    // Form tạo nhật ký mới
    public function diaryCreate($bookingId): void
    {
        requireGuideOrAdmin();

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Kiểm tra quyền
        if ($booking->assigned_hdv_id != $hdvId) {
            $_SESSION['error'] = 'Bạn không có quyền thêm nhật ký cho booking này';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        view('guide.diary-form', [
            'title' => 'Thêm nhật ký tour',
            'pageTitle' => 'Thêm nhật ký tour',
            'booking' => $booking,
            'nhatKy' => null,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide-tours/my-bookings'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide-tours/booking/' . $bookingId],
                ['label' => 'Nhật ký tour', 'url' => BASE_URL . 'guide-tours/diary/' . $bookingId],
                ['label' => 'Thêm nhật ký', 'url' => BASE_URL . 'guide-tours/diary/create/' . $bookingId, 'active' => true],
            ],
        ]);
    }

    // Xử lý lưu nhật ký mới
    public function diaryStore(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $bookingId = $_POST['booking_id'] ?? null;
        if (!$bookingId) {
            $_SESSION['error'] = 'Booking ID không hợp lệ';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Kiểm tra quyền
        $booking = Booking::find($bookingId);
        if (!$booking || $booking->assigned_hdv_id != $hdvId) {
            $_SESSION['error'] = 'Bạn không có quyền thêm nhật ký cho booking này';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
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

        // Validate
        $errors = [];
        if (empty($noi_dung)) {
            $errors[] = 'Vui lòng nhập nội dung nhật ký';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            header('Location: ' . BASE_URL . 'guide-tours/diary/create/' . $bookingId);
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
            header('Location: ' . BASE_URL . 'guide-tours/diary/' . $bookingId);
        } else {
            $_SESSION['error'] = 'Thêm nhật ký thất bại';
            header('Location: ' . BASE_URL . 'guide-tours/diary/create/' . $bookingId);
        }
        exit;
    }

    // Form sửa nhật ký
    public function diaryEdit($id): void
    {
        requireGuideOrAdmin();

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $nhatKy = NhatKyTour::find($id);
        if (!$nhatKy) {
            $_SESSION['error'] = 'Nhật ký không tồn tại';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $booking = Booking::find($nhatKy->booking_id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Kiểm tra quyền
        if ($booking->assigned_hdv_id != $hdvId) {
            $_SESSION['error'] = 'Bạn không có quyền sửa nhật ký này';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        view('guide.diary-form', [
            'title' => 'Sửa nhật ký tour',
            'pageTitle' => 'Sửa nhật ký tour',
            'booking' => $booking,
            'nhatKy' => $nhatKy,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'guide-tours/my-bookings'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide-tours/booking/' . $booking->id],
                ['label' => 'Nhật ký tour', 'url' => BASE_URL . 'guide-tours/diary/' . $booking->id],
                ['label' => 'Sửa nhật ký', 'url' => BASE_URL . 'guide-tours/diary/edit/' . $id, 'active' => true],
            ],
        ]);
    }

    // Xử lý cập nhật nhật ký
    public function diaryUpdate(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID nhật ký không hợp lệ';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $nhatKy = NhatKyTour::find($id);
        if (!$nhatKy) {
            $_SESSION['error'] = 'Nhật ký không tồn tại';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Kiểm tra quyền
        $booking = Booking::find($nhatKy->booking_id);
        if (!$booking || $booking->assigned_hdv_id != $hdvId) {
            $_SESSION['error'] = 'Bạn không có quyền sửa nhật ký này';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
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

        // Validate
        $errors = [];
        if (empty($noi_dung)) {
            $errors[] = 'Vui lòng nhập nội dung nhật ký';
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            header('Location: ' . BASE_URL . 'guide-tours/diary/edit/' . $id);
            exit;
        }

        // Cập nhật nhật ký
        $nhatKy->ngay_gio = $ngay_gio;
        $nhatKy->noi_dung = $noi_dung;
        $nhatKy->danh_gia_hdv = $danh_gia_hdv;

        if (NhatKyTour::update($nhatKy)) {
            $_SESSION['success'] = 'Cập nhật nhật ký thành công';
            header('Location: ' . BASE_URL . 'guide-tours/diary/' . $nhatKy->booking_id);
        } else {
            $_SESSION['error'] = 'Cập nhật nhật ký thất bại';
            header('Location: ' . BASE_URL . 'guide-tours/diary/edit/' . $id);
        }
        exit;
    }

    // Xóa nhật ký
    public function diaryDelete(): void
    {
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $hdvId = getCurrentHDVId();
        if (!$hdvId) {
            $_SESSION['error'] = 'Không tìm thấy thông tin hướng dẫn viên';
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID nhật ký không hợp lệ';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $nhatKy = NhatKyTour::find($id);
        if (!$nhatKy) {
            $_SESSION['error'] = 'Nhật ký không tồn tại';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        // Kiểm tra quyền
        $booking = Booking::find($nhatKy->booking_id);
        if (!$booking || $booking->assigned_hdv_id != $hdvId) {
            $_SESSION['error'] = 'Bạn không có quyền xóa nhật ký này';
            header('Location: ' . BASE_URL . 'guide-tours/my-bookings');
            exit;
        }

        $bookingId = $nhatKy->booking_id;
        if (NhatKyTour::delete($id)) {
            $_SESSION['success'] = 'Xóa nhật ký thành công';
        } else {
            $_SESSION['error'] = 'Xóa nhật ký thất bại';
        }

        header('Location: ' . BASE_URL . 'guide-tours/diary/' . $bookingId);
        exit;
    }
}
?>

