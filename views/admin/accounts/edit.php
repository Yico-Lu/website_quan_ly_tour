
<!--begin::Row-->
<div class="row">
  <div class="col-12">
    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="bi bi-pencil me-2"></i>
          Chỉnh sửa tài khoản: <?= htmlspecialchars($account->getDisplayName()) ?>
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse" title="Collapse">
            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <?php showAlert(); ?>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger fade show" role="alert">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <strong>Có lỗi xảy ra</strong>
          </div>
          <ul class="mb-0 ps-3">
            <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>accounts/update/<?= $account->id ?>" method="post" novalidate>
          <div class="row">
            <div class="col-md-6">
              <h5 class="mb-3">
                <i class="bi bi-person me-2"></i>
                Thông tin cơ bản
              </h5>

              <div class="mb-3">
                <label for="ten_dang_nhap" class="form-label fw-semibold">
                  Tên đăng nhập <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control"
                       id="ten_dang_nhap"
                       name="ten_dang_nhap"
                       value="<?= htmlspecialchars($account->ten_dang_nhap) ?>"
                       placeholder="Nhập tên đăng nhập"
                       required>
                <div class="form-text">Tên đăng nhập phải có ít nhất 3 ký tự</div>
              </div>

              <div class="mb-3">
                <label for="ho_ten" class="form-label fw-semibold">
                  Họ tên <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control"
                       id="ho_ten"
                       name="ho_ten"
                       value="<?= htmlspecialchars($account->ho_ten) ?>"
                       placeholder="Nhập họ tên đầy đủ"
                       required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                  Email <span class="text-danger">*</span>
                </label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       value="<?= htmlspecialchars($account->email) ?>"
                       placeholder="Nhập địa chỉ email"
                       required>
                <div class="form-text">Email sẽ được sử dụng để đăng nhập</div>
              </div>

              <div class="mb-3">
                <label for="sdt" class="form-label fw-semibold">
                  Số điện thoại
                </label>
                <input type="tel"
                       class="form-control"
                       id="sdt"
                       name="sdt"
                       value="<?= htmlspecialchars($account->sdt ?? '') ?>"
                       placeholder="Nhập số điện thoại">
              </div>
            </div>

            <div class="col-md-6">
              <h5 class="mb-3">
                <i class="bi bi-shield me-2"></i>
                Phân quyền và trạng thái
              </h5>

              <div class="mb-3">
                <label for="phan_quyen" class="form-label fw-semibold">
                  Vai trò <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="phan_quyen" name="phan_quyen" required onchange="toggleGuideFields()">
                  <option value="">Chọn vai trò</option>
                  <option value="admin" <?= $account->phan_quyen === 'admin' ? 'selected' : '' ?>>
                    Quản trị viên
                  </option>
                  <option value="hdv" <?= $account->phan_quyen === 'hdv' ? 'selected' : '' ?>>
                    Hướng dẫn viên
                  </option>
                </select>
                <div class="form-text">
                  <i class="bi bi-info-circle me-1"></i>
                  Quản trị viên có toàn quyền, hướng dẫn viên chỉ quản lý tour
                </div>
              </div>

              <div class="mb-3">
                <label for="trang_thai" class="form-label fw-semibold">
                  Trạng thái <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="trang_thai" name="trang_thai" required>
                  <option value="hoat_dong" <?= $account->trang_thai === 'hoat_dong' ? 'selected' : '' ?>>
                    Hoạt động
                  </option>
                  <option value="ngung" <?= $account->trang_thai === 'ngung' ? 'selected' : '' ?>>
                    Ngưng hoạt động
                  </option>
                </select>
              </div>

              <!-- Đổi mật khẩu -->
              <div class="mb-3">
                <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#passwordSection">
                  <i class="bi bi-key me-1"></i>
                  Đổi mật khẩu
                </button>
              </div>

              <div class="collapse" id="passwordSection">
                <div class="card card-body bg-light">
                  <h6 class="mb-3">
                    <i class="bi bi-key me-1"></i>
                    Đổi mật khẩu
                  </h6>

                  <?php if (getCurrentUser()->id === $account->id): ?>
                  <div class="mb-3">
                    <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                  </div>
                  <?php endif; ?>

                  <div class="mb-3">
                    <label for="new_password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="new_password" name="new_password"
                           placeholder="Ít nhất 6 ký tự" required>
                  </div>

                  <div class="mb-3">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                  </div>

                  <button type="submit" formaction="<?= BASE_URL ?>accounts/change-password/<?= $account->id ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-check-circle me-1"></i>
                    Cập nhật mật khẩu
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div id="guideFields" style="display: <?= $account->phan_quyen === 'hdv' ? 'block' : 'none' ?>;">
            <hr>
            <h5 class="mb-3">
              <i class="bi bi-person-badge me-2"></i>
              Thông tin hướng dẫn viên
            </h5>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="ngay_sinh" class="form-label fw-semibold">
                    Ngày sinh
                  </label>
                  <input type="date"
                         class="form-control"
                         id="ngay_sinh"
                         name="ngay_sinh"
                         value="<?= $account->ngay_sinh ?? '' ?>">
                </div>

                <div class="mb-3">
                  <label for="lien_he" class="form-label fw-semibold">
                    Liên hệ
                  </label>
                  <input type="text"
                         class="form-control"
                         id="lien_he"
                         name="lien_he"
                         value="<?= htmlspecialchars($account->lien_he ?? '') ?>"
                         placeholder="Thông tin liên hệ bổ sung">
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="nhom" class="form-label fw-semibold">
                    Nhóm
                  </label>
                  <select class="form-select" id="nhom" name="nhom">
                    <option value="">Chọn nhóm</option>
                    <option value="noi_dia" <?= ($account->nhom ?? '') === 'noi_dia' ? 'selected' : '' ?>>
                      Nội địa
                    </option>
                    <option value="quoc_te" <?= ($account->nhom ?? '') === 'quoc_te' ? 'selected' : '' ?>>
                      Quốc tế
                    </option>
                    <option value="yeu_cau" <?= ($account->nhom ?? '') === 'yeu_cau' ? 'selected' : '' ?>>
                      Theo yêu cầu
                    </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="chuyen_mon" class="form-label fw-semibold">
                    Chuyên môn
                  </label>
                  <input type="text"
                         class="form-control"
                         id="chuyen_mon"
                         name="chuyen_mon"
                         value="<?= htmlspecialchars($account->chuyen_mon ?? '') ?>"
                         placeholder="Ví dụ: Hướng dẫn văn hóa, lịch sử...">
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-12">
              <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary">
                  <i class="bi bi-arrow-left me-1"></i>
                  Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-circle me-1"></i>
                  Cập nhật tài khoản
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>
<!--end::Row-->

<script>
function toggleGuideFields() {
  const roleSelect = document.getElementById('phan_quyen');
  const guideFields = document.getElementById('guideFields');

  if (roleSelect.value === 'hdv') {
    guideFields.style.display = 'block';
  } else {
    guideFields.style.display = 'none';
  }
}

// Kiểm tra xác nhận mật khẩu
document.getElementById('confirm_password').addEventListener('input', function() {
  const password = document.getElementById('new_password').value;
  const confirmPassword = this.value;

  if (password !== confirmPassword) {
    this.setCustomValidity('Mật khẩu xác nhận không khớp');
  } else {
    this.setCustomValidity('');
  }
});

// Khởi tạo
document.addEventListener('DOMContentLoaded', function() {
  toggleGuideFields();
});
</script>

