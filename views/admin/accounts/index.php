<?php
// Đảm bảo biến $newAccount luôn tồn tại và là array
if (!isset($newAccount) || !is_array($newAccount)) {
    $newAccount = [];
}

// Đảm bảo biến $errors luôn tồn tại và là array
if (!isset($errors) || !is_array($errors)) {
    $errors = [];
}
?>

<!--begin::Row-->
<div class="row">
  <div class="col-12">
    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="bi bi-people me-2"></i>
          <?= $title ?>
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">
            <i class="bi bi-plus-circle me-1"></i>
            Thêm tài khoản
          </button>
          <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse" title="Collapse">
            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <?php showAlert(); ?>

        <!-- Page Header -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <?php if ($filter === 'admin'): ?>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-shield-check text-primary me-2 fs-4"></i>
                    <div>
                      <h5 class="mb-0">Quản lý Tài khoản Quản trị viên</h5>
                      <small class="text-muted">Các tài khoản có quyền quản trị hệ thống</small>
                    </div>
                  </div>
                <?php elseif ($filter === 'hdv'): ?>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-person-badge text-info me-2 fs-4"></i>
                    <div>
                      <h5 class="mb-0">Quản lý Tài khoản Hướng dẫn viên</h5>
                      <small class="text-muted">Các tài khoản hướng dẫn viên du lịch</small>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-people text-secondary me-2 fs-4"></i>
                    <div>
                      <h5 class="mb-0">Quản lý Tất cả Tài khoản</h5>
                      <small class="text-muted">Quản trị viên và hướng dẫn viên</small>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>


        <!-- Filter và Search -->
        <div class="row mb-3">
          <div class="col-md-8">
            <div class="d-flex gap-2 flex-wrap">
              <div class="btn-group" role="group">
                <a href="<?= BASE_URL ?>accounts" class="btn btn-outline-primary <?= $filter === 'all' ? 'active' : '' ?>">
                  <i class="bi bi-people me-1"></i>
                  Tất cả tài khoản
                </a>
                <a href="<?= BASE_URL ?>accounts/admins" class="btn btn-outline-primary <?= $filter === 'admin' ? 'active' : '' ?>">
                  <i class="bi bi-shield-check me-1"></i>
                  Quản trị viên
                </a>
                <a href="<?= BASE_URL ?>accounts?filter=hdv" class="btn btn-outline-info <?= $filter === 'hdv' ? 'active' : '' ?>">
                  <i class="bi bi-person-badge me-1"></i>
                  Hướng dẫn viên
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <form method="GET" class="d-flex">
              <input type="hidden" name="filter" value="<?= $filter ?>">
              <input type="text" name="search" class="form-control me-2"
                     placeholder="Tìm theo tên, email, tên đăng nhập..."
                     value="<?= htmlspecialchars($search) ?>">
              <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-search"></i>
              </button>
            </form>
          </div>
        </div>

        <!-- Bảng danh sách tài khoản -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th width="5%">#</th>
                <th width="15%">Tên đăng nhập</th>
                <th width="20%">Họ tên</th>
                <th width="20%">Email</th>
                <th width="10%">Vai trò</th>
                <th width="10%">Trạng thái</th>
                <th width="10%">Ngày tạo</th>
                <th width="10%">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($accounts)): ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  <i class="bi bi-info-circle me-2"></i>
                  Không có tài khoản nào
                </td>
              </tr>
              <?php else: ?>
                <?php foreach ($accounts as $index => $account): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-2">
                        <?php
                        $imagePath = BASE_PATH . '/public/dist/assets/img/' . $account->anh_dai_dien;
                        if ($account->anh_dai_dien && file_exists($imagePath)):
                        ?>
                          <img src="<?= asset('dist/assets/img/' . $account->anh_dai_dien) ?>"
                               class="rounded-circle" alt="Avatar" style="width: 32px; height: 32px;">
                        <?php else: ?>
                          <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                               style="width: 32px; height: 32px;">
                            <i class="bi bi-person text-white"></i>
                          </div>
                        <?php endif; ?>
                      </div>
                      <span><?= htmlspecialchars($account->ten_dang_nhap) ?></span>
                    </div>
                  </td>
                  <td><?= htmlspecialchars($account->ho_ten) ?></td>
                  <td>
                    <a href="mailto:<?= htmlspecialchars($account->email) ?>" class="text-decoration-none">
                      <?= htmlspecialchars($account->email) ?>
                    </a>
                  </td>
                  <td>
                    <?php if ($account->phan_quyen === 'admin'): ?>
                      <span class="badge bg-primary">
                        <i class="bi bi-shield-check me-1"></i>
                        Quản trị viên
                      </span>
                    <?php else: ?>
                      <span class="badge bg-info">
                        <i class="bi bi-person-badge me-1"></i>
                        Hướng dẫn viên
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="badge bg-<?= $account->getStatusColor() ?>">
                      <?= $account->getStatusText() ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($account->ngay_tao): ?>
                      <small class="text-muted">
                        <?= date('d/m/Y', strtotime($account->ngay_tao)) ?>
                      </small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <a href="<?= BASE_URL ?>accounts/show/<?= $account->id ?>"
                         class="btn btn-outline-info btn-sm" title="Xem chi tiết">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="<?= BASE_URL ?>accounts/edit/<?= $account->id ?>"
                         class="btn btn-outline-warning btn-sm" title="Chỉnh sửa">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <?php if ($account->canDelete()): ?>
                      <a href="javascript:void(0)"
                         onclick="deleteAccount(<?= $account->id ?>, '<?= htmlspecialchars($account->ho_ten) ?>')"
                         class="btn btn-outline-danger btn-sm"
                         title="Xóa">
                        <i class="bi bi-trash"></i>
                      </a>
                      <?php else: ?>
                      <button type="button" class="btn btn-outline-secondary btn-sm"
                              disabled
                              title="<?= htmlspecialchars($account->getDeleteReason()) ?>">
                        <i class="bi bi-trash"></i>
                      </button>
                      <?php endif; ?>
                    </div>
                    <?php if ($account->phan_quyen === 'hdv' && $account->nhom): ?>
                      <div class="mt-1">
                        <small class="text-muted">
                          <i class="bi bi-tag me-1"></i>
                          <?= match($account->nhom) {
                            'noi_dia' => 'Nội địa',
                            'quoc_te' => 'Quốc tế',
                            'yeu_cau' => 'Theo yêu cầu',
                            default => 'Chưa phân loại'
                          } ?>
                        </small>
                      </div>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- Thống kê -->
        <?php if (!empty($accounts)): ?>
        <div class="row mt-4">
          <div class="col-12">
            <h5 class="mb-3">
              <i class="bi bi-graph-up me-2"></i>
              Thống kê <?= $filter === 'all' ? 'tổng quan' : ($filter === 'admin' ? 'quản trị viên' : 'hướng dẫn viên') ?>
            </h5>
          </div>
        </div>

        <div class="row">
          <?php if ($filter === 'all'): ?>
            <!-- Thống kê tổng quan -->
            <div class="col-md-4">
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3><?= count(array_filter($accounts, fn($a) => $a->phan_quyen === 'admin')) ?></h3>
                  <p>Quản trị viên</p>
                </div>
                <div class="icon">
                  <i class="bi bi-shield-check"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= count(array_filter($accounts, fn($a) => $a->phan_quyen === 'hdv')) ?></h3>
                  <p>Hướng dẫn viên</p>
                </div>
                <div class="icon">
                  <i class="bi bi-person-badge"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= count(array_filter($accounts, fn($a) => $a->trang_thai === 'hoat_dong')) ?></h3>
                  <p>Tài khoản hoạt động</p>
                </div>
                <div class="icon">
                  <i class="bi bi-check-circle"></i>
                </div>
              </div>
            </div>
          <?php elseif ($filter === 'admin'): ?>
            <!-- Thống kê admin -->
            <div class="col-md-6">
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3><?= count(array_filter($accounts, fn($a) => $a->trang_thai === 'hoat_dong')) ?></h3>
                  <p>Admin hoạt động</p>
                </div>
                <div class="icon">
                  <i class="bi bi-check-circle"></i>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?= count(array_filter($accounts, fn($a) => $a->trang_thai === 'ngung')) ?></h3>
                  <p>Admin ngừng hoạt động</p>
                </div>
                <div class="icon">
                  <i class="bi bi-x-circle"></i>
                </div>
              </div>
            </div>
          <?php elseif ($filter === 'hdv'): ?>
            <!-- Thống kê HDV -->
            <div class="col-md-4">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= count(array_filter($accounts, fn($a) => $a->trang_thai === 'hoat_dong')) ?></h3>
                  <p>HDV hoạt động</p>
                </div>
                <div class="icon">
                  <i class="bi bi-check-circle"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="small-box bg-secondary">
                <div class="inner">
                  <h3><?= count(array_filter($accounts, fn($a) => $a->nhom === 'noi_dia')) ?></h3>
                  <p>HDV nội địa</p>
                </div>
                <div class="icon">
                  <i class="bi bi-house"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= count(array_filter($accounts, fn($a) => $a->nhom === 'quoc_te')) ?></h3>
                  <p>HDV quốc tế</p>
                </div>
                <div class="icon">
                  <i class="bi bi-airplane"></i>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>
