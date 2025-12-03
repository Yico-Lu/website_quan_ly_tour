<div class="col-md-6">
  <h5 class="mb-3">
    <i class="bi bi-shield me-2"></i>
    Phân quyền và bảo mật
  </h5>

  <div class="mb-3">
    <label for="phan_quyen" class="form-label fw-semibold">
      Vai trò <span class="text-danger">*</span>
    </label>
    <select class="form-select" id="phan_quyen" name="phan_quyen" required onchange="toggleGuideFields()">
      <option value="">Chọn vai trò</option>
      <option value="admin" <?= ($account['phan_quyen'] ?? '') === 'admin' ? 'selected' : '' ?>>
        Quản trị viên
      </option>
      <option value="hdv" <?= ($account['phan_quyen'] ?? '') === 'hdv' ? 'selected' : '' ?>>
        Hướng dẫn viên
      </option>
    </select>
    <div class="form-text">
      <i class="bi bi-info-circle me-1"></i>
      Quản trị viên có toàn quyền, hướng dẫn viên chỉ quản lý tour
    </div>
  </div>

  <div class="mb-3">
    <label for="mat_khau" class="form-label fw-semibold">
      Mật khẩu <span class="text-danger">*</span>
    </label>
    <input type="password" class="form-control" id="mat_khau" name="mat_khau"
           placeholder="Nhập mật khẩu" required>
    <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
  </div>

  <div class="mb-3">
    <label for="mat_khau_confirm" class="form-label fw-semibold">
      Xác nhận mật khẩu <span class="text-danger">*</span>
    </label>
    <input type="password" class="form-control" id="mat_khau_confirm" name="mat_khau_confirm"
           placeholder="Nhập lại mật khẩu" required>
  </div>

  <div class="mb-3">
    <label for="trang_thai" class="form-label fw-semibold">
      Trạng thái <span class="text-danger">*</span>
    </label>
    <select class="form-select" id="trang_thai" name="trang_thai" required>
      <option value="hoat_dong" <?= ($account['trang_thai'] ?? 'hoat_dong') === 'hoat_dong' ? 'selected' : '' ?>>
        Hoạt động
      </option>
      <option value="ngung" <?= ($account['trang_thai'] ?? '') === 'ngung' ? 'selected' : '' ?>>
        Ngưng hoạt động
      </option>
    </select>
  </div>
</div>
