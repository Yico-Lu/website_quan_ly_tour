<?php
ob_start();
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



<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => $title ?? 'Dashboard Báo cáo - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Dashboard Báo cáo',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/auto-hide-alerts.js'],
]);
?>

