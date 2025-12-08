// Lấy BASE_URL từ data attribute hoặc window object
const BASE_URL = window.BASE_URL || document.querySelector('meta[name="base-url"]')?.content || '';

function toggleLkhEdit(isEdit) {
    const view = document.getElementById('lkhView');
    const form = document.getElementById('lkhForm');
    const btnEdit = document.getElementById('btnLkhEdit');
    const btnCancel = document.getElementById('btnLkhCancel');
    if (isEdit) {
        view.classList.add('d-none');
        form.classList.remove('d-none');
        btnEdit.classList.add('d-none');
        btnCancel.classList.remove('d-none');
    } else {
        view.classList.remove('d-none');
        form.classList.add('d-none');
        btnEdit.classList.remove('d-none');
        btnCancel.classList.add('d-none');
    }
}

function loadKhachList(bookingId) {
    const errorEl = document.getElementById('khachListError');
    const contentEl = document.getElementById('khachListContent');
    const tableBody = document.getElementById('khachListTableBody');
    
    // Reset UI
    errorEl.classList.add('d-none');
    tableBody.innerHTML = '';
    
    // Gọi API
    fetch(BASE_URL + 'bookings/view-khach-list/' + bookingId)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                errorEl.textContent = data.error;
                errorEl.classList.remove('d-none');
                contentEl.style.display = 'none';
                return;
            }
            
            if (data.success && data.data && data.data.length > 0) {
                // Lưu bookingId và lichKhoiHanhId để dùng khi lưu
                tableBody.dataset.bookingId = bookingId;
                tableBody.dataset.lichKhoiHanhId = data.lich_khoi_hanh_id || '';
                
                // Render table
                data.data.forEach(item => {
                    const row = document.createElement('tr');
                    const isChecked = item.trang_thai === 'da_den' || item.trang_thai === 'co_mat';
                    row.innerHTML = `
                        <td>${item.stt}</td>
                        <td><strong>${escapeHtml(item.ho_ten)}</strong></td>
                        <td>${escapeHtml(item.gioi_tinh || '-')}</td>
                        <td>${escapeHtml(item.nam_sinh || '-')}</td>
                        <td>${escapeHtml(item.so_giay_to || '-')}</td>
                        <td>${escapeHtml(item.yeu_cau_ca_nhan || '-')}</td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center justify-content-center gap-2">
                                <span class="small text-muted">Vắng</span>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input attendance-switch" type="checkbox" role="switch" 
                                           data-ho-ten="${escapeHtml(item.ho_ten)}"
                                           data-gioi-tinh="${escapeHtml(item.gioi_tinh || '')}"
                                           data-nam-sinh="${escapeHtml(item.nam_sinh || '')}"
                                           data-so-giay-to="${escapeHtml(item.so_giay_to || '')}"
                                           ${isChecked ? 'checked' : ''}>
                                </div>
                                <span class="small text-muted">Có mặt</span>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
                
                contentEl.style.display = 'block';
            } else {
                errorEl.textContent = 'Không có dữ liệu khách hàng';
                errorEl.classList.remove('d-none');
                contentEl.style.display = 'none';
            }
        })
        .catch(error => {
            errorEl.textContent = 'Lỗi khi tải dữ liệu: ' + error.message;
            errorEl.classList.remove('d-none');
            contentEl.style.display = 'none';
        });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function toggleDichVuForm(open) {
    const form = document.getElementById('dichVuForm');
    const btnAdd = document.getElementById('btnDichVuAdd');
    const btnCancel = document.getElementById('btnDichVuCancel');
    if (open) {
        form.classList.remove('d-none');
        btnCancel.classList.remove('d-none');
        btnAdd.classList.add('d-none');
    } else {
        form.classList.add('d-none');
        btnCancel.classList.add('d-none');
        btnAdd.classList.remove('d-none');
        // reset fields
        document.getElementById('dich_vu_id').value = '';
        document.getElementById('ten_dich_vu').value = '';
        document.getElementById('chi_tiet').value = '';
    }
}

function onEditDichVu(btn) {
    const id = btn.getAttribute('data-id') || '';
    const ten = btn.getAttribute('data-ten') || '';
    const chitiet = btn.getAttribute('data-chitiet') || '';
    
    // Decode HTML entities
    const tenDecoded = decodeHtml(ten);
    const chitietDecoded = decodeHtml(chitiet);
    
    document.getElementById('dich_vu_id').value = id;
    document.getElementById('ten_dich_vu').value = tenDecoded;
    document.getElementById('chi_tiet').value = chitietDecoded;
    toggleDichVuForm(true);
    
    // Scroll to form
    document.getElementById('dichVuForm').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function decodeHtml(html) {
    const txt = document.createElement('textarea');
    txt.innerHTML = html;
    return txt.value;
}

function saveAttendance(bookingId) {
    const tableBody = document.getElementById('khachListTableBody');
    const switches = tableBody.querySelectorAll('.attendance-switch');
    const lichKhoiHanhId = tableBody.dataset.lichKhoiHanhId || '';
    
    if (!lichKhoiHanhId) {
        alert('Vui lòng cập nhật lịch khởi hành trước khi điểm danh');
        return;
    }
    
    const attendanceData = [];
    switches.forEach(switchEl => {
        attendanceData.push({
            ho_ten: switchEl.dataset.hoTen,
            gioi_tinh: switchEl.dataset.gioiTinh,
            nam_sinh: switchEl.dataset.namSinh,
            so_giay_to: switchEl.dataset.soGiayTo,
            trang_thai: switchEl.checked ? 'da_den' : 'vang'
        });
    });
    
    fetch(BASE_URL + 'bookings/save-attendance-excel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            booking_id: bookingId,
            lich_khoi_hanh_id: lichKhoiHanhId,
            attendance: attendanceData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Đã lưu điểm danh thành công');
            const modal = bootstrap.Modal.getInstance(document.getElementById('khachListModal'));
            if (modal) modal.hide();
        } else {
            alert('Lỗi: ' + (data.error || 'Không thể lưu điểm danh'));
        }
    })
    .catch(error => {
        alert('Lỗi khi lưu điểm danh: ' + error.message);
    });
}

