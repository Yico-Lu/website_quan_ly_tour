<?php
require_once __DIR__ . '/../models/Booking.php';

class GuideController
{
    // Trang chủ cho hướng dẫn viên - hiển thị danh sách tour được phân công
    public function index(): void
    {
        // Kiểm tra quyền hướng dẫn viên
        requireGuideOrAdmin();

        // Lấy user hiện tại
        $currentUser = getCurrentUser();

        $assignedTours = Tour::getAll(true); // Lấy tất cả tour với thông tin liên quan

        // Hiển thị view
        view('guide.index', [
            'title' => 'Tour được phân công - Hướng dẫn viên',
            'pageTitle' => 'Tour được phân công',
            'tours' => $assignedTours,
            'currentUser' => $currentUser,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'guide', 'active' => true],
            ],
        ]);
    }

    // Xem chi tiết tour được phân công
    public function show($tourId): void
    {
        // Kiểm tra quyền hướng dẫn viên
        requireGuideOrAdmin();

        // Lấy thông tin tour
        $tour = Tour::find($tourId, true); // Load tất cả thông tin liên quan
        if (!$tour) {
            setFlashMessage('error', 'Tour không tồn tại');
            header('Location: ' . BASE_URL . 'guide');
            exit;
        }

        // Lấy user hiện tại
        $currentUser = getCurrentUser();

        // Hiển thị view chi tiết tour
        view('guide.show', [
            'title' => 'Chi tiết Tour: ' . $tour->ten_tour,
            'pageTitle' => 'Chi tiết Tour',
            'tour' => $tour,
            'currentUser' => $currentUser,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'guide'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide/show/' . $tourId, 'active' => true],
            ],
        ]);
    }

    // Trang dashboard/tổng quan cho hướng dẫn viên
    public function dashboard(): void
    {
        // Kiểm tra quyền hướng dẫn viên
        requireGuideOrAdmin();

        $currentUser = getCurrentUser();

        $stats = [
            'total_tours' => 0, // Tổng số tour được phân công
            'active_tours' => 0, // Tour đang hoạt động
            'upcoming_tours' => 0, // Tour sắp tới
            'completed_tours' => 0, // Tour đã hoàn thành
        ];

        view('guide.dashboard', [
            'title' => 'Dashboard Hướng dẫn viên',
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'currentUser' => $currentUser,
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => BASE_URL . 'guide/dashboard', 'active' => true],
            ],
        ]);
    }

    // Xem danh sách khách hàng trong tour
    public function customers($tourId): void
    {
        // Kiểm tra quyền hướng dẫn viên
        requireGuideOrAdmin();

        // Lấy thông tin tour
        $tour = Tour::find($tourId, true);
        if (!$tour) {
            setFlashMessage('error', 'Tour không tồn tại');
            header('Location: ' . BASE_URL . 'guide');
            exit;
        }

        // Lấy danh sách khách hàng đã đặt tour
        $customers = Booking::getByTourId($tourId);

        // Lấy thống kê
        $stats = Booking::getTourStats($tourId);

        // Lấy user hiện tại
        $currentUser = getCurrentUser();

        view('guide.customers', [
            'title' => 'Danh sách khách hàng - ' . $tour->ten_tour,
            'pageTitle' => 'Danh sách khách hàng',
            'tour' => $tour,
            'customers' => $customers,
            'stats' => $stats,
            'currentUser' => $currentUser,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'guide'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide/show/' . $tourId],
                ['label' => 'Danh sách khách', 'url' => BASE_URL . 'guide/customers/' . $tourId, 'active' => true],
            ],
        ]);
    }

    // Check-in khách hàng
    public function checkin(): void
    {
        // Kiểm tra quyền hướng dẫn viên
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide');
            exit;
        }

        $bookingId = $_POST['booking_id'] ?? null;
        $tourId = $_POST['tour_id'] ?? null;
        $checkinStatus = isset($_POST['da_checkin']) ? 1 : 0;

        if (!$bookingId || !$tourId) {
            setFlashMessage('error', 'Thông tin không hợp lệ');
            header('Location: ' . BASE_URL . 'guide/customers/' . $tourId);
            exit;
        }

        if (Booking::updateCheckinStatus($bookingId, $checkinStatus)) {
            $statusText = $checkinStatus ? 'đã check-in' : 'chưa check-in';
            setFlashMessage('success', 'Cập nhật trạng thái check-in thành công: ' . $statusText);
        } else {
            setFlashMessage('error', 'Cập nhật trạng thái check-in thất bại');
        }

        header('Location: ' . BASE_URL . 'guide/customers/' . $tourId);
        exit;
    }

    // Import danh sách khách hàng từ file CSV
    public function importCustomers($tourId): void
    {
        // Kiểm tra quyền hướng dẫn viên
        requireGuideOrAdmin();

        // Lấy thông tin tour
        $tour = Tour::find($tourId);
        if (!$tour) {
            setFlashMessage('error', 'Tour không tồn tại');
            header('Location: ' . BASE_URL . 'guide');
            exit;
        }

        // Lấy user hiện tại
        $currentUser = getCurrentUser();

        view('guide.import', [
            'title' => 'Import khách hàng - ' . $tour->ten_tour,
            'pageTitle' => 'Import danh sách khách hàng',
            'tour' => $tour,
            'currentUser' => $currentUser,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'guide'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'guide/show/' . $tourId],
                ['label' => 'Import khách hàng', 'url' => BASE_URL . 'guide/import/' . $tourId, 'active' => true],
            ],
        ]);
    }

    // Xử lý import file CSV
    public function processImport(): void
    {
        // Kiểm tra quyền hướng dẫn viên
        requireGuideOrAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'guide');
            exit;
        }

        $tourId = $_POST['tour_id'] ?? null;

        if (!$tourId) {
            setFlashMessage('error', 'Tour ID không hợp lệ');
            header('Location: ' . BASE_URL . 'guide');
            exit;
        }

        // Kiểm tra file upload
        if (!isset($_FILES['customer_file']) || $_FILES['customer_file']['error'] !== UPLOAD_ERR_OK) {
            setFlashMessage('error', 'Vui lòng chọn file CSV để import');
            header('Location: ' . BASE_URL . 'guide/import/' . $tourId);
            exit;
        }

        // Validate file type
        $fileType = strtolower(pathinfo($_FILES['customer_file']['name'], PATHINFO_EXTENSION));
        if ($fileType !== 'csv') {
            setFlashMessage('error', 'Chỉ chấp nhận file CSV');
            header('Location: ' . BASE_URL . 'guide/import/' . $tourId);
            exit;
        }

        // Upload file tạm thời
        $tempPath = $_FILES['customer_file']['tmp_name'];

        // Import dữ liệu từ CSV
        $result = Booking::importFromCSV($tourId, $tempPath);

        if ($result['imported'] > 0) {
            setFlashMessage('success', 'Import thành công ' . $result['imported'] . ' khách hàng');
        }

        if (!empty($result['errors'])) {
            $errorMessage = 'Có ' . count($result['errors']) . ' lỗi trong quá trình import:<br>';
            $errorMessage .= implode('<br>', array_slice($result['errors'], 0, 5)); // Hiển thị 5 lỗi đầu
            if (count($result['errors']) > 5) {
                $errorMessage .= '<br>... và ' . (count($result['errors']) - 5) . ' lỗi khác';
            }
            setFlashMessage('warning', $errorMessage);
        }

        header('Location: ' . BASE_URL . 'guide/customers/' . $tourId);
        exit;
    }
}
?>