<!--end::Row-->


<script>

function toggleGuideFieldsModal() {
  const roleSelect = document.getElementById('modal_phan_quyen');
  const guideFields = document.getElementById('guideFieldsModal');

  if (roleSelect.value === 'hdv') {
    guideFields.style.display = 'block';
  } else {
    guideFields.style.display = 'none';
  }
}

// Kiểm tra xác nhận mật khẩu trong modal
document.addEventListener('DOMContentLoaded', function() {
  const confirmPasswordInput = document.getElementById('modal_mat_khau_confirm');
  const passwordInput = document.getElementById('modal_mat_khau');

  if (confirmPasswordInput && passwordInput) {
    confirmPasswordInput.addEventListener('input', function() {
      const password = passwordInput.value;
      const confirmPassword = this.value;

      if (password !== confirmPassword) {
        this.setCustomValidity('Mật khẩu xác nhận không khớp');
      } else {
        this.setCustomValidity('');
      }
    });
  }

  // Nếu có lỗi, tự động mở modal (chỉ một lần)
  <?php if (!empty($errors ?? [])): ?>
    if (typeof window.addModalShown === 'undefined') {
      window.addModalShown = true;
      const modalElement = document.getElementById('addAccountModal');
      if (modalElement && !modalElement.modalInstance) {
        modalElement.modalInstance = new bootstrap.Modal(modalElement);
      }
      if (modalElement.modalInstance) {
        modalElement.modalInstance.show();
      }
    }
  <?php endif; ?>

  // Truyền dữ liệu cho modal
  window.newAccountData = <?= json_encode($newAccount ?? []) ?>;
  window.formErrors = <?= json_encode($errors ?? []) ?>;
});
</script>

