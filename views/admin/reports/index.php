<?php
ob_start();
$pdo = getDB();
?>

<!-- Info boxes -->
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="bi bi-calendar-day"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Hôm nay</span>
                <span class="info-box-number">
                    <?= number_format($stats['today']['total_bookings_today'] ?? 0) ?>
                    <small>đơn đặt</small>
                </span>
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    Doanh thu: <?= number_format($stats['today']['revenue_today'] ?? 0, 0, ',', '.') ?> VND
                </span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="bi bi-calendar-month"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tháng này</span>
                <span class="info-box-number">
                    <?= number_format($stats['month']['total_bookings_month'] ?? 0) ?>
                    <small>đơn đặt</small>
                </span>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    Doanh thu: <?= number_format($stats['month']['revenue_month'] ?? 0, 0, ',', '.') ?> VND
                </span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="bi bi-calendar-year"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Năm nay</span>
                <span class="info-box-number">
                    <?= number_format($stats['year']['total_bookings_year'] ?? 0) ?>
                    <small>đơn đặt</small>
                </span>
                <div class="progress">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    Doanh thu: <?= number_format($stats['year']['revenue_year'] ?? 0, 0, ',', '.') ?> VND
                </span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="bi bi-trophy"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tour hoạt động</span>
                <span class="info-box-number">
