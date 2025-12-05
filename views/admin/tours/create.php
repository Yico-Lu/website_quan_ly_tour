<?php
    ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Thêm Tour Mới</h3>
    </div>

    <form action="<?= BASE_URL ?>tours/store" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            <!-- Hiển thị lỗi -->
            <?php if(isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>Có lỗi:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Tên tour -->
                <div class="col-md-8 mb-3">
                    <label for="ten_tour" class="form-label">Tên Tour <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control"
                        id="ten_tour"
                        name="ten_tour"
                        value="<?= htmlspecialchars($old['ten_tour'] ?? '') ?>"
                        placeholder="Nhập tên tour"
                        required
                    />
                </div>

                <!-- Danh mục -->
                <div class="col-md-4 mb-3">
                    <label for="danh_muc_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select class="form-select" id="danh_muc_id" name="danh_muc_id" required>
                        <option value="">Chọn danh mục</option>
                        <?php foreach($danhMucList as $danhMuc): ?>
                            <option
                                value="<?= $danhMuc['id'] ?>"
                                <?= ($old['danh_muc_id'] ?? '') == $danhMuc['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($danhMuc['ten_danh_muc']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Giá tour -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gia" class="form-label">Giá Tour (VNĐ) <span class="text-danger">*</span></label>
                    <input
                        type="number"
                        class="form-control"
                        id="gia"
                        name="gia"
                        value="<?= htmlspecialchars($old['gia'] ?? '') ?>"
                        placeholder="0"
                        min="1"
                        required
                    />
                </div>

                <!-- Trạng thái -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Trạng thái</label>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="trang_thai"
                            name="trang_thai"
                            value="1"
                            <?= ($old['trang_thai'] ?? 1) ? 'checked' : '' ?>
                        />
                        <label class="form-check-label" for="trang_thai">
                            Hoạt động
                        </label>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="mb-3">
                <label for="mo_ta" class="form-label">Mô tả Tour <span class="text-danger">*</span></label>
                <textarea
                    class="form-control"
                    id="mo_ta"
                    name="mo_ta"
                    rows="4"
                    placeholder="Nhập mô tả chi tiết về tour..."
                    required
                ><?= htmlspecialchars($old['mo_ta'] ?? '') ?></textarea>
            </div>

            <!-- Ảnh tour -->
            <div class="mb-3">
                <label for="anh_tour" class="form-label">Ảnh Tour</label>
                <input
                    type="file"
                    class="form-control"
                    id="anh_tour"
                    name="anh_tour"
                    accept="image/*"
                />
                <div class="form-text">
                    Chọn ảnh cho tour .Nếu không chọn ảnh, sẽ dùng ảnh mặc định.
                </div>
            </div>

            <!-- CHÍNH SÁCH TOUR -->
            <div class="card card-outline card-info mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-file-text"></i> Chính sách Tour
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="chinh_sach_ten" class="form-label">Tên chính sách</label>
                        <input type="text" class="form-control" id="chinh_sach_ten"
                               name="chinh_sach_ten" value="<?= htmlspecialchars($old['chinh_sach_ten'] ?? '') ?>"
                               placeholder="VD: Chính sách hủy tour">
                    </div>
                    <div class="mb-3">
                        <label for="chinh_sach_noi_dung" class="form-label">Nội dung chính sách</label>
                        <textarea class="form-control" id="chinh_sach_noi_dung"
                                  name="chinh_sach_noi_dung" rows="3"
                                  placeholder="Mô tả chi tiết chính sách..."><?= htmlspecialchars($old['chinh_sach_noi_dung'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- LỊCH TRÌNH TOUR -->
            <div class="card card-outline card-success mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-calendar-event"></i> Lịch trình Tour
                    </h5>
                </div>
                <div class="card-body">
                    <label for="lich_trinh" class="form-label">Mô tả lịch trình <span class="text-muted">(không bắt buộc)</span></label>
                    <textarea
                        class="form-control"
                        id="lich_trinh"
                        name="lich_trinh"
                        rows="4"
                        placeholder="Mô tả chi tiết lịch trình tour...
Ví dụ:
Ngày 1: Hà Nội - Hạ Long (Ăn sáng, khởi hành 8:00, tham quan vịnh Hạ Long)
Ngày 2: Hạ Long - Hà Nội (Tham quan hang Sửng Sốt, về Hà Nội)"
                    ><?= htmlspecialchars($old['lich_trinh'] ?? '') ?></textarea>
                    <div class="form-text">Mô tả chi tiết các hoạt động trong tour</div>
                </div>
            </div>

            <!-- NHÀ CUNG CẤP -->
            <div class="card card-outline card-warning mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-building"></i> Nhà cung cấp
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nha_cung_cap_ten" class="form-label">Tên nhà cung cấp</label>
                            <input type="text" class="form-control" id="nha_cung_cap_ten"
                                   name="nha_cung_cap_ten" value="<?= htmlspecialchars($old['nha_cung_cap_ten'] ?? '') ?>"
                                   placeholder="VD: Vietnam Airlines">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nha_cung_cap_loai" class="form-label">Loại</label>
                            <select class="form-select" id="nha_cung_cap_loai" name="nha_cung_cap_loai">
                                <option value="">Chọn loại</option>
                                <option value="hang_khong" <?= ($old['nha_cung_cap_loai'] ?? '') == 'hang_khong' ? 'selected' : '' ?>>Hàng không</option>
                                <option value="khach_san" <?= ($old['nha_cung_cap_loai'] ?? '') == 'khach_san' ? 'selected' : '' ?>>Khách sạn</option>
                                <option value="nha_hang" <?= ($old['nha_cung_cap_loai'] ?? '') == 'nha_hang' ? 'selected' : '' ?>>Nhà hàng</option>
                                <option value="phuong_tien" <?= ($old['nha_cung_cap_loai'] ?? '') == 'phuong_tien' ? 'selected' : '' ?>>Phương tiện</option>
                                <option value="hdv" <?= ($old['nha_cung_cap_loai'] ?? '') == 'hdv' ? 'selected' : '' ?>>Hướng dẫn viên</option>
                                <option value="khac" <?= ($old['nha_cung_cap_loai'] ?? '') == 'khac' ? 'selected' : '' ?>>Khác</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nha_cung_cap_lien_he" class="form-label">Liên hệ</label>
                            <input type="text" class="form-control" id="nha_cung_cap_lien_he"
                                   name="nha_cung_cap_lien_he" value="<?= htmlspecialchars($old['nha_cung_cap_lien_he'] ?? '') ?>"
                                   placeholder="VD: 1900 1886">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ẢNH CHI TIẾT -->
            <div class="card card-outline card-secondary mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-images"></i> Ảnh chi tiết Tour
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="anh_chi_tiet" class="form-label">Chọn nhiều ảnh chi tiết <span class="text-muted">(không bắt buộc)</span></label>
                        <input type="file" class="form-control" id="anh_chi_tiet" name="anh_chi_tiet[]"
                               accept="image/*" multiple>
                        <div class="form-text">
                            Chọn nhiều ảnh (JPG, PNG, GIF).
                        </div>
                    </div>

                    <!-- Container hiển thị preview ảnh -->
                    <div id="preview" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Thêm Tour
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Thêm Tour Mới',
    'pageTitle' => $pageTitle ?? 'Thêm Tour Mới',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/tour-image-preview.js'],
]);
?>