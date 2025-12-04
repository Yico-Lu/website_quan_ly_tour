<?php
    ob_start();
?>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Danh sách Danh mục Tour</h3>
            <div class="card-tools">
                <a href="<?= BASE_URL ?>categories/create" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Thêm Danh mục mới
                </a>
            </div>
        </div>
                  <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Tên Danh mục</th>
                          <th>Mô tả</th>
                          <th>Trạng thái</th>
                          <th>Ngày tạo</th>
                          <th style="width: 150px">Thao tác </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($categories)): ?>
                            <?php foreach($categories as $index => $category): ?>
                        <tr class="align-middle">
                          <td><?= $index + 1 ?>.</td>
                          <td><?= htmlspecialchars($category->ten_danh_muc) ?></td>
                          <td><?= htmlspecialchars($category->mo_ta) ?></td>
                          <td>
                            <span class="badge <?= $category->getTrangThaiBadgeClass() ?>">
                              <?= $category->getTrangThai() ?>
                            </span>
                          </td>
                          <td><?= date('d/m/Y', strtotime($category->ngay_tao)) ?></td>
                          <td>
                            <div class="btn-group btn-group-sm gap-1">
                              <a href="<?= BASE_URL ?>categories/edit/<?= $category->id ?>" class="btn btn-warning btn-sm" title="Sửa">
                                <i class="bi bi-pencil"></i>
                              </a>
                              <form method="POST" action="<?= BASE_URL ?>categories/delete"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này không')">
                                <input type="hidden" name="id" value="<?= $category->id ?>">
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
                            <td colspan="6">
                              <h3>Chưa có danh mục nào trong hệ thống</h3>
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
    'title' => $title ?? 'Danh sách Danh mục Tour - Quản lý Tour',
    'pageTitle' => $pageTitle ?? 'Danh sách Danh mục Tour',
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