<?= number_format($stats['active_tours'] ?? 0) ?>
                    <small>tour</small>
                </span>
                <div class="progress">
                    <div class="progress-bar bg-danger" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <?= number_format($stats['total_categories'] ?? 0) ?> danh mục
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Truy cập nhanh</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?= BASE_URL ?>reports/revenue-table" class="btn btn-primary btn-block">
                            <i class="bi bi-download"></i>
                            <br>Xuất Báo cáo Excel
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= BASE_URL ?>bookings" class="btn btn-success btn-block">
                            <i class="bi bi-journal-text"></i>
                            <br>Quản lý Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Hoạt động gần đây</h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($stats['recent_activities'])): ?>
                        <?php foreach ($stats['recent_activities'] as $activity): ?>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?= htmlspecialchars($activity['ten_tour']) ?></h6>
                                    <small><?php
                                        $date = new DateTime($activity['ngay_tao']);
                                        $now = new DateTime();
                                        $diff = $now->diff($date);

                                        if ($diff->days == 0) {
                                            echo 'Hôm nay';
                                        } elseif ($diff->days == 1) {
                                            echo 'Hôm qua';
                                        } else {
                                            echo $diff->days . ' ngày trước';
                                        }
                                    ?></small>
                                </div>
                                <p class="mb-1">Có <?= $activity['so_luong'] ?> khách đặt</p>
                                <small class="text-muted">Doanh thu: <?= number_format($activity['revenue'], 0, ',', '.') ?> VND</small>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item">
                            <div class="text-center py-3">
                                <small class="text-muted">Chưa có hoạt động nào</small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= BASE_URL ?>bookings" class="btn btn-sm btn-outline-primary">
                    Xem tất cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top Tour bán chạy</h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($stats['top_tours'])): ?>
                        <?php foreach ($stats['top_tours'] as $tour): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($tour['ten_tour']) ?></h6>
                                        <small class="text-muted"><?= number_format($tour['total_bookings']) ?> đơn đặt</small>
                                    </div>
                                    <span class="badge bg-success"><?= number_format($tour['total_revenue'], 0, ',', '.') ?> VND</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item">
                            <div class="text-center py-3">
                                <small class="text-muted">Chưa có tour nào được đặt</small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= BASE_URL ?>tours" class="btn btn-sm btn-outline-primary">
                    Xem tất cả tour <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">So sánh Doanh thu Theo Năm</h3>
            </div>
            <div class="card-body">
                <?php
                $currentYear = date('Y');
                $comparisonYears = [$currentYear - 1, $currentYear];
                $yearlyStats = [];

                foreach ($comparisonYears as $year) {
                    $stmt = $pdo->prepare("
                        SELECT
                            COUNT(*) as total_bookings,
                            SUM(CASE WHEN b.trang_thai IN ('da_coc', 'da_thanh_toan') THEN so_luong * t.gia ELSE 0 END) as revenue
                        FROM booking b
                        LEFT JOIN tour t ON b.tour_id = t.id
                        WHERE YEAR(b.ngay_tao) = ?
                    ");
                    $stmt->execute([$year]);
                    $yearStats = $stmt->fetch(PDO::FETCH_ASSOC);

                    $yearlyStats[$year] = [
                        'bookings' => (int)($yearStats['total_bookings'] ?? 0),
                        'revenue' => (float)($yearStats['revenue'] ?? 0)
                    ];
                }
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Năm</th>
                                <th class="text-center">Số đơn đặt</th>
                                <th class="text-end">Doanh thu</th>
                                <th class="text-center">Tăng trưởng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comparisonYears as $index => $year): ?>
                                <?php
                                $currentStats = $yearlyStats[$year];
                                $prevStats = $index > 0 ? $yearlyStats[$comparisonYears[$index - 1]] : null;
                                $bookingGrowth = $prevStats ? (($currentStats['bookings'] - $prevStats['bookings']) / max($prevStats['bookings'], 1)) * 100 : 0;
                                $revenueGrowth = $prevStats ? (($currentStats['revenue'] - $prevStats['revenue']) / max($prevStats['revenue'], 1)) * 100 : 0;
                                ?>
                                <tr>
                                    <td><strong><?= $year ?></strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?= number_format($currentStats['bookings']) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success"><?= number_format($currentStats['revenue'], 0, ',', '.') ?> VND</strong>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($prevStats): ?>
                                            <small class="text-<?= $revenueGrowth >= 0 ? 'success' : 'danger' ?>">
                                                <i class="bi bi-arrow-<?= $revenueGrowth >= 0 ? 'up' : 'down' ?>"></i>
                                                <?= number_format(abs($revenueGrowth), 1) ?>%
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thống kê Doanh thu Theo Tháng (Năm <?= date('Y') ?>)</h3>
            </div>
            <div class="card-body">
                <?php
                $currentYear = date('Y');
                $monthlyStats = [];

                // Lấy thống kê theo tháng cho năm hiện tại
                for ($month = 1; $month <= 12; $month++) {
                    $monthStart = sprintf('%04d-%02d-01', $currentYear, $month);
                    $monthEnd = sprintf('%04d-%02d-%02d', $currentYear, $month, date('t', strtotime($monthStart)));

                    $stmt = $pdo->prepare("
                        SELECT
                            COUNT(*) as total_bookings,
                            SUM(CASE WHEN b.trang_thai IN ('da_coc', 'da_thanh_toan') THEN so_luong * t.gia ELSE 0 END) as revenue
                        FROM booking b
                        LEFT JOIN tour t ON b.tour_id = t.id
                        WHERE b.ngay_tao BETWEEN ? AND ?
                    ");
                    $stmt->execute([$monthStart, $monthEnd]);
                    $monthStats = $stmt->fetch(PDO::FETCH_ASSOC);

                    $monthlyStats[$month] = [
                        'bookings' => (int)($monthStats['total_bookings'] ?? 0),
                        'revenue' => (float)($monthStats['revenue'] ?? 0)
                    ];
                }

                // Tính quý
                $quarters = [
                    'Q1' => array_sum(array_column(array_slice($monthlyStats, 0, 3, true), 'revenue')),
                    'Q2' => array_sum(array_column(array_slice($monthlyStats, 3, 3, true), 'revenue')),
                    'Q3' => array_sum(array_column(array_slice($monthlyStats, 6, 3, true), 'revenue')),
                    'Q4' => array_sum(array_column(array_slice($monthlyStats, 9, 3, true), 'revenue'))
                ];
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th class="text-center">Tháng</th>
                                        <th class="text-center">Đơn đặt</th>
                                        <th class="text-end">Doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalYearRevenue = array_sum(array_column($monthlyStats, 'revenue'));
                                    foreach ($monthlyStats as $month => $stats):
                                    ?>
                                        <tr>
                                            <td class="text-center">
                                                <strong><?= $month ?>/<?= $currentYear ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info"><?= number_format($stats['bookings']) ?></span>
                                            </td>
                                            <td class="text-end">
                                                <strong class="text-success"><?= number_format($stats['revenue'], 0, ',', '.') ?> VND</strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th class="text-center"><strong>TỔNG</strong></th>
                                        <th class="text-center"><strong><?= number_format(array_sum(array_column($monthlyStats, 'bookings'))) ?> đơn</strong></th>
                                        <th class="text-end"><strong class="text-success"><?= number_format(array_sum(array_column($monthlyStats, 'revenue')), 0, ',', '.') ?> VND</strong></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-center mb-3">Doanh thu theo Quý</h5>
                        <div class="row">
                            <?php foreach ($quarters as $quarter => $revenue): ?>
                                <div class="col-6 mb-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center p-2">
                                            <h6 class="card-title mb-1"><?= $quarter ?></h6>
                                            <h5 class="text-primary mb-0">
                                                <?= number_format($revenue / 1000000, 1) ?>M
                                            </h5>
                                            <small class="text-muted">
                                                <?= number_format(($revenue / $totalYearRevenue) * 100, 1) ?>%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => $title ?? 'Dashboard Báo cáo - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Dashboard Báo cáo',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>