<?php
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
}
?>