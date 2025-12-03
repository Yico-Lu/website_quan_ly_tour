
<!--begin::Row-->
<div class="row">
  <div class="col-12">
    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="bi bi-eye me-2"></i>
          Chi tiết tài khoản: <?= htmlspecialchars($account->getDisplayName()) ?>
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

        <div class="row">
          <!-- Thông tin cơ bản -->
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <i class="bi bi-person me-2"></i>
                  Thông tin cơ bản
                </h5>
              </div>
              <div class="card-body">
                <?php renderAccountBasicInfo($account); ?>
              </div>
            </div>
          </div>

          <!-- Avatar và thống kê -->
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <i class="bi bi-image me-2"></i>
                  Avatar
                </h5>
              </div>
              <div class="card-body text-center">
                <?php
                $imagePath = BASE_PATH . '/public/dist/assets/img/' . $account->anh_dai_dien;
                if ($account->anh_dai_dien && file_exists($imagePath)):
                ?>
                  <img src="<?= asset('dist/assets/img/' . $account->anh_dai_dien) ?>"
                       class="rounded-circle mb-3"
                       alt="Avatar"
                       style="width: 120px; height: 120px; object-fit: cover;">
                <?php else: ?>
                  <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                       style="width: 120px; height: 120px;">
                    <i class="bi bi-person text-white" style="font-size: 3rem;"></i>
                  </div>
                <?php endif; ?>
                <p class="text-muted small mb-0">Ảnh đại diện</p>
              </div>
            </div>

            <!-- Thống kê nhanh -->
            <div class="card mt-3">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <i class="bi bi-graph-up me-2"></i>
                  Thống kê
                </h5>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-12">
                    <div class="mb-2">
                      <span class="badge bg-<?= $account->phan_quyen === 'admin' ? 'primary' : 'info' ?> fs-6">
                        <?= $account->getRoleText() ?>
                      </span>
                    </div>
                    <small class="text-muted">Vai trò hiện tại</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Thông tin hướng dẫn viên (nếu có) -->
        <?php if ($account->phan_quyen === 'hdv'): ?>
        <div class="row mt-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <i class="bi bi-person-badge me-2"></i>
                  Thông tin hướng dẫn viên
                </h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label fw-semibold text-muted">Ngày sinh</label>
                      <p class="mb-0">
                        <?php if ($account->ngay_sinh): ?>
                          <?= date('d/m/Y', strtotime($account->ngay_sinh)) ?>
                          <small class="text-muted">(<?= date_diff(date_create($account->ngay_sinh), date_create('today'))->y ?> tuổi)</small>
                        <?php else: ?>
                          <span class="text-muted">Chưa cập nhật</span>
                        <?php endif; ?>
                      </p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label fw-semibold text-muted">Liên hệ</label>
                      <p class="mb-0">
                        <?= $account->lien_he ? htmlspecialchars($account->lien_he) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label fw-semibold text-muted">Nhóm</label>
                      <p class="mb-0">
                        <?php
                        $nhomText = match($account->nhom) {
                          'noi_dia' => 'Nội địa',
                          'quoc_te' => 'Quốc tế',
                          'yeu_cau' => 'Theo yêu cầu',
                          default => 'Chưa phân loại'
                        };
                        ?>
                        <span class="badge bg-secondary"><?= $nhomText ?></span>
                      </p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label fw-semibold text-muted">Chuyên môn</label>
                      <p class="mb-0">
                        <?= $account->chuyen_mon ? htmlspecialchars($account->chuyen_mon) : '<span class="text-muted">Chưa cập nhật</span>' ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="row mt-4">
          <div class="col-12">
            <div class="d-flex justify-content-between">
              <a href="<?= BASE_URL ?>accounts" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Quay lại danh sách
              </a>
              <div>
                <a href="<?= BASE_URL ?>accounts/edit/<?= $account->id ?>" class="btn btn-warning">
                  <i class="bi bi-pencil me-1"></i>
                  Chỉnh sửa
                </a>
                <?php if ($account->id !== getCurrentUser()->id): ?>
                <button type="button" class="btn btn-danger ms-2"
                        onclick="confirmDelete(<?= $account->id ?>, '<?= htmlspecialchars($account->ho_ten) ?>')">
                  <i class="bi bi-trash me-1"></i>
                  Xóa tài khoản
                </button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>
<!--end::Row-->

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-exclamation-triangle text-danger me-2"></i>
          Xác nhận xóa tài khoản
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Bạn có chắc chắn muốn xóa tài khoản <strong id="deleteAccountName"></strong>?</p>
        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle me-2"></i>
          Hành động này không thể hoàn tác!
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <a id="deleteLink" href="#" class="btn btn-danger">
          <i class="bi bi-trash me-1"></i>
          Xóa tài khoản
        </a>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(accountId, accountName) {
  document.getElementById('deleteAccountName').textContent = accountName;
  document.getElementById('deleteLink').href = '<?= BASE_URL ?>accounts/delete/' + accountId;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
