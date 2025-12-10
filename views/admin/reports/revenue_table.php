<?php
ob_start();
?>


<?php
$year = $year ?? date('Y');
$totalBookings = 0;
$totalCustomers = 0;
$totalRevenue = 0;
$totalCompleted = 0;

// Tính tổng từ dữ liệu hàng tháng
if (!empty($monthlyData)) {
    foreach ($monthlyData as $data) {
        $totalBookings += $data['total_bookings'] ?? 0;
        $totalCustomers += $data['total_customers'] ?? 0;
        $totalRevenue += $data['revenue'] ?? 0;
        $totalCompleted += $data['completed_bookings'] ?? 0;
    }
}

$avgMonthlyRevenue = count($monthlyData) > 0 ? $totalRevenue / count($monthlyData) : 0;
?>
<?php
ob_start();
?>

<!-- Year Selector -->
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Chọn Năm</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>reports/revenue-table" class="row g-3">
            <div class="col-md-3">
                <label for="year" class="form-label">Năm</label>
                <select class="form-select" id="year" name="year" onchange="this.form.submit()">
                    <?php for ($y = date('Y') + 1; $y >= 2020; $y--): ?>
                        <option value="<?= $y ?>" <?= ($year ?? date('Y')) == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<?php
$year = $year ?? date('Y');
$totalBookings = 0;
$totalCustomers = 0;
$totalRevenue = 0;
$totalCompleted = 0;

// Tính tổng từ dữ liệu hàng tháng
if (!empty($monthlyData)) {
    foreach ($monthlyData as $data) {
        $totalBookings += $data['total_bookings'] ?? 0;
        $totalCustomers += $data['total_customers'] ?? 0;
        $totalRevenue += $data['revenue'] ?? 0;
        $totalCompleted += $data['completed_bookings'] ?? 0;
    }
}

$avgMonthlyRevenue = count($monthlyData) > 0 ? $totalRevenue / count($monthlyData) : 0;
?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check display-4 text-primary opacity-25"></i>
                <h5 class="card-title">Tổng đơn đặt</h5>
                <h2 class="mb-0 text-primary"><?= number_format($totalBookings) ?></h2>
                <small class="text-muted">Năm <?= $year ?></small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-people display-4 text-info opacity-25"></i>
                <h5 class="card-title">Tổng khách hàng</h5>
                <h2 class="mb-0 text-info"><?= number_format($totalCustomers) ?></h2>
                <small class="text-muted">Đã phục vụ</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-cash-coin display-4 text-success opacity-25"></i>
                <h5 class="card-title">Tổng doanh thu</h5>
                <h2 class="mb-0 text-success"><?= number_format($totalRevenue, 0, ',', '.') ?> VND</h2>
                <small class="text-muted">Năm <?= $year ?></small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-trophy display-4 text-warning opacity-25"></i>
                <h5 class="card-title">Trung bình/tháng</h5>
<h2 class="mb-0 text-warning"><?= number_format($avgMonthlyRevenue, 0, ',', '.') ?> VND</h2>
                <small class="text-muted">Doanh thu TB</small>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bảng Doanh thu theo Tháng - Năm <?= $year ?></h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="bi bi-download"></i> Xuất báo cáo
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($monthlyData)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px">Tháng</th>
                            <th>Tháng</th>
                            <th class="text-center">Đơn đặt</th>
                            <th class="text-center">Khách hàng</th>
                            <th class="text-center">Hoàn thành</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($monthlyData as $data): ?>
                            <?php
                            $completionRate = $data['total_bookings'] > 0 ? ($data['completed_bookings'] / $data['total_bookings']) * 100 : 0;
                            ?>
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-primary fs-6"><?= $data['month'] ?></span>
                                </td>
                                <td>
                                    <strong><?= $data['month_name'] ?></strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        <?= number_format($data['total_bookings']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">
                                        <?= number_format($data['total_customers']) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center"><small class="text-muted me-2">
                                            <?= number_format($data['completed_bookings']) ?>/<?= number_format($data['total_bookings']) ?>
                                        </small>
                                        <div class="progress flex-fill" style="height: 8px; width: 60px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: <?= $completionRate ?>%"
                                                 aria-valuenow="<?= $completionRate ?>"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success">
                                        <?= number_format($data['revenue'], 0, ',', '.') ?> VND
                                    </strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th colspan="2" class="text-end"><strong>TỔNG CỘNG</strong></th>
                            <th class="text-center"><strong><?= number_format($totalBookings) ?> đơn</strong></th>
                            <th class="text-center"><strong><?= number_format($totalCustomers) ?> khách</strong></th>
                            <th class="text-center"><strong><?= number_format($totalCompleted) ?> hoàn thành</strong></th>
                            <th class="text-end"><strong class="text-success"><?= number_format($totalRevenue, 0, ',', '.') ?> VND</strong></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?><div class="text-center py-5">
                <i class="bi bi-table text-muted" style="font-size: 3rem;"></i>
                <h4 class="text-muted mt-3">Không có dữ liệu</h4>
                <p class="text-muted">Không tìm thấy dữ liệu báo cáo cho năm <?= $year ?>.</p>
                <p class="text-muted">Có thể chưa có booking nào trong năm này.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Xuất báo cáo doanh thu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có muốn xuất báo cáo doanh thu năm <strong><?= $year ?></strong> ra file Excel không?</p>
                <div class="text-center">
                    <button type="button" class="btn btn-success btn-lg" onclick="exportReport('xlsx')">
                        <i class="bi bi-file-earmark-excel me-2"></i>Xuất Excel (.xlsx)
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>

<script>
function exportReport(format) {
    const url = new URL(window.location);
    url.searchParams.set('export', format);

    // Create a temporary link and click it
    const link = document.createElement('a');
    link.href = url.toString();
    link.download = `bang-doanh-thu-${<?= $year ?>}.${format}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('exportModal'));
    modal.hide();
}
</script>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => $title ?? 'Bảng Doanh thu - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Bảng Doanh thu',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>
