// Thêm chính sách mới
function addChinhSach() {
    const container = document.getElementById('chinh_sach_container');
    const currentItems = document.querySelectorAll('.chinh-sach-item');
    const newIndex = currentItems.length;

    const html = `
        <div class="chinh-sach-item border rounded p-3 mb-3 bg-light">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>Chính sách #${newIndex + 1}</strong>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeChinhSach(this)">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </div>
            <div class="mb-3">
                <label class="form-label">Tên chính sách</label>
                <input type="text" class="form-control" name="chinh_sach[${newIndex}][ten]"
                       placeholder="VD: Chính sách hủy tour, Chính sách đặt tour, Chính sách hoàn tiền...">
            </div>
            <div class="mb-0">
                <label class="form-label">Nội dung chính sách</label>
                <textarea class="form-control" name="chinh_sach[${newIndex}][noi_dung]" rows="3"
                          placeholder="Mô tả chi tiết chính sách..."></textarea>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

// Xóa chính sách
function removeChinhSach(btn) {
    const item = btn.closest('.chinh-sach-item');
    const items = document.querySelectorAll('.chinh-sach-item');

    if (items.length <= 1) {
        alert('Phải có ít nhất 1 chính sách. Nếu không cần, bạn có thể để trống.');
        return;
    }

    item.remove();

    // Cập nhật lại số thứ tự và name attribute
    document.querySelectorAll('.chinh-sach-item').forEach((item, index) => {
        item.querySelector('strong').textContent = `Chính sách #${index + 1}`;

        const tenInput = item.querySelector('input[name*="[ten]"]');
        const noiDungTextarea = item.querySelector('textarea[name*="[noi_dung]"]');

        if (tenInput) tenInput.name = `chinh_sach[${index}][ten]`;
        if (noiDungTextarea) noiDungTextarea.name = `chinh_sach[${index}][noi_dung]`;
    });
}

// Thêm lịch trình mới
function addLichTrinh() {
    const container = document.getElementById('lich_trinh_container');
    const currentItems = document.querySelectorAll('.lich-trinh-item');
    const newIndex = currentItems.length;
    const newDayNumber = newIndex + 1;

    const html = `
        <div class="lich-trinh-item border rounded p-3 mb-3 bg-light">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>Ngày ${newDayNumber}</strong>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeLichTrinh(this)">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Số ngày</label>
                    <input type="number" class="form-control" name="lich_trinh[${newIndex}][ngay]"
                           value="${newDayNumber}" min="1" required>
                </div>
                <div class="col-md-9 mb-3">
                    <label class="form-label">Điểm tham quan</label>
                    <input type="text" class="form-control" name="lich_trinh[${newIndex}][diem_tham_quan]"
                           placeholder="VD: Hồ Hoàn Kiếm, Lăng Bác, Vịnh Hạ Long...">
                </div>
            </div>
            <div class="mb-0">
                <label class="form-label">Hoạt động</label>
                <textarea class="form-control" name="lich_trinh[${newIndex}][hoat_dong]" rows="3"
                          placeholder="Mô tả chi tiết các hoạt động trong ngày..."></textarea>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

// Xóa lịch trình
function removeLichTrinh(btn) {
    const item = btn.closest('.lich-trinh-item');
    const items = document.querySelectorAll('.lich-trinh-item');

    if (items.length <= 1) {
        alert('Phải có ít nhất 1 ngày lịch trình. Nếu không cần, bạn có thể để trống.');
        return;
    }

    item.remove();

    // Cập nhật lại số thứ tự và name attribute
    document.querySelectorAll('.lich-trinh-item').forEach((item, index) => {
        const dayNumber = index + 1;

        item.querySelector('strong').textContent = `Ngày ${dayNumber}`;

        const ngayInput = item.querySelector('input[name*="[ngay]"]');
        const diemThamQuanInput = item.querySelector('input[name*="[diem_tham_quan]"]');
        const hoatDongTextarea = item.querySelector('textarea[name*="[hoat_dong]"]');

        if (ngayInput) {
            ngayInput.name = `lich_trinh[${index}][ngay]`;
            ngayInput.value = dayNumber;
        }
        if (diemThamQuanInput) diemThamQuanInput.name = `lich_trinh[${index}][diem_tham_quan]`;
        if (hoatDongTextarea) hoatDongTextarea.name = `lich_trinh[${index}][hoat_dong]`;
    });
}

// Thêm nhà cung cấp mới
function addNhaCungCap() {
    const container = document.getElementById('nha_cung_cap_container');
    const currentItems = document.querySelectorAll('.nha-cung-cap-item');
    const newIndex = currentItems.length;

    const html = `
        <div class="nha-cung-cap-item border rounded p-3 mb-3 bg-light">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>Nhà cung cấp #${newIndex + 1}</strong>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeNhaCungCap(this)">
                    <i class="bi bi-trash"></i> Xóa
                </button>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tên nhà cung cấp</label>
                    <input type="text" class="form-control" name="nha_cung_cap[${newIndex}][ten]"
                           placeholder="VD: Vietnam Airlines, Khách sạn ABC...">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Loại</label>
                    <select class="form-select" name="nha_cung_cap[${newIndex}][loai]">
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
                    <input type="text" class="form-control" name="nha_cung_cap[${newIndex}][lien_he]"
                           placeholder="VD: 1900 1886,...">
                </div>
            </div>
            <div class="mb-0">
                <label class="form-label">Ghi chú</label>
                <textarea class="form-control" name="nha_cung_cap[${newIndex}][ghi_chu]" rows="2"
                          placeholder="Ghi chú về nhà cung cấp..."></textarea>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

// Xóa nhà cung cấp
function removeNhaCungCap(btn) {
    const item = btn.closest('.nha-cung-cap-item');
    const items = document.querySelectorAll('.nha-cung-cap-item');

    if (items.length <= 1) {
        alert('Phải có ít nhất 1 nhà cung cấp. Nếu không cần, bạn có thể để trống.');
        return;
    }

    item.remove();

    // Cập nhật lại số thứ tự và name attribute
    document.querySelectorAll('.nha-cung-cap-item').forEach((item, index) => {
        item.querySelector('strong').textContent = `Nhà cung cấp #${index + 1}`;

        const tenInput = item.querySelector('input[name*="[ten]"]');
        const loaiSelect = item.querySelector('select[name*="[loai]"]');
        const lienHeInput = item.querySelector('input[name*="[lien_he]"]');
        const ghiChuTextarea = item.querySelector('textarea[name*="[ghi_chu]"]');

        if (tenInput) tenInput.name = `nha_cung_cap[${index}][ten]`;
        if (loaiSelect) loaiSelect.name = `nha_cung_cap[${index}][loai]`;
        if (lienHeInput) lienHeInput.name = `nha_cung_cap[${index}][lien_he]`;
        if (ghiChuTextarea) ghiChuTextarea.name = `nha_cung_cap[${index}][ghi_chu]`;
    });
}

