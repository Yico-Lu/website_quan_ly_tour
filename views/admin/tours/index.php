<?php
    ob_start();
?>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Danh sách Tour</h3>
            <div class="card-tools">
                <a href="<?= BASE_URL ?>tours/create" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Thêm Tour mới
                </a>
            </div>
        </div>
                  <!-- /.card-header -->
            <div class="card-body">
              <?php displayFlashMessages(); ?>

              <!-- Form tìm kiếm và lọc -->
              <form method="GET" action="<?= BASE_URL ?>tours" class="mb-3">
                  <div class="row g-2">
                      <div class="col-md-4">
                          <label for="keyword" class="form-label">Tìm kiếm theo tên tour</label>
                          <input 
                              type="text" 
                              class="form-control" 
                              id="keyword" 
                              name="keyword" 
                              value="<?= htmlspecialchars($keyword ?? '') ?>"
                              placeholder="Nhập tên tour cần tìm..."
                          >
                      </div>
                      <div class="col-md-3">
                          <label for="danh_muc_id" class="form-label">Lọc theo danh mục</label>
                          <select class="form-select" id="danh_muc_id" name="danh_muc_id">
                              <option value="">Tất cả danh mục</option>
                              <?php foreach($danhMucList as $danhMuc): ?>
                                  <option 
                                      value="<?= $danhMuc['id'] ?>"
                                      <?= ($danh_muc_id ?? '') == $danhMuc['id'] ? 'selected' : '' ?>
                                  >
                                      <?= htmlspecialchars($danhMuc['ten_danh_muc']) ?>
                                  </option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="trang_thai" class="form-label">Lọc theo trạng thái</label>
                          <select class="form-select" id="trang_thai" name="trang_thai">
                              <option value="">Tất cả trạng thái</option>
                              <option value="1" <?= ($trang_thai ?? '') == '1' ? 'selected' : '' ?>>Hoạt động</option>
                              <option value="0" <?= ($trang_thai ?? '') == '0' ? 'selected' : '' ?>>Ngưng hoạt động</option>
                          </select>
                      </div>
                      <div class="col-md-2 d-flex align-items-end">
                          <button type="submit" class="btn btn-primary w-100">
                              <i class="bi bi-search"></i> Tìm kiếm
                          </button>
                      </div>
                  </div>
                  <?php if (!empty($keyword) || !empty($danh_muc_id) || !empty($trang_thai)): ?>
                  <div class="mt-2">
                      <a href="<?= BASE_URL ?>tours" class="btn btn-sm btn-secondary">
                          <i class="bi bi-x-circle"></i> Xóa bộ lọc
                      </a>
                  </div>
                  <?php endif; ?>
              </form>

              <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Tên Tour</th>
                          <th style="width: 150px">Ảnh</th>
                          <th>Danh mục</th>
                          <th>Giá</th>
                          <th>Trạng thái</th>
                          <th>Ngày tạo</th>
                          <th style="width: 150px">Thao tác </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($tours)): ?>
                            <?php foreach($tours as $index => $tour): ?>
                        <tr class="align-middle">
                          <td><?= $index + 1 ?>.</td>
                          <td><?= htmlspecialchars($tour->ten_tour) ?></td>
                          <td class="text-center">
                            <?php if(!empty($tour->anh_tour)): ?>
                              <img src="<?= asset($tour->anh_tour) ?>"
                                   alt="Ảnh tour"
                                   class="img-thumbnail"
                                   style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                              <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                   style="width: 100px; ">
                                <i class="bi bi-image text-muted"></i>
                              </div>
                            <?php endif; ?>
                          </td>
                          <td><?= htmlspecialchars($tour->ten_danh_muc ?? 'Chưa phân loại') ?></td>
                          <td><?= $tour->formatGia() ?></td>
                          <td>
                            <span class="badge <?= $tour->getTrangThaiBadgeClass() ?>">
                              <?= $tour->getTrangThai() ?>
                            </span>
                          </td>
                          <td><?= date('d/m/Y', strtotime($tour->ngay_tao)) ?></td>
                          <td>
                            <div class="btn-group btn-group-sm gap-1">
                              <a href="<?= BASE_URL ?>tours/show/<?= $tour->id ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                              </a>
                              <a href="<?= BASE_URL ?>tours/edit/<?= $tour->id ?>" class="btn btn-warning btn-sm" title="Sửa">
                                <i class="bi bi-pencil"></i>
                              </a>
                              <!-- <button type="button" class="btn btn-danger" title="Xóa"> -->
                              <form method="POST" action="<?= BASE_URL ?>tours/delete"  
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa tour này không')">
                                <input type="hidden" name="id" value="<?= $tour->id ?>">
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                  <i class="bi bi-trash"></i>
                                </button>
                              </form>
                            </div>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="8" class="text-center py-4">
                              <?php if (!empty($keyword) || !empty($danh_muc_id) || !empty($trang_thai)): ?>
                                <h3 class="text-muted">Không tìm thấy tour nào</h3>
                                <p class="text-muted">Thử tìm kiếm với từ khóa khác hoặc xóa bộ lọc</p>
                                <a href="<?= BASE_URL ?>tours" class="btn btn-secondary mt-2">
                                  <i class="bi bi-arrow-left"></i> Xem tất cả tour
                                </a>
                              <?php else: ?>
                                <h3 class="text-muted">Chưa có tour nào trong hệ thống</h3>
                                <a href="<?= BASE_URL ?>tours/create" class="btn btn-success mt-3">
                                  <i class="bi bi-plus-circle"></i> Thêm Tour đầu tiên
                                </a>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endif; ?>
                    </tbody>
              </table>
                </div>
                  <!-- /.card-body -->
                  <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-end">
                      <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                    </ul>
                  </div>
                </div>

<?php
  $content = ob_get_clean();
  view('layouts.AdminLayout', [
    'title' => $title ?? 'Danh sách Tour - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Danh sách Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
  ]);
?>