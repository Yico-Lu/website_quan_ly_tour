// Image Preview Manager
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('anh_chi_tiet');
    if (!input) return;

    // Tạo container preview
    const container = document.createElement('div');
    container.id = 'preview';
    container.className = 'mt-3';
    input.closest('.mb-3').appendChild(container);

    // Lắng nghe sự kiện chọn file
    input.addEventListener('change', updatePreview);
});

function updatePreview(event) {
    const files = event.target.files;
    const container = document.getElementById('preview');
    container.innerHTML = '';

    if (files.length === 0) return;

    // Tạo grid
    const grid = document.createElement('div');
    grid.className = 'row g-2 mt-2';

    Array.from(files).forEach((file, index) => {
        const col = document.createElement('div');
        col.className = 'col-md-3 col-sm-6';

        const card = document.createElement('div');
        card.className = 'card position-relative';

        // Nút xóa
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 m-1';
        btn.innerHTML = '×';
        btn.onclick = () => removeImage(index);

        // Ảnh
        const img = document.createElement('img');
        img.className = 'card-img-top';
        img.style.height = '100px';
        img.style.objectFit = 'cover';

        // Tên file
        const body = document.createElement('div');
        body.className = 'card-body p-2';
        body.innerHTML = `<small class="text-muted">${file.name}</small>`;

        card.append(btn, img, body);
        col.appendChild(card);
        grid.appendChild(col);

        // Hiển thị ảnh
        const reader = new FileReader();
        reader.onload = e => img.src = e.target.result;
        reader.readAsDataURL(file);
    });

    container.appendChild(grid);
}

function removeImage(index) {
    const input = document.getElementById('anh_chi_tiet');
    const dt = new DataTransfer();

    Array.from(input.files).forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });

    input.files = dt.files;
    updatePreview({ target: input });
}
