<?php
// Bắt đầu capture nội dung
ob_start();
?>

<div class="container-fluid">
    <!-- Thông tin tour -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        Danh sách khách hàng - <?= htmlspecialchars($tour->ten_tour) ?>
                    </h3>
                    <div class="card-tools">
                        <a href="<?= BASE_URL ?>guide/show/<?= $tour->id ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <a href="<?= BASE_URL ?>guide/import/<?= $tour->id ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-upload"></i> Import
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thống kê -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $stats['total_bookings'] ?></h3>
                                    <p>Tổng booking</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $stats['checked_in'] ?></h3>
                                    <p>Đã check-in</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $stats['pending_checkin'] ?></h3>
                                    <p>Chưa check-in</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3><?= $stats['total_people'] ?></h3>
                                    <p>Tổng khách</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách khách hàng -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i>
                        Chi tiết danh sách khách hàng
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <?php if (empty($customers)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có khách hàng nào</h5>
                            <p class="text-muted">Sử dụng chức năng Import để thêm danh sách khách hàng</p>
                            <a href="<?= BASE_URL ?>guide/import/<?= $tour->id ?>" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import
                            </a>
                        </div>
                    <?php else: ?>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Liên hệ</th>
                                    <th>Số người</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $index => $customer): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($customer->ho_ten) ?></strong>
                                        <?php if (!empty($customer->ghi_chu)): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($customer->ghi_chu) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($customer->email)): ?>
                                            <i class="fas fa-envelope text-muted"></i> <?= htmlspecialchars($customer->email) ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($customer->so_dien_thoai)): ?>
                                            <i class="fas fa-phone text-muted"></i> <?= htmlspecialchars($customer->so_dien_thoai) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <?= $customer->getTongSoNguoi() ?> người
                                        </span>
                                        <?php if ($customer->so_luong_tre_em > 0): ?>
                                            <br><small class="text-muted">
                                                (<?= $customer->so_luong_nguoi_lon ?> người lớn, <?= $customer->so_luong_tre_em ?> trẻ em)
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            <?= $customer->formatTongTien() ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <?php if (method_exists($customer, 'getCheckinBadge')): ?>
                                            <?= $customer->getCheckinBadge() ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y H:i', strtotime($customer->ngay_dat)) ?>
                                    </td>
                                    <td>
                                        <?php if (property_exists($customer, 'da_checkin')): ?>
                                            <form method="POST" action="<?= BASE_URL ?>guide/checkin" class="d-inline">
                                                <input type="hidden" name="booking_id" value="<?= $customer->id ?>">
                                                <input type="hidden" name="tour_id" value="<?= $tour->id ?>">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                           class="form-check-input"
                                                           id="checkin_<?= $customer->id ?>"
                                                           name="da_checkin"
                                                           value="1"
                                                           <?= $customer->da_checkin ? 'checked' : '' ?>
                                                           onchange="this.form.submit()">
                                                    <label class="form-check-label" for="checkin_<?= $customer->id ?>">
                                                        Check-in
                                                    </label>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Lấy nội dung đã capture
$content = ob_get_clean();

// Truyền vào layout
view('layouts.GuideLayout', [
    'title' => $title ?? 'Danh sách khách hàng',
    'pageTitle' => $pageTitle ?? 'Danh sách khách hàng',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'currentUser' => $currentUser ?? null,
]);
?>

