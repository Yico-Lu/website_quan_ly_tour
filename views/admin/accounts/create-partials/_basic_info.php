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
           value="<?= htmlspecialchars($account['ten_dang_nhap'] ?? '') ?>"
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
           value="<?= htmlspecialchars($account['ho_ten'] ?? '') ?>"
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
           value="<?= htmlspecialchars($account['email'] ?? '') ?>"
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
           value="<?= htmlspecialchars($account['sdt'] ?? '') ?>"
           placeholder="Nhập số điện thoại">
  </div>
</div>
