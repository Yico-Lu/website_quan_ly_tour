<?php
    ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Sửa Tour</h3>
    </div>

    <form action="<?= BASE_URL ?>tours/update" method="POST">
        <div class="card-body">
            <!-- Hidden ID -->
            <input type="hidden" name="id" value="<?= $tour->id ?>">

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
                        value="<?= htmlspecialchars($old['ten_tour'] ?? $tour->ten_tour) ?>"
                        placeholder="Nhập tên tour"
                        required
                    />
                </div>

                <!-- Danh mục -->
                <div class="col-md-4 mb-3">
                    <label for="danh_muc_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select class="form-select" id="danh_muc_id" name="danh_muc_id" required>
                        <option value="">Chọn danh mục</option>
                        <?php
                        $selectedDanhMuc = $old['danh_muc_id'] ?? $tour->danh_muc_id;
                        foreach($danhMucList as $danhMuc):
                        ?>
                            <option
                                value="<?= $danhMuc['id'] ?>"
                                <?= $selectedDanhMuc == $danhMuc['id'] ? 'selected' : '' ?>
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
                        value="<?= htmlspecialchars($old['gia'] ?? $tour->gia) ?>"
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
                            <?= ($old['trang_thai'] ?? $tour->trang_thai) ? 'checked' : '' ?>
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
                ><?= htmlspecialchars($old['mo_ta'] ?? $tour->mo_ta) ?></textarea>
            </div>

            <!-- Ảnh tour -->
            <div class="mb-3">
                <label for="anh_tour" class="form-label">Ảnh Tour</label>
                <input
                    type="text"
                    class="form-control"
                    id="anh_tour"
                    name="anh_tour"
                    value="<?= htmlspecialchars($old['anh_tour'] ?? $tour->anh_tour) ?>"
                    placeholder="Đường dẫn ảnh tour (VD: tour-ha-noi.jpg)"
                />
                <div class="form-text">Có thể để trống, sẽ dùng ảnh mặc định</div>
            </div>

            <!-- CHÍNH SÁCH TOUR -->
            <div class="card card-outline card-info mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-file-text"></i> Chính sách Tour
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                    $currentChinhSach = $tour->getChinhSach();
                    $currentCS = !empty($currentChinhSach) ? $currentChinhSach[0] : null;
                    ?>
                    <div class="mb-3">
                        <label for="chinh_sach_ten" class="form-label">Tên chính sách</label>
                        <input type="text" class="form-control" id="chinh_sach_ten"
                               name="chinh_sach_ten" value="<?= htmlspecialchars($old['chinh_sach_ten'] ?? ($currentCS ? $currentCS['ten_chinh_sach'] : '')) ?>"
                               placeholder="VD: Chính sách hủy tour">
                    </div>
                    <div class="mb-3">
                        <label for="chinh_sach_noi_dung" class="form-label">Nội dung chính sách</label>
                        <textarea class="form-control" id="chinh_sach_noi_dung"
                                  name="chinh_sach_noi_dung" rows="3"
                                  placeholder="Mô tả chi tiết chính sách..."><?= htmlspecialchars($old['chinh_sach_noi_dung'] ?? ($currentCS ? $currentCS['noi_dung'] : '')) ?></textarea>
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
                    <?php
                    $lichTrinhHienTai = $tour->getLichTrinh();
                    $lichTrinhText = !empty($lichTrinhHienTai) ? $lichTrinhHienTai[0]['hoat_dong'] : '';
                    ?>
                    <textarea
                        class="form-control"
                        id="lich_trinh"
                        name="lich_trinh"
                        rows="6"
                        placeholder="Mô tả chi tiết lịch trình tour..."
                    ><?= htmlspecialchars($old['lich_trinh'] ?? $lichTrinhText) ?></textarea>
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
                    <?php
                    $currentNCC = $tour->getNhaCungCap();
                    $currentN = !empty($currentNCC) ? $currentNCC[0] : null;
                    ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nha_cung_cap_ten" class="form-label">Tên nhà cung cấp</label>
                            <input type="text" class="form-control" id="nha_cung_cap_ten"
                                   name="nha_cung_cap_ten" value="<?= htmlspecialchars($old['nha_cung_cap_ten'] ?? ($currentN ? $currentN['ten_nha_cung_cap'] : '')) ?>"
                                   placeholder="VD: Vietnam Airlines">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nha_cung_cap_loai" class="form-label">Loại</label>
                            <select class="form-select" id="nha_cung_cap_loai" name="nha_cung_cap_loai">
                                <option value="">Chọn loại</option>
                                <option value="hang_khong" <?= (($old['nha_cung_cap_loai'] ?? ($currentN ? $currentN['loai'] : '')) == 'hang_khong') ? 'selected' : '' ?>>Hàng không</option>
                                <option value="khach_san" <?= (($old['nha_cung_cap_loai'] ?? ($currentN ? $currentN['loai'] : '')) == 'khach_san') ? 'selected' : '' ?>>Khách sạn</option>
                                <option value="nha_hang" <?= (($old['nha_cung_cap_loai'] ?? ($currentN ? $currentN['loai'] : '')) == 'nha_hang') ? 'selected' : '' ?>>Nhà hàng</option>
                                <option value="phuong_tien" <?= (($old['nha_cung_cap_loai'] ?? ($currentN ? $currentN['loai'] : '')) == 'phuong_tien') ? 'selected' : '' ?>>Phương tiện</option>
                                <option value="hdv" <?= (($old['nha_cung_cap_loai'] ?? ($currentN ? $currentN['loai'] : '')) == 'hdv') ? 'selected' : '' ?>>Hướng dẫn viên</option>
                                <option value="khac" <?= (($old['nha_cung_cap_loai'] ?? ($currentN ? $currentN['loai'] : '')) == 'khac') ? 'selected' : '' ?>>Khác</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nha_cung_cap_lien_he" class="form-label">Liên hệ</label>
                            <input type="text" class="form-control" id="nha_cung_cap_lien_he"
                                   name="nha_cung_cap_lien_he" value="<?= htmlspecialchars($old['nha_cung_cap_lien_he'] ?? ($currentN ? $currentN['lien_he'] : '')) ?>"
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
                    <div class="row">
                        <?php
                        $anhChiTietList = $tour->getAnhChiTiet();
                        for($i = 0; $i < 4; $i++):
                            $anh = $anhChiTietList[$i] ?? null;
                        ?>
                        <div class="col-md-6 mb-3">
                            <label for="anh_<?= $i + 1 ?>" class="form-label">Ảnh <?= $i + 1 ?> <span class="text-muted">(không bắt buộc)</span></label>
                            <input type="text" class="form-control" id="anh_<?= $i + 1 ?>" name="anh_chi_tiet[]"
                                   value="<?= htmlspecialchars($anh ? $anh['duong_dan'] : '') ?>"
                                   placeholder="tour-detail-<?= $i + 1 ?>.jpg">
                        </div>
                        <?php endfor; ?>
                    </div>
                    <div class="form-text">Để trống nếu không có ảnh. Hệ thống sẽ dùng ảnh mặc định.</div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Cập nhật Tour
            </button>
            <a href="<?= BASE_URL ?>tours" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Sửa Tour',
    'pageTitle' => $pageTitle ?? 'Sửa Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
]);
?>
