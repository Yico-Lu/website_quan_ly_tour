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

}
?>
