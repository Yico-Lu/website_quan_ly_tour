<?php
    ob_start();
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Sửa Tour</h3>
    </div>

    <form action="<?= BASE_URL ?>tours/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $tour->id ?>">
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

            <!-- Hiển thị thông báo flash -->
            <?php
            $flashMessages = getFlashMessages();
            foreach ($flashMessages as $message):
                $alertClass = $message['type'] === 'success' ? 'alert-success' : 'alert-danger';
                $icon = $message['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill';
                $title = $message['type'] === 'success' ? 'Thành công' : 'Lỗi';
            ?>
                <div class="alert <?= $alertClass ?> fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi <?= $icon ?> me-2 fs-5"></i>
                        <strong><?= $title ?>:</strong>
                        <span class="ms-2"><?= htmlspecialchars($message['message']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>

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
                    type="file"
                    class="form-control"
                    id="anh_tour"
                    name="anh_tour"
                    accept="image/*"
                />
                <?php if(!empty($tour->anh_tour)): ?>
                    <div class="mt-2">
                        <small class="text-muted">Ảnh hiện tại:</small><br>
                        <img src="<?= asset($tour->anh_tour) ?>" alt="Ảnh hiện tại"
                             style="max-width: 100px; max-height: 100px;" class="border rounded">
                    </div>
                <?php endif; ?>
                <div class="form-text">
                    Chọn ảnh mới để thay đổi (JPG, PNG, GIF). Kích thước tối đa 2MB.<br>
                    Nếu không chọn ảnh, sẽ giữ ảnh hiện tại.
                </div>
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
                    <?php
                    $currentChinhSach = $tour->getChinhSach();
                    $oldChinhSach = $old['chinh_sach'] ?? [];
                    // Nếu có dữ liệu cũ từ form (khi có lỗi), dùng dữ liệu cũ, nếu không thì dùng dữ liệu từ database
                    $chinhSachToShow = !empty($oldChinhSach) ? $oldChinhSach : $currentChinhSach;
                    // Nếu không có dữ liệu, tạo 1 item mặc định
                    if (empty($chinhSachToShow)) {
                        $chinhSachToShow = [['ten_chinh_sach' => '', 'noi_dung' => '']];
                    }
                    ?>
                    <?php foreach($chinhSachToShow as $index => $cs): ?>
                    <div class="chinh-sach-item border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Chính sách #<?= $index + 1 ?></strong>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeChinhSach(this)">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên chính sách</label>
                            <input type="text" class="form-control" name="chinh_sach[<?= $index ?>][ten]"
                                   value="<?= htmlspecialchars($oldChinhSach[$index]['ten'] ?? $cs['ten_chinh_sach'] ?? '') ?>"
                                   placeholder="VD: Chính sách hủy tour, Chính sách đặt tour, Chính sách hoàn tiền...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Nội dung chính sách</label>
                            <textarea class="form-control" name="chinh_sach[<?= $index ?>][noi_dung]" rows="3"
                                      placeholder="Mô tả chi tiết chính sách..."><?= htmlspecialchars($oldChinhSach[$index]['noi_dung'] ?? $cs['noi_dung'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
                    <?php
                    $currentLichTrinh = $tour->getLichTrinh();
                    $oldLichTrinh = $old['lich_trinh'] ?? [];
                    // Nếu có dữ liệu cũ từ form (khi có lỗi), dùng dữ liệu cũ, nếu không thì dùng dữ liệu từ database
                    $lichTrinhToShow = !empty($oldLichTrinh) ? $oldLichTrinh : $currentLichTrinh;
                    // Nếu không có dữ liệu, tạo 1 item mặc định
                    if (empty($lichTrinhToShow)) {
                        $lichTrinhToShow = [['ngay' => 1, 'diem_tham_quan' => '', 'hoat_dong' => '']];
                    }
                    ?>
                    <?php foreach($lichTrinhToShow as $index => $lt): ?>
                    <div class="lich-trinh-item border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Ngày <?= $lt['ngay'] ?? ($index + 1) ?></strong>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeLichTrinh(this)">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Điểm tham quan</label>
                            <input type="text" class="form-control" name="lich_trinh[<?= $index ?>][diem_tham_quan]"
                                   value="<?= htmlspecialchars($oldLichTrinh[$index]['diem_tham_quan'] ?? $lt['diem_tham_quan'] ?? '') ?>"
                                   placeholder="VD: Hồ Hoàn Kiếm, Lăng Bác, Vịnh Hạ Long...">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Hoạt động</label>
                            <textarea class="form-control" name="lich_trinh[<?= $index ?>][hoat_dong]" rows="3"
                                      placeholder="Mô tả chi tiết các hoạt động trong ngày..."><?= htmlspecialchars($oldLichTrinh[$index]['hoat_dong'] ?? $lt['hoat_dong'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
                    <?php
                    $currentNCC = $tour->getNhaCungCap();
                    $oldNCC = $old['nha_cung_cap'] ?? [];
                    // Nếu có dữ liệu cũ từ form (khi có lỗi), dùng dữ liệu cũ, nếu không thì dùng dữ liệu từ database
                    $nccToShow = !empty($oldNCC) ? $oldNCC : $currentNCC;
                    // Nếu không có dữ liệu, tạo 1 item mặc định
                    if (empty($nccToShow)) {
                        $nccToShow = [['ten_nha_cung_cap' => '', 'loai' => '', 'lien_he' => '', 'ghi_chu' => '']];
                    }
                    ?>
                    <?php foreach($nccToShow as $index => $ncc): ?>
                    <div class="nha-cung-cap-item border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Nhà cung cấp #<?= $index + 1 ?></strong>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeNhaCungCap(this)">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tên nhà cung cấp</label>
                                <input type="text" class="form-control" name="nha_cung_cap[<?= $index ?>][ten]"
                                       value="<?= htmlspecialchars($oldNCC[$index]['ten'] ?? $ncc['ten_nha_cung_cap'] ?? '') ?>"
                                       placeholder="VD: Vietnam Airlines, Khách sạn ABC...">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Loại</label>
                                <select class="form-select" name="nha_cung_cap[<?= $index ?>][loai]">
                                    <option value="">Chọn loại</option>
                                    <?php
                                    $selectedLoai = $oldNCC[$index]['loai'] ?? $ncc['loai'] ?? '';
                                    $loaiOptions = [
                                        'hang_khong' => 'Hàng không',
                                        'khach_san' => 'Khách sạn',
                                        'nha_hang' => 'Nhà hàng',
                                        'phuong_tien' => 'Phương tiện',
                                        'hdv' => 'Hướng dẫn viên',
                                        'khac' => 'Khác'
                                    ];
                                    foreach($loaiOptions as $value => $label):
                                    ?>
                                    <option value="<?= $value ?>" <?= $selectedLoai == $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Liên hệ</label>
                                <input type="text" class="form-control" name="nha_cung_cap[<?= $index ?>][lien_he]"
                                       value="<?= htmlspecialchars($oldNCC[$index]['lien_he'] ?? $ncc['lien_he'] ?? '') ?>"
                                       placeholder="VD: 1900 1886, 0909123456...">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" name="nha_cung_cap[<?= $index ?>][ghi_chu]" rows="2"
                                      placeholder="Ghi chú về nhà cung cấp..."><?= htmlspecialchars($oldNCC[$index]['ghi_chu'] ?? $ncc['ghi_chu'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
                    <!-- Hiển thị ảnh hiện tại -->
                    <?php $anhChiTietList = $tour->getAnhChiTiet(); ?>
                    <?php if(!empty($anhChiTietList)): ?>
                    <div class="mb-3">
                        <small class="text-muted">Ảnh hiện tại:</small>
                        <div class="row mt-2 g-2">
                            <?php foreach($anhChiTietList as $anh): ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="card position-relative">
                                    <img src="<?= asset($anh['duong_dan']) ?>" alt="Ảnh chi tiết"
                                         class="card-img-top" style="height: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"
                                            onclick="deleteExistingImage(<?= $anh['id'] ?>)">
                                        ×
                                    </button>
                                    <div class="card-body p-2">
                                        <small class="text-muted text-truncate d-block" title="<?= basename($anh['duong_dan']) ?>">
                                            <?= basename($anh['duong_dan']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Upload ảnh mới -->
                    <div class="mb-3">
                        <label for="anh_chi_tiet" class="form-label">Thêm ảnh chi tiết mới <span class="text-muted">(không bắt buộc)</span></label>
                        <input type="file" class="form-control" id="anh_chi_tiet" name="anh_chi_tiet[]"
                               accept="image/*" multiple>
                        <div class="form-text">
                            Chọn thêm ảnh mới (JPG, PNG, GIF). Nhấn Ctrl để chọn nhiều ảnh.
                        </div>
                        <!-- Container preview ảnh mới -->
                        <div id="preview" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden inputs cho ảnh cần xóa -->
        <input type="hidden" id="delete_images" name="delete_images" value="">

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Cập nhật Tour
            </button>
            <a href="<?= BASE_URL ?>tours" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>

    <script>
        // Xử lý xóa ảnh
        let imagesToDelete = [];

        function deleteExistingImage(imageId) {
            imagesToDelete.push(imageId);
            document.getElementById('delete_images').value = imagesToDelete.join(',');
            event.target.closest('.col-md-3').style.display = 'none';
        }

        // ============================================
        // HÀM THÊM CHÍNH SÁCH MỚI
        // ============================================
    </script>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => $title ?? 'Sửa Tour',
    'pageTitle' => $pageTitle ?? 'Sửa Tour',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'extraJs' => ['js/tour-image-preview.js', 'js/tour-form.js'],
]);
?>
