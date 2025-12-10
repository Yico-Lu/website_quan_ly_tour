<?php
class ReportController
{
    // Dashboard Báo cáo
    public function index(): void
    {
        requireAdmin();

        $stats = $this->getDashboardStats();

        view('admin.reports.index', [
            'title' => 'Dashboard Báo cáo - Quản lý Tour',
            'pageTitle' => 'Dashboard Báo cáo',
            'stats' => $stats,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Báo cáo', 'url' => BASE_URL . 'reports', 'active' => true],
            ],
        ]);
    }

    public function revenueTable(): void
    {
        requireAdmin();

        $year = $_GET['year'] ?? date('Y');

        // Xử lý export
        if (isset($_GET['export']) && $_GET['export'] === 'xlsx') {
            $this->exportRevenueReport($year);
            return;
        }

        // Lấy dữ liệu doanh thu theo tháng trong năm
        $monthlyData = $this->getMonthlyRevenueData($year);

        view('admin.reports.revenue_table', [
            'title' => 'Bảng Doanh thu - Quản lý Tour',
            'pageTitle' => 'Bảng Doanh thu',
            'monthlyData' => $monthlyData,
            'year' => $year,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Báo cáo', 'url' => BASE_URL . 'reports'],
                ['label' => 'Bảng Doanh thu', 'url' => BASE_URL . 'reports/revenue-table', 'active' => true],
            ],
        ]);
    }

    private function getMonthlyRevenueData(string $year): array
    {
        $pdo = getDB();

        // Lấy doanh thu theo tháng
        $sql = "SELECT
                    MONTH(b.ngay_tao) as month,
                    MONTHNAME(b.ngay_tao) as month_name,
                    COUNT(b.id) as total_bookings,
                    SUM(b.so_luong) as total_customers,
                    SUM(CASE WHEN b.trang_thai IN ('da_coc', 'da_thanh_toan') THEN b.so_luong * t.gia ELSE 0 END) as revenue,
                    SUM(CASE WHEN b.trang_thai = 'hoan_thanh' THEN 1 ELSE 0 END) as completed_bookings
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                WHERE YEAR(b.ngay_tao) = ?
                GROUP BY MONTH(b.ngay_tao), MONTHNAME(b.ngay_tao)
                ORDER BY MONTH(b.ngay_tao)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$year]);
        $monthlyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Tạo mảng 12 tháng với dữ liệu mặc định 0
        $fullYearData = [];
        for ($month = 1; $month <= 12; $month++) {
            $fullYearData[$month] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'total_bookings' => 0,
                'total_customers' => 0,
                'revenue' => 0,
                'completed_bookings' => 0
            ];
        }

        // Điền dữ liệu thực tế
