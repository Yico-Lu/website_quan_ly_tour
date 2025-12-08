document.addEventListener('DOMContentLoaded', () => {
    initValidation();
    initKhachForm();
    initTourInfo();
});

function initValidation() {
    const form = document.getElementById('bookingEditForm');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        let isValid = true;
        const required = Array.from(form.querySelectorAll('[data-required="true"], [required]'));

        form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach((el) => el.remove());

        required.forEach((field) => {
            if (!form.contains(field)) return;
            if (field.id === 'hdv_id' || field.name === 'hdv_id') return;
            if (field.tagName === 'SELECT' && !field.hasAttribute('required') && !field.hasAttribute('data-required')) return;

            const value = (field.value || '').trim();
            if (value) return;

            isValid = false;
            field.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = messageFor(field);
            field.parentNode.appendChild(feedback);
        });

        if (!isValid) e.preventDefault();
    });
}

function messageFor(field) {
    const id = field.id || '';
    if (id === 'tour_id') return 'Vui lòng chọn tour';
    if (id === 'ten_nguoi_dat') return 'Vui lòng nhập tên người đặt';
    if (id === 'thoi_gian_tour') return 'Vui lòng chọn thời gian tour';
    if (id === 'lien_he') return 'Vui lòng nhập thông tin liên hệ';
    if (id === 'ten_dich_vu' || (field.name || '').includes('ten_dich_vu')) return 'Vui lòng nhập tên dịch vụ';
    if ((field.name || '').includes('[ho_ten]')) return 'Vui lòng nhập họ tên';
    const label = field.form?.querySelector(`label[for="${field.id}"]`);
    return 'Vui lòng nhập ' + (label ? label.textContent.replace(/\*/g, '').trim().toLowerCase() : 'trường này');
}

function initKhachForm() {
    const container = document.getElementById('khach_container');
    const dataEl = document.getElementById('khach_data_edit');
    if (!container || !dataEl) return;

    const current = safeJson(dataEl.dataset.currentKhachs) || [];
    const old = safeJson(dataEl.dataset.oldKhachs) || [];
    let khachIndex = 0;

    const escapeHtml = (text = '') => {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    const createForm = (index, data = {}) => {
        const card = document.createElement('div');
        card.className = 'card mb-3 khach-card';
        const khachId = data.id || '';
        card.innerHTML = `
            <div class="card-header bg-light">
                <strong><span class="badge bg-primary me-2">Người đại diện</span>Thông tin người đại diện</strong>
            </div>
            <div class="card-body">
                ${khachId ? `<input type="hidden" name="khach[${index}][id]" value="${khachId}">` : ''}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="khach[${index}][ho_ten]"
                               value="${escapeHtml(data.ho_ten)}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giới tính</label>
                        <select class="form-select" name="khach[${index}][gioi_tinh]">
                            <option value="">-- Chọn --</option>
                            <option value="nam" ${(data.gioi_tinh || '') === 'nam' ? 'selected' : ''}>Nam</option>
                            <option value="nu" ${(data.gioi_tinh || '') === 'nu' ? 'selected' : ''}>Nữ</option>
                            <option value="khac" ${(data.gioi_tinh || '') === 'khac' ? 'selected' : ''}>Khác</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Năm sinh</label>
                        <input type="number" class="form-control" name="khach[${index}][nam_sinh]"
                               value="${escapeHtml(data.nam_sinh)}" min="1900" max="2100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số giấy tờ</label>
                        <input type="text" class="form-control" name="khach[${index}][so_giay_to]"
                               value="${escapeHtml(data.so_giay_to)}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tình trạng thanh toán</label>
                        <select class="form-select" name="khach[${index}][tinh_trang_thanh_toan]">
                            <option value="chua_thanh_toan" ${(data.tinh_trang_thanh_toan || 'chua_thanh_toan') === 'chua_thanh_toan' ? 'selected' : ''}>Chưa thanh toán</option>
                            <option value="da_coc" ${(data.tinh_trang_thanh_toan || '') === 'da_coc' ? 'selected' : ''}>Đã cọc</option>
                            <option value="da_thanh_toan" ${(data.tinh_trang_thanh_toan || '') === 'da_thanh_toan' ? 'selected' : ''}>Đã thanh toán</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Yêu cầu cá nhân</label>
                    <textarea class="form-control" name="khach[${index}][yeu_cau_ca_nhan]" rows="2"
                              placeholder="Nhập yêu cầu cá nhân (nếu có)">${escapeHtml(data.yeu_cau_ca_nhan)}</textarea>
                </div>
            </div>
        `;
        return card;
    };

    const addForm = (data = {}) => {
        container.appendChild(createForm(khachIndex, data));
        khachIndex++;
    };

    const firstData = (old.length > 0 ? old : current)[0] || {};
    addForm(firstData);

    const updateTenNguoiDat = () => {
        const first = container.querySelector('.khach-card');
        if (!first) return;
        const hoTen = first.querySelector('input[name*="[ho_ten]"]');
        const tenNguoiDatInput = document.getElementById('ten_nguoi_dat');
        if (hoTen && tenNguoiDatInput) {
            tenNguoiDatInput.value = hoTen.value;
        }
    };

    container.addEventListener('input', (e) => {
        if (e.target.name && e.target.name.includes('[ho_ten]') && e.target.closest('.khach-card')) {
            updateTenNguoiDat();
        }
    });

    const bookingForm = document.getElementById('bookingEditForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', updateTenNguoiDat);
    }
}

function safeJson(value) {
    try {
        return value ? JSON.parse(value) : null;
    } catch {
        return null;
    }
}

function initTourInfo() {
    const tourSelect = document.getElementById('tour_id');
    if (!tourSelect) return;

    const formatPrice = (p) => p ? Number(p).toLocaleString('vi-VN') + ' VND' : '-';

    const updateTourInfo = () => {
        const opt = tourSelect.options[tourSelect.selectedIndex];
        const gia = opt?.dataset.gia || '';
        const priceEl = document.getElementById('tour_price_edit');
        if (priceEl) priceEl.textContent = formatPrice(gia);
    };

    tourSelect.addEventListener('change', updateTourInfo);
    updateTourInfo();
}

