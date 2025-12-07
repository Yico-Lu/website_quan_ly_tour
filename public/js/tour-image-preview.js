// Quản lý preview ảnh khi chọn file
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('anh_chi_tiet');
    if (!input) return;

    // Lắng nghe sự kiện khi người dùng chọn file
    input.addEventListener('change', function(event) {
        updatePreview(event);
    });
});

// Hàm hiển thị preview các ảnh đã chọn
function updatePreview(event) {
    const files = event.target.files; // Lấy danh sách file đã chọn
    const container = document.getElementById('preview');
    
    // Xóa nội dung cũ
    container.innerHTML = '';

    // Nếu không có file nào thì dừng
    if (files.length === 0) return;

    // Tạo grid để hiển thị ảnh
    const grid = document.createElement('div');
    grid.className = 'row g-2 mt-2';

    // Duyệt qua từng file đã chọn
    for (let index = 0; index < files.length; index++) {
        const file = files[index];

        // Tạo cột chứa ảnh
        const col = document.createElement('div');
        col.className = 'col-md-3 col-sm-6';

        // Tạo card chứa ảnh
        const card = document.createElement('div');
        card.className = 'card position-relative';

        // Tạo nút xóa ảnh
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 m-1';
        btn.innerHTML = '×';
        btn.onclick = function() {
            removeImage(index);
        };

        // Tạo thẻ img để hiển thị ảnh
        const img = document.createElement('img');
        img.className = 'card-img-top';
        img.style.height = '100px';
        img.style.objectFit = 'cover';

        // Tạo phần hiển thị tên file
        const body = document.createElement('div');
        body.className = 'card-body p-2';
        body.innerHTML = '<small class="text-muted">' + file.name + '</small>';

        // Gắn các phần tử vào card
        card.appendChild(btn);
        card.appendChild(img);
        card.appendChild(body);
        col.appendChild(card);
        grid.appendChild(col);

        // Đọc file và hiển thị ảnh
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Thêm grid vào container
    container.appendChild(grid);
}

// Hàm xóa ảnh khỏi danh sách đã chọn
function removeImage(index) {
    const input = document.getElementById('anh_chi_tiet');
    
    // Tạo DataTransfer để quản lý danh sách file
    const dt = new DataTransfer();
    
    // Thêm lại tất cả file trừ file bị xóa
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) {
            dt.items.add(input.files[i]);
        }
    }
    
    // Cập nhật danh sách file
    input.files = dt.files;
    
    // Cập nhật lại preview
    updatePreview({ target: input });
}