foreach ($monthlyData as $data) {
            $monthNum = $data['month'];
            $fullYearData[$monthNum] = [
                'month' => $monthNum,
                'month_name' => $data['month_name'],
                'total_bookings' => $data['total_bookings'] ?? 0,
                'total_customers' => $data['total_customers'] ?? 0,
                'revenue' => $data['revenue'] ?? 0,
                'completed_bookings' => $data['completed_bookings'] ?? 0
            ];
        }

        return array_values($fullYearData);
    }

    private function getDashboardStats(): array
    {
        $pdo = getDB();

        // Thống kê hôm nay
        $today = date('Y-m-d');
        $stmt = $pdo->prepare("
            SELECT
                COUNT(*) as total_bookings_today,
                SUM(CASE WHEN b.trang_thai IN ('da_coc', 'da_thanh_toan') THEN so_luong * t.gia ELSE 0 END) as revenue_today
            FROM booking b
            LEFT JOIN tour t ON b.tour_id = t.id
            WHERE DATE(b.ngay_tao) = ?
        ");
        $stmt->execute([$today]);
        $todayStats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Thống kê tháng này
        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        $stmt = $pdo->prepare("
            SELECT
                COUNT(*) as total_bookings_month,
                SUM(CASE WHEN b.trang_thai IN ('da_coc', 'da_thanh_toan') THEN so_luong * t.gia ELSE 0 END) as revenue_month
            FROM booking b
            LEFT JOIN tour t ON b.tour_id = t.id
            WHERE b.ngay_tao BETWEEN ? AND ?
        ");
        $stmt->execute([$monthStart, $monthEnd]);
        $monthStats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Thống kê năm nay
        $yearStart = date('Y-01-01');
        $yearEnd = date('Y-12-31');
        $stmt = $pdo->prepare("
            SELECT
                COUNT(*) as total_bookings_year,
                SUM(CASE WHEN b.trang_thai IN ('da_coc', 'da_thanh_toan') THEN so_luong * t.gia ELSE 0 END) as revenue_year
            FROM booking b
            LEFT JOIN tour t ON b.tour_id = t.id
            WHERE b.ngay_tao BETWEEN ? AND ?
        ");
        $stmt->execute([$yearStart, $yearEnd]);
        $yearStats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Tour hoạt động và danh mục
        $activeTours = $pdo->query("SELECT COUNT(*) as count FROM tour WHERE trang_thai = 'active'")->fetch(PDO::FETCH_ASSOC);
        $totalCategories = $pdo->query("SELECT COUNT(*) as count FROM danh_muc_tour")->fetch(PDO::FETCH_ASSOC);

        // Hoạt động gần đây (5 booking gần nhất)
        $recentActivities = $pdo->query("
            SELECT
                b.id,
                t.ten_tour,
                b.so_luong,
                b.ngay_tao,
                (CASE WHEN b.trang_thai IN ('da_coc', 'da_thanh_toan') THEN b.so_luong * t.gia ELSE 0 END) as revenue
            FROM booking b
            LEFT JOIN tour t ON b.tour_id = t.id
            ORDER BY b.ngay_tao DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Top tour bán chạy (theo số lượng booking)
        $topTours = $pdo->query("
            SELECT
                t.ten_tour,
                COUNT(b.id) as total_bookings,
                SUM(CASE WHEN b.trang_thai IN ('da_coc', 'da_thanh_toan') THEN b.so_luong * t.gia ELSE 0 END) as total_revenue
            FROM tour t
            LEFT JOIN booking b ON t.id = b.tour_id
            GROUP BY t.id, t.ten_tour
            ORDER BY total_bookings DESC
            LIMIT 5
        ")->fetchAll(PDO::FETCH_ASSOC);

        return [
            'today' => [
                'total_bookings_today' => (int)($todayStats['total_bookings_today'] ?? 0),
                'revenue_today' => (float)($todayStats['revenue_today'] ?? 0)
            ],
            'month' => [
                'total_bookings_month' => (int)($monthStats['total_bookings_month'] ?? 0),
                'revenue_month' => (float)($monthStats['revenue_month'] ?? 0)
            ],
            'year' => [
                'total_bookings_year' => (int)($yearStats['total_bookings_year'] ?? 0),
                'revenue_year' => (float)($yearStats['revenue_year'] ?? 0)
            ],
            'active_tours' => (int)($activeTours['count'] ?? 0),
            'total_categories' => (int)($totalCategories['count'] ?? 0),
            'recent_activities' => $recentActivities,
            'top_tours' => $topTours
        ];
    }

    private function exportRevenueReport(string $year): void
    {
        $monthlyData = $this->getMonthlyRevenueData($year);

        // Tính tổng
        $totalBookings = 0;
        $totalCustomers = 0;
        $totalRevenue = 0;
        $totalCompleted = 0;

        foreach ($monthlyData as $data) {
            $totalBookings += $data['total_bookings'];
            $totalCustomers += $data['total_customers'];
            $totalRevenue += $data['revenue'];
            $totalCompleted += $data['completed_bookings'];
        }

        $filename = "bang-doanh-thu-{$year}";
        $this->exportToExcel($monthlyData, $year, $totalBookings, $totalCustomers, $totalRevenue, $totalCompleted, $filename);
    }

    private function exportToExcel(array $monthlyData, string $year, int $totalBookings, int $totalCustomers, float $totalRevenue, int $totalCompleted, string $filename): void
    {
        // Set headers for Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Content-Transfer-Encoding: binary');

        // Create Excel-compatible CSV content
        $output = fopen('php://output', 'w');

        // BOM for UTF-8 (important for Excel)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Title row
        fputcsv($output, ['BÁO CÁO DOANH THU NĂM ' . $year]);
        fputcsv($output, []); // Empty row

        // Headers
fputcsv($output, ['Tháng', 'Tên tháng', 'Số đơn đặt', 'Số khách hàng', 'Đơn hoàn thành', 'Doanh thu (VND)']);

        // Data rows
        foreach ($monthlyData as $data) {
            fputcsv($output, [
                $data['month'],
                $data['month_name'],
                $data['total_bookings'],
                $data['total_customers'],
                $data['completed_bookings'],
                number_format($data['revenue'], 0, ',', '.') . ' VND'
            ]);
        }

        // Empty row before total
        fputcsv($output, []);

        // Total row
        fputcsv($output, [
            'TỔNG CỘNG',
            '',
            $totalBookings,
            $totalCustomers,
            $totalCompleted,
            number_format($totalRevenue, 0, ',', '.') . ' VND',
            '100.0%'
        ]);

        fclose($output);
        exit;
    }

}
?>