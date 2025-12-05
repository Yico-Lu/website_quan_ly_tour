<?php
// File test để kiểm tra upload ảnh có hoạt động không
echo "<h1>Test Upload Ảnh</h1>";

// Kiểm tra thư mục uploads
$uploadDir = __DIR__ . '/public/uploads/tours/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
    echo "<p style='color: green;'>✅ Đã tạo thư mục uploads/tours/</p>";
} else {
    echo "<p style='color: green;'>✅ Thư mục uploads/tours/ đã tồn tại</p>";
}

// Kiểm tra quyền ghi
if (is_writable($uploadDir)) {
    echo "<p style='color: green;'>✅ Thư mục có quyền ghi</p>";
} else {
    echo "<p style='color: red;'>❌ Thư mục không có quyền ghi</p>";
}

// Hiển thị các file đã upload
$files = glob($uploadDir . '*');
echo "<h2>Files đã upload:</h2>";
if (empty($files)) {
    echo "<p>Chưa có file nào</p>";
} else {
    echo "<ul>";
    foreach ($files as $file) {
        $fileName = basename($file);
        $fileSize = filesize($file);
        $fileSizeMB = round($fileSize / 1024 / 1024, 2);
        echo "<li>{$fileName} ({$fileSizeMB} MB)</li>";
    }
    echo "</ul>";
}

echo "<hr>";
echo "<p><a href='" . dirname($_SERVER['PHP_SELF']) . "/tours/create'>← Quay lại trang tạo tour</a></p>";
?>


