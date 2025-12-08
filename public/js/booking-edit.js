document.addEventListener('DOMContentLoaded', function () {
    // Validate form (JS)
    const mainForm = document.getElementById('bookingEditForm');
    if (mainForm) {
        attachValidation(mainForm);
    }

    function attachValidation(form) {
        form.addEventListener('submit', function (e) {
            if (form.id !== 'bookingEditForm') {
                return;
            }

            let isValid = true;
            const requiredFields = Array.from(
                form.querySelectorAll('[data-required="true"], [required]')
            ).filter((field) => form.contains(field));

            form.querySelectorAll('.is-invalid').forEach((field) => field.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach((feedback) => feedback.remove());

            requiredFields.forEach((field) => {
                if (!form.contains(field)) return;
                if (field.id === 'hdv_id' || field.name === 'hdv_id') return;
                if (field.tagName === 'SELECT' && !field.hasAttribute('required') && !field.hasAttribute('data-required')) {
                    return;
                }

                const value = (field.value || '').trim();
                if (!value) {
                    isValid = false;
                    field.classList.add('is-invalid');

                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';

                    const label = form.querySelector('label[for="' + field.id + '"]');
                    let labelText = 'Trường này';
                    if (label) {
                        labelText = label.textContent.replace(/\*/g, '').trim();
                    }

                    if (field.id === 'tour_id') {
                        feedback.textContent = 'Vui lòng chọn tour';
                    } else if (field.id === 'ten_nguoi_dat') {
                        feedback.textContent = 'Vui lòng nhập tên người đặt';
                    } else if (field.id === 'thoi_gian_tour') {
                        feedback.textContent = 'Vui lòng chọn thời gian tour';
                    } else if (field.id === 'lien_he') {
                        feedback.textContent = 'Vui lòng nhập thông tin liên hệ';
                    } else if (field.id === 'ten_dich_vu' || field.name === 'ten_dich_vu') {
                        feedback.textContent = 'Vui lòng nhập tên dịch vụ';
                    } else if (field.name && field.name.includes('[ho_ten]')) {
                        feedback.textContent = 'Vui lòng nhập họ tên';
                    } else {
                        feedback.textContent = 'Vui lòng nhập ' + labelText.toLowerCase();
                    }

                    if (feedback.textContent) {
                        field.parentNode.appendChild(feedback);
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const khachContainer = document.getElementById('khach_container');
    const khachDataEl = document.getElementById('khach_data_edit');
    if (!khachContainer || !khachDataEl) return;

    let khachIndex = 0;
    const currentKhachs = parseJsonSafe(khachDataEl.dataset.currentKhachs) || [];
    const oldKhachs = parseJsonSafe(khachDataEl.dataset.oldKhachs) || [];

    function createKhachForm(index, data = {}) {
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
                               value="${escapeHtml(data.ho_ten || '')}" required>
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
                               value="${escapeHtml(data.nam_sinh || '')}" min="1900" max="2100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số giấy tờ</label>
                        <input type="text" class="form-control" name="khach[${index}][so_giay_to]"
                               value="${escapeHtml(data.so_giay_to || '')}">
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
                              placeholder="Nhập yêu cầu cá nhân (nếu có)">${escapeHtml(data.yeu_cau_ca_nhan || '')}</textarea>
                </div>
            </div>
        `;
        return card;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function parseJsonSafe(value) {
        try {
            return value ? JSON.parse(value) : null;
        } catch (e) {
            console.error('Parse JSON error:', e);
            return null;
        }
    }

    function addKhachForm(data = {}) {
        const form = createKhachForm(khachIndex, data);
        khachContainer.appendChild(form);
        khachIndex++;
    }

    const khachsToLoad = oldKhachs.length > 0 ? oldKhachs : currentKhachs;
    if (khachsToLoad.length > 0) {
        addKhachForm(khachsToLoad[0]);
    } else {
        addKhachForm();
    }

    function updateTenNguoiDat() {
        const firstKhachCard = khachContainer.querySelector('.khach-card');
        if (firstKhachCard) {
            const firstHoTenInput = firstKhachCard.querySelector('input[name*="[ho_ten]"]');
            const tenNguoiDatInput = document.getElementById('ten_nguoi_dat');
            if (firstHoTenInput && tenNguoiDatInput) {
                tenNguoiDatInput.value = firstHoTenInput.value;
            }
        }
    }

    khachContainer.addEventListener('input', (e) => {
        if (e.target.name && e.target.name.includes('[ho_ten]')) {
            const firstKhachCard = khachContainer.querySelector('.khach-card');
            if (firstKhachCard && e.target.closest('.khach-card') === firstKhachCard) {
                updateTenNguoiDat();
            }
        }
    });

    const bookingForm = document.getElementById('bookingEditForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function () {
            updateTenNguoiDat();
        });
    }
});

