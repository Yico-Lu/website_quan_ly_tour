<?php
// Chuẩn bị dữ liệu
$user = $user ?? getCurrentUser();

// Bắt đầu capture nội dung
ob_start();
?>

<!--begin::Row-->
<div class="row">
  <div class="col-12">
    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Chào mừng đến với hệ thống quản lý tour</h3>
        <div class="card-tools">
          <button
            type="button"
            class="btn btn-tool"
            data-lte-toggle="card-collapse"
            title="Thu gọn"
          >
            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <?php if (isLoggedIn() && $user): ?>
          <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">
              <i class="bi bi-check-circle-fill me-2"></i>
              Đăng nhập thành công!
            </h4>
            <p class="mb-0">
              Xin chào, <strong><?= htmlspecialchars($user->name) ?></strong>!
              Bạn đã đăng nhập với quyền <strong>Hướng dẫn viên</strong>.
            </p>
          </div>

          <div class="mt-4">
            <h3 class="mb-3">
              <i class="bi bi-info-circle-fill me-2 text-primary"></i>
              Thông tin tài khoản
            </h3>
            <div class="list-group">
              <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">
                    <i class="bi bi-envelope me-2"></i>
                    Email
                  </h5>
                </div>
                <p class="mb-1"><?= htmlspecialchars($user->email) ?></p>
              </div>
              <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">
                    <i class="bi bi-person-badge me-2"></i>
                    Vai trò
                  </h5>
                </div>
                <p class="mb-1">Hướng dẫn viên</p>
              </div>
              <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between align-items-center">
                  <h5 class="mb-1">
                    <i class="bi bi-map me-2"></i>
                    Tour được phân công
                  </h5>
                  <a class="btn btn-primary btn-sm" href="<?= BASE_URL . 'guide/my-bookings' ?>">
                    Xem danh sách
                  </a>
                </div>
                <p class="mb-0 text-muted">Nhấp để xem các tour bạn đang phụ trách.</p>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              Chưa đăng nhập
            </h4>
            <p class="mb-0">
              Vui lòng <a href="<?= BASE_URL ?>?act=login" class="alert-link">đăng nhập</a> để sử dụng đầy đủ chức năng.
            </p>
          </div>
        <?php endif; ?>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>
<!--end::Row-->

<?php
// Lấy nội dung đã capture
$content = ob_get_clean();

// Truyền vào layout
view('layouts.GuideLayout', [
    'title' => $title ?? 'Trang chủ - Hướng dẫn viên',
    'pageTitle' => $pageTitle ?? 'Trang chủ',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>