// JS cho màn chi tiết booking của HDV
function toggleCheckIn(khachId, isCheckIn) {
    const data = window.guideBookingData || {};
    const lichKhoiHanhId = data.lichKhoiHanhId;
    if (!lichKhoiHanhId) {
        alert('Vui lòng cập nhật lịch khởi hành trước khi check-in.');
        return;
    }

    const formData = new FormData();
    formData.append('booking_id', data.bookingId);
    formData.append('lich_khoi_hanh_id', lichKhoiHanhId);
    formData.append('booking_khach_id', khachId);
    formData.append('trang_thai', isCheckIn ? 'da_den' : 'vang_mat');

    fetch(data.baseUrl + 'guide/check-in', {
        method: 'POST',
        body: formData
    }).then(() => {
        location.reload();
    });
}

function toggleCheckInFromFile(idx, isCheckIn) {
    const data = window.guideBookingData || {};
    const lichKhoiHanhId = data.lichKhoiHanhId;
    if (!lichKhoiHanhId) {
        alert('Vui lòng cập nhật lịch khởi hành trước khi check-in.');
        return;
    }
    const list = data.guestListFromFile || [];
    const guest = list[idx];
    if (!guest || !guest.ho_ten) {
        alert('Không tìm thấy thông tin khách.');
        return;
    }
    const formData = new FormData();
    formData.append('booking_id', data.bookingId);
    formData.append('lich_khoi_hanh_id', lichKhoiHanhId);
    formData.append('booking_khach_id', ''); // chưa có, server sẽ tạo mới
    formData.append('trang_thai', isCheckIn ? 'da_den' : 'vang_mat');
    formData.append('ho_ten', guest.ho_ten);
    formData.append('gioi_tinh', guest.gioi_tinh || '');
    formData.append('nam_sinh', guest.nam_sinh || '');
    formData.append('so_giay_to', guest.so_giay_to || '');
    formData.append('yeu_cau_ca_nhan', guest.yeu_cau_ca_nhan || '');

    fetch(data.baseUrl + 'guide/check-in', {
        method: 'POST',
        body: formData
    }).then(() => {
        location.reload();
    });
}

function showUpdateYeuCau(khachId, yeuCau) {
    document.getElementById('modalKhachId').value = khachId;
    document.getElementById('modalYeuCau').value = yeuCau;
    const modal = new bootstrap.Modal(document.getElementById('updateYeuCauModal'));
    modal.show();
}

function showUpdateYeuCauDoan() {
    const data = window.guideBookingData || {};
    document.getElementById('modalYeuCauDoan').value = data.yeuCauDoan || '';
    const modal = new bootstrap.Modal(document.getElementById('updateYeuCauDoanModal'));
    modal.show();
}

window.toggleCheckIn = toggleCheckIn;
window.toggleCheckInFromFile = toggleCheckInFromFile;
window.showUpdateYeuCau = showUpdateYeuCau;
window.showUpdateYeuCauDoan = showUpdateYeuCauDoan;

