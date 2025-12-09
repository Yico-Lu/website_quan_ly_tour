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
                <div class="alert alert-danger fade show" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                        <strong>Có lỗi xảy ra</strong>
                    </div>
                    <ul class="mb-0 ps-3">
                        <?php foreach($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php displayFlashMessages(); ?>

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
                    rows="2"
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
            </div>

            <!-- CHÍNH SÁCH TOUR -->
            <div class="card card-outline card-info mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-text"></i> Chính sách Tour
                    </h5>
                    <button type="button" class="btn btn-sm btn-success" onclick="addChinhSach()">
                        <i class="bi bi-plus-circle"></i> Thêm chính sách
                    </button>
                </div>
                <div class="card-body" id="chinh_sach_container">
                    <!-- Chính sách mặc định (có thể xóa) -->
                    <div class="chinh-sach-item border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Chính sách #1</strong>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeChinhSach(this)">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên chính sách</label>
                            <input type="text" class="form-control" name="chinh_sach[0][ten]"
                                   placeholder="VD: Chính sách hủy tour, Chính sách đặt tour, Chính sách hoàn tiền...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Nội dung chính sách</label>
                            <textarea class="form-control" name="chinh_sach[0][noi_dung]" rows="3"
                                      placeholder="Mô tả chi tiết chính sách..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LỊCH TRÌNH TOUR -->
            <div class="card card-outline card-success mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-event"></i> Lịch trình Tour
                    </h5>
                    <button type="button" class="btn btn-sm btn-success" onclick="addLichTrinh()">
                        <i class="bi bi-plus-circle"></i> Thêm ngày
                    </button>
                </div>
                <div class="card-body" id="lich_trinh_container">
                    <!-- Lịch trình mặc định (có thể xóa) -->
                    <div class="lich-trinh-item border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Ngày 1</strong>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeLichTrinh(this)">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Điểm tham quan</label>
                            <input type="text" class="form-control" name="lich_trinh[0][diem_tham_quan]"
                                   placeholder="VD: Hồ Hoàn Kiếm, Lăng Bác, Vịnh Hạ Long...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Hoạt động</label>
                            <textarea class="form-control" name="lich_trinh[0][hoat_dong]" rows="3"
                                      placeholder="Mô tả chi tiết các hoạt động trong ngày..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NHÀ CUNG CẤP -->
            <div class="card card-outline card-warning mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-building"></i> Nhà cung cấp
                    </h5>
                    <button type="button" class="btn btn-sm btn-success" onclick="addNhaCungCap()">
                        <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp
                    </button>
                </div>
                <div class="card-body" id="nha_cung_cap_container">
                    <!-- Nhà cung cấp mặc định (có thể xóa) -->
                    <div class="nha-cung-cap-item border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Nhà cung cấp #1</strong>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeNhaCungCap(this)">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tên nhà cung cấp</label>
                                <input type="text" class="form-control" name="nha_cung_cap[0][ten]"
                                       placeholder="VD: Vietnam Airlines, Khách sạn ABC...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Loại</label>
                                <select class="form-select" name="nha_cung_cap[0][loai]">
                                    <option value="">Chọn loại</option>
                                    <option value="hang_khong">Hàng không</option>
                                    <option value="khach_san">Khách sạn</option>
                                    <option value="nha_hang">Nhà hàng</option>
                                    <option value="phuong_tien">Phương tiện</option>
                                    <option value="hdv">Hướng dẫn viên</option>
                                    <option value="khac">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Liên hệ</label>
                                <input type="text" class="form-control" name="nha_cung_cap[0][lien_he]"
                                       placeholder="VD: 1900 1886">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" name="nha_cung_cap[0][ghi_chu]" rows="2"
                                      placeholder="Ghi chú về nhà cung cấp..."></textarea>
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
    'extraJs' => ['js/tour-image-preview.js', 'js/tour-form.js'],
]);
?>