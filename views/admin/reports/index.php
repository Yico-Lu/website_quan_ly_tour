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
                            <i class="bi bi-table"></i>
                            <br>Bảng Doanh thu
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
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Tour Phú Quốc 3 ngày 2 đêm</h6>
                            <small>Hôm nay</small>
                        </div>
                        <p class="mb-1">Có 5 đơn đặt mới</p>
                        <small class="text-muted">Doanh thu: 75.000.000 VND</small>
                    </a>
<a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Tour Sapa mùa hoa đào</h6>
                            <small>Hôm qua</small>
                        </div>
                        <p class="mb-1">Có 3 đơn đặt mới</p>
                        <small class="text-muted">Doanh thu: 45.000.000 VND</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Tour Đà Lạt city tour</h6>
                            <small>3 ngày trước</small>
                        </div>
                        <p class="mb-1">Có 2 đơn đặt mới</p>
                        <small class="text-muted">Doanh thu: 20.000.000 VND</small>
                    </a>
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
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Tour Phú Quốc 3 ngày 2 đêm</h6>
                                <small class="text-muted">45 đơn đặt</small>
                            </div>
                            <span class="badge bg-success">150.000.000 VND</span>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Tour Sapa mùa hoa đào</h6>
                                <small class="text-muted">32 đơn đặt</small>
                            </div>
                            <span class="badge bg-success">96.000.000 VND</span>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Tour Đà Lạt city tour</h6>
                                <small class="text-muted">28 đơn đặt</small>
                            </div>
<span class="badge bg-success">70.000.000 VND</span>
                        </div>
                    </div>
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

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => $title ?? 'Dashboard Báo cáo - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Dashboard Báo cáo',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>