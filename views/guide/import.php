<?php
// Bắt đầu capture nội dung
ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-upload mr-2"></i>
                        Import danh sách khách hàng - <?= htmlspecialchars($tour->ten_tour) ?>
                    </h3>
                    <div class="card-tools">
                        <a href="<?= BASE_URL ?>guide/customers/<?= $tour->id ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Hướng dẫn -->
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Hướng dẫn import</h5>
                        <p>Upload file CSV chứa danh sách khách hàng đã đặt tour. File CSV phải có cấu trúc như sau:</p>
                        <ol>
                            <li><strong>Dòng đầu tiên</strong>: Là tiêu đề cột</li>
                            <li><strong>Các cột theo thứ tự</strong>:
                                <code>ho_ten, email, so_dien_thoai, so_luong_nguoi_lon, so_luong_tre_em, tong_tien, ghi_chu</code>
                            </li>
                        </ol>
                        <p><strong>Ví dụ file CSV:</strong></p>
                        <pre class="bg-light p-2 rounded">
ho_ten,email,so_dien_thoai,so_luong_nguoi_lon,so_luong_tre_em,tong_tien,ghi_chu
Nguyễn Văn A,nguyenvana@email.com,0912345678,2,1,5000000,Thích ăn hải sản
Trần Thị B,tranb@email.com,0987654321,1,0,2500000,Yêu cầu phòng riêng</pre>
                    </div>

                    <!-- Form upload -->
                    <div class="row">
                        <div class="col-md-6">
                            <form action="<?= BASE_URL ?>guide/process-import" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="tour_id" value="<?= $tour->id ?>">

                                <div class="form-group">
                                    <label for="customer_file">Chọn file CSV</label>
                                    <input type="file"
                                           class="form-control"
                                           id="customer_file"
                                           name="customer_file"
                                           accept=".csv"
                                           required>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Import khách hàng
                                    </button>
                                    <a href="<?= BASE_URL ?>guide/customers/<?= $tour->id ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Hủy
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Hàm tạo và download file CSV mẫu
function downloadTemplate() {
    const csvContent = "ho_ten,email,so_dien_thoai,so_luong_nguoi_lon,so_luong_tre_em,tong_tien,ghi_chu\n" +
                      "Nguyễn Văn A,nguyenvana@email.com,0912345678,2,1,5000000,Thích ăn hải sản\n" +
                      "Trần Thị B,tranb@email.com,0987654321,1,0,2500000,Yêu cầu phòng riêng";

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");

    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", "mau_danh_sach_khach_hang.csv");
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}
</script>

<?php
// Lấy nội dung đã capture
$content = ob_get_clean();

// Truyền vào layout
view('layouts.GuideLayout', [
    'title' => $title ?? 'Import khách hàng',
    'pageTitle' => $pageTitle ?? 'Import khách hàng',
    'content' => $content,
    'breadcrumb' => $breadcrumb ?? [],
    'currentUser' => $currentUser ?? null,
]);
?>