<!-- Modal thêm tài khoản -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addAccountModalLabel">
          <i class="bi bi-plus-circle me-2"></i>
          Thêm tài khoản mới
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addAccountForm" action="<?= BASE_URL ?>accounts/store" method="post" novalidate>
        <div class="modal-body">
          <?php if (!empty($errors ?? [])): ?>
          <div class="alert alert-danger fade show" role="alert">
            <div class="d-flex align-items-center mb-2">
              <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
              <strong>Có lỗi xảy ra</strong>
            </div>
            <ul class="mb-3 ps-3">
              <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <div class="row">
            <div class="col-md-6">
              <h6 class="mb-3 text-primary">
                <i class="bi bi-person me-2"></i>
                Thông tin cơ bản
              </h6>

              <div class="mb-3">
                <label for="modal_ten_dang_nhap" class="form-label fw-semibold">
                  Tên đăng nhập <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control"
                       id="modal_ten_dang_nhap"
                       name="ten_dang_nhap"
                       value="<?= htmlspecialchars($newAccount['ten_dang_nhap'] ?? '') ?>"
                       placeholder="Nhập tên đăng nhập"
                       required>
                <div class="form-text">Tên đăng nhập phải có ít nhất 3 ký tự</div>
              </div>

              <div class="mb-3">
                <label for="modal_ho_ten" class="form-label fw-semibold">
                  Họ tên <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control"
                       id="modal_ho_ten"
                       name="ho_ten"
                       value="<?= htmlspecialchars($newAccount['ho_ten'] ?? '') ?>"
                       placeholder="Nhập họ tên đầy đủ"
                       required>
              </div>

              <div class="mb-3">
                <label for="modal_email" class="form-label fw-semibold">
                  Email <span class="text-danger">*</span>
                </label>
                <input type="email"
                       class="form-control"
                       id="modal_email"
                       name="email"
                       value="<?= htmlspecialchars($newAccount['email'] ?? '') ?>"
                       placeholder="Nhập địa chỉ email"
                       required>
                <div class="form-text">Email sẽ được sử dụng để đăng nhập</div>
              </div>

              <div class="mb-3">
                <label for="modal_sdt" class="form-label fw-semibold">
                  Số điện thoại
                </label>
                <input type="tel"
                       class="form-control"
                       id="modal_sdt"
                       name="sdt"
                       value="<?= htmlspecialchars($newAccount['sdt'] ?? '') ?>"
                       placeholder="Nhập số điện thoại">
              </div>
            </div>

            <div class="col-md-6">
              <h6 class="mb-3 text-primary">
                <i class="bi bi-shield me-2"></i>
                Phân quyền và bảo mật
              </h6>

              <div class="mb-3">
                <label for="modal_phan_quyen" class="form-label fw-semibold">
                  Vai trò <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="modal_phan_quyen" name="phan_quyen" required onchange="toggleGuideFieldsModal()">
                  <option value="">Chọn vai trò</option>
                  <option value="admin" <?= ($newAccount['phan_quyen'] ?? '') === 'admin' ? 'selected' : '' ?>>
                    Quản trị viên
                  </option>
                  <option value="hdv" <?= ($newAccount['phan_quyen'] ?? '') === 'hdv' ? 'selected' : '' ?>>
                    Hướng dẫn viên
                  </option>
                </select>
                <div class="form-text">
                  <i class="bi bi-info-circle me-1"></i>
                  Quản trị viên có toàn quyền, hướng dẫn viên chỉ quản lý tour
                </div>
              </div>

              <div class="mb-3">
                <label for="modal_mat_khau" class="form-label fw-semibold">
                  Mật khẩu <span class="text-danger">*</span>
                </label>
                <input type="password"
                       class="form-control"
                       id="modal_mat_khau"
                       name="mat_khau"
                       placeholder="Nhập mật khẩu"
                       required>
                <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
              </div>

              <div class="mb-3">
                <label for="modal_mat_khau_confirm" class="form-label fw-semibold">
                  Xác nhận mật khẩu <span class="text-danger">*</span>
                </label>
                <input type="password"
                       class="form-control"
                       id="modal_mat_khau_confirm"
                       name="mat_khau_confirm"
                       placeholder="Nhập lại mật khẩu"
                       required>
              </div>

              <div class="mb-3">
                <label for="modal_trang_thai" class="form-label fw-semibold">
                  Trạng thái <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="modal_trang_thai" name="trang_thai" required>
                  <option value="hoat_dong" <?= ($newAccount['trang_thai'] ?? 'hoat_dong') === 'hoat_dong' ? 'selected' : '' ?>>
                    Hoạt động
                  </option>
                  <option value="ngung" <?= ($newAccount['trang_thai'] ?? '') === 'ngung' ? 'selected' : '' ?>>
                    Ngưng hoạt động
                  </option>
                </select>
              </div>
            </div>
          </div>

          <!-- Thông tin hướng dẫn viên -->
          <div id="guideFieldsModal" style="display: none;">
            <hr>
            <h6 class="mb-3 text-info">
              <i class="bi bi-person-badge me-2"></i>
              Thông tin hướng dẫn viên
            </h6>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="modal_ngay_sinh" class="form-label fw-semibold">
                    Ngày sinh
                  </label>
                  <input type="date"
                         class="form-control"
                         id="modal_ngay_sinh"
                         name="ngay_sinh"
                         value="<?= $newAccount['ngay_sinh'] ?? '' ?>">
                </div>

                <div class="mb-3">
                  <label for="modal_lien_he" class="form-label fw-semibold">
                    Liên hệ
                  </label>
                  <input type="text"
                         class="form-control"
                        id="modal_lien_he"
                         name="lien_he"
                         value="<?= htmlspecialchars($newAccount['lien_he'] ?? '') ?>"
                         placeholder="Thông tin liên hệ bổ sung">
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="modal_nhom" class="form-label fw-semibold">
                    Nhóm
                  </label>
                  <select class="form-select" id="modal_nhom" name="nhom">
                    <option value="">Chọn nhóm</option>
                    <option value="noi_dia" <?= ($newAccount['nhom'] ?? '') === 'noi_dia' ? 'selected' : '' ?>>
                      Nội địa
                    </option>
                    <option value="quoc_te" <?= ($newAccount['nhom'] ?? '') === 'quoc_te' ? 'selected' : '' ?>>
                      Quốc tế
                    </option>
                    <option value="yeu_cau" <?= ($newAccount['yeu_cau'] ?? '') === 'yeu_cau' ? 'selected' : '' ?>>
                      Theo yêu cầu
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="modal_chuyen_mon" class="form-label fw-semibold">
                    Chuyên môn
                  </label>
                  <input type="text"
                         class="form-control"
                         id="modal_chuyen_mon"
                         name="chuyen_mon"
                         value="<?= htmlspecialchars($newAccount['chuyen_mon'] ?? '') ?>"
                         placeholder="Ví dụ: Hướng dẫn văn hóa, lịch sử...">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-arrow-left me-1"></i>
            Hủy
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i>
            Tạo tài khoản
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openAddModal() {
  console.log('Opening add modal...');
  const modalElement = document.getElementById('addAccountModal');
  if (modalElement && !modalElement.modalInstance) {
    modalElement.modalInstance = new bootstrap.Modal(modalElement);
  }
  if (modalElement.modalInstance) {
    modalElement.modalInstance.show();
  }
  console.log('Modal shown');
}

