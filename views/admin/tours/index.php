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
              <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Tên Tour</th>
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
                              <button type="button" class="btn btn-info rounded-xs" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                              </button>
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
                            <td>
                              <h3>Chưa có tour nào trong hệ thống</h3>
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

<!-- Hiển thị thông báo -->
<?php if (isset($_SESSION['success'])): ?>
<script>
    alert('<?= addslashes($_SESSION['success']) ?>');
</script>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<script>
    alert('Lỗi: <?= addslashes($_SESSION['error']) ?>');
</script>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

