<?php
class ReportController
{
    // Dashboard Báo cáo
    public function index(): void
    {
        requireAdmin();

        view('admin.reports.index', [
            'title' => 'Dashboard Báo cáo - Quản lý Tour',
            'pageTitle' => 'Dashboard Báo cáo',
            'stats' => [], // không lấy dữ liệu
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

}
?>