function deleteAccount(accountId, accountName) {
  console.log('Deleting account:', accountId, accountName);

  const confirmed = confirm('Bạn có chắc chắn muốn xóa tài khoản "' + accountName + '"?\n\nHành động này không thể hoàn tác!');

  if (confirmed) {
    console.log('User confirmed, redirecting to delete URL');
    window.location.href = '<?= BASE_URL ?>accounts/delete/' + accountId;
  } else {
    console.log('User cancelled delete');
  }
}


function toggleGuideFieldsModal() {
  const roleSelect = document.getElementById('modal_phan_quyen');
  const guideFields = document.getElementById('guideFieldsModal');

  if (roleSelect.value === 'hdv') {
    guideFields.style.display = 'block';
  } else {
    guideFields.style.display = 'none';
  }
}

// Kiểm tra xác nhận mật khẩu trong modal
document.addEventListener('DOMContentLoaded', function() {
  const confirmPasswordInput = document.getElementById('modal_mat_khau_confirm');
  const passwordInput = document.getElementById('modal_mat_khau');

  if (confirmPasswordInput && passwordInput) {
    confirmPasswordInput.addEventListener('input', function() {
      const password = passwordInput.value;
      const confirmPassword = this.value;

      if (password !== confirmPassword) {
        this.setCustomValidity('Mật khẩu xác nhận không khớp');
      } else {
        this.setCustomValidity('');
      }
    });
  }

  console.log('JavaScript loaded successfully');
});
</script>
