document.addEventListener('DOMContentLoaded', () => {
    // Khởi tạo khách đại diện (nếu có form)
    const khachContainer = document.getElementById('khach_container');
    let khachIndex = 0;
    const oldKhachs = (typeof window.OLD_KHACH_DATA !== 'undefined' && Array.isArray(window.OLD_KHACH_DATA)) 
        ? window.OLD_KHACH_DATA 
        : [];
    // Khởi tạo dịch vụ booking (giữ lại để auto-fill nếu cần)
    const dichVuContainer = document.getElementById('dich_vu_container');
    const oldDichVu = (typeof window.OLD_DICH_VU !== 'undefined' && Array.isArray(window.OLD_DICH_VU))
        ? window.OLD_DICH_VU
        : [];

    const escapeHtml = (text = '') => {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    const createKhachForm = (index, data = {}) => {
        const card = document.createElement('div');
        card.className = 'card mb-3 khach-card';
        card.innerHTML = `
            <div class="card-body">
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

    const addKhachForm = (data = {}) => {
        if (!khachContainer) return;
        khachContainer.appendChild(createKhachForm(khachIndex, data));
        khachIndex++;
    };

    if (khachContainer) {
        addKhachForm(oldKhachs[0] || {});
    }

    const updateTenNguoiDat = () => {
        const first = khachContainer.querySelector('.khach-card');
        if (!first) return;
        const hoTenInput = first.querySelector('input[name*="[ho_ten]"]');
        const tenNguoiDatInput = document.getElementById('ten_nguoi_dat');
        if (hoTenInput && tenNguoiDatInput) {
            tenNguoiDatInput.value = hoTenInput.value;
        }
    };

    if (khachContainer) {
        khachContainer.addEventListener('input', (e) => {
            if (e.target.name && e.target.name.includes('[ho_ten]') && e.target.closest('.khach-card')) {
                updateTenNguoiDat();
            }
        });

        const bookingForm = document.querySelector('form[action*="bookings"]');
        if (bookingForm) {
            bookingForm.addEventListener('submit', updateTenNguoiDat);
        }
    }

    // Hiển thị giá và ngày tour khi chọn tour
    const tourSelect = document.getElementById('tour_id');
    const priceEl = document.getElementById('tour_price_create');
    const startInput = document.getElementById('ngay_gio_xuat_phat');
    const endInput = document.getElementById('thoi_gian_ket_thuc');

    const formatPrice = (p) => p ? Number(p).toLocaleString('vi-VN') + ' VND' : '-';

    const parseDaysFromTour = () => {
        if (!tourSelect) return null;
        const opt = tourSelect.options[tourSelect.selectedIndex];
        const txt = opt ? opt.textContent : '';
        const match = txt && txt.match(/(\d+)\s*ngày/i);
        return match ? parseInt(match[1], 10) : null;
    };

    const updateEndTimeFromStart = () => {
        if (!startInput || !endInput) return;
        const startVal = startInput.value;
        if (!startVal) {
            endInput.value = '';
            return;
        }
        const days = parseDaysFromTour();
        if (!days || Number.isNaN(days)) return;
        const startDate = new Date(startVal);
        if (Number.isNaN(startDate.getTime())) return;
        const endDate = new Date(startDate.getTime());
        endDate.setDate(endDate.getDate() + days);
        const endStr = endDate.toISOString().slice(0, 16);
        endInput.value = endStr;
    };

    const updateTourInfo = () => {
        if (!tourSelect || !priceEl) return;
        const opt = tourSelect.options[tourSelect.selectedIndex];
        const gia = opt?.dataset.gia || '';
        priceEl.textContent = formatPrice(gia);
        updateEndTimeFromStart();
    };

    if (tourSelect) {
        tourSelect.addEventListener('change', updateTourInfo);
        updateTourInfo();
    }

    if (startInput) {
        startInput.addEventListener('change', () => {
            updateEndTimeFromStart();
            updateTourInfo();
        });
    }
});

function safeJson(value) {
    try {
        return value ? JSON.parse(value) : null;
    } catch {
        return null;
    }
}

