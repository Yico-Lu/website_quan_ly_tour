<div id="guideFields" style="display: <?= ($account['phan_quyen'] ?? '') === 'hdv' ? 'block' : 'none' ?>;">
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
               value="<?= $account['ngay_sinh'] ?? '' ?>">
      </div>

      <div class="mb-3">
        <label for="lien_he" class="form-label fw-semibold">
          Liên hệ
        </label>
        <input type="text"
               class="form-control"
               id="lien_he"
               name="lien_he"
               value="<?= htmlspecialchars($account['lien_he'] ?? '') ?>"
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
          <option value="noi_dia" <?= ($account['nhom'] ?? '') === 'noi_dia' ? 'selected' : '' ?>>
            Nội địa
          </option>
          <option value="quoc_te" <?= ($account['nhom'] ?? '') === 'quoc_te' ? 'selected' : '' ?>>
            Quốc tế
          </option>
          <option value="yeu_cau" <?= ($account['nhom'] ?? '') === 'yeu_cau' ? 'selected' : '' ?>>
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
               value="<?= htmlspecialchars($account['chuyen_mon'] ?? '') ?>"
               placeholder="Ví dụ: Hướng dẫn văn hóa, lịch sử...">
      </div>
    </div>
  </div>
</div>
