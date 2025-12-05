# Helper Functions Documentation

## Tổng quan

## 1. Flash Message Helpers

### `setFlashMessage(string $type, string $message): void`
Thiết lập thông báo flash message.

**Parameters:**
- `$type`: Loại thông báo ('success', 'error', 'warning', 'info')
- `$message`: Nội dung thông báo

**Example:**
```php
setFlashMessage('success', 'Tour đã được tạo thành công');
setFlashMessage('error', 'Có lỗi xảy ra');
```

### `getFlashMessages(): array`
Lấy và xóa thông báo flash message.

**Return:** Array các flash messages

**Example:**
```php
$messages = getFlashMessages();
foreach ($messages as $message) {
    echo "<div class='alert alert-{$message['type']}'>{$message['message']}</div>";
}
```

## 2. Upload Helpers

### `uploadImage(array $file, string $prefix = 'file', string $uploadDir = 'uploads/general/'): string|null`
Upload một file ảnh đơn lẻ.

**Parameters:**
- `$file`: Array $_FILES element
- `$prefix`: Tiền tố cho tên file (default: 'file')
- `$uploadDir`: Thư mục upload (default: 'uploads/general/')

**Return:** String - Web path hoặc null nếu lỗi

**Example:**
```php
$imagePath = uploadImage($_FILES['avatar'], 'user_avatar', 'uploads/avatars/');
```

### `uploadMultipleImages(array $files, string $prefix = 'file', string $uploadDir = 'uploads/general/'): array`
Upload nhiều file ảnh cùng lúc.

**Parameters:**
- `$files`: Array $_FILES cho multiple files
- `$prefix`: Tiền tố cho tên file
- `$uploadDir`: Thư mục upload

**Return:** Array - Mảng các web paths

**Example:**
```php
$imagePaths = uploadMultipleImages($_FILES['gallery'], 'gallery', 'uploads/gallery/');
```

## Cách sử dụng trong Controller

## Cách sử dụng trong Controller

```php
<?php
class TourController
{
    public function store()
    {
        // Kiểm tra đăng nhập và quyền
        requireGuideOrAdmin();

        // Upload ảnh tour
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $imagePath = uploadImage($_FILES['image'], 'tour', 'uploads/tours/');
        }

        // Xử lý dữ liệu...

        // Set success message
        setFlashMessage('success', 'Tour đã được tạo thành công');
        header('Location: ' . BASE_URL . 'tours');
    }

    public function update($id)
    {
        // Kiểm tra quyền admin
        requireAdmin();

        // Upload nhiều ảnh gallery
        $galleryPaths = [];
        if (isset($_FILES['gallery'])) {
            $galleryPaths = uploadMultipleImages($_FILES['gallery'], 'gallery', 'uploads/gallery/');
        }

        // Xử lý cập nhật...

        setFlashMessage('success', 'Tour đã được cập nhật');
        header('Location: ' . BASE_URL . 'tours');
    }
}
```

## Cách sử dụng trong View

```php
<?php
// Trong file layout hoặc header
$flashMessages = getFlashMessages();
foreach ($flashMessages as $message):
?>
<div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
    <?= $message['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endforeach; ?>

<?php
// Hiển thị menu dựa trên quyền
if (isLoggedIn()):
    $user = getCurrentUser();
?>
<div class="user-info">
    Xin chào, <?= htmlspecialchars($user->name) ?>

    <?php if (isAdmin()): ?>
        <a href="<?= BASE_URL ?>admin" class="btn btn-primary">Quản trị</a>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>logout" class="btn btn-outline-secondary">Đăng xuất</a>
</div>
<?php else: ?>
<a href="<?= BASE_URL ?>login" class="btn btn-primary">Đăng nhập</a>
<?php endif; ?>

<?php
// Hiển thị nội dung chỉ dành cho admin
if (isAdmin()):
?>
<div class="admin-panel">
    <!-- Admin controls -->
</div>
<?php endif; ?>
```

## Lợi ích của Helper Functions

1. **Đơn giản hóa:** Tập trung tất cả helper functions vào một file duy nhất
2. **Dễ tiếp cận:** Người mới có thể dễ dàng tìm và sử dụng các hàm
3. **Tái sử dụng:** Một lần viết, dùng nhiều nơi trong ứng dụng
4. **DRY Principle:** Tránh duplicate code
5. **Maintainability:** Dễ sửa đổi và bảo trì
6. **Organization:** Code được tổ chức rõ ràng và logic

## Quy tắc đặt tên

- **Functions:** camelCase (view, block, asset, setFlashMessage)
- **Parameters:** camelCase ($filePath, $uploadDir)
- **Constants:** UPPER_CASE (BASE_URL, BASE_PATH)
- **Files:** snake_case.php (helpers.php, database.php)

Tất cả helper functions đều an toàn và có validation đầy đủ cho hệ thống quản lý tour! 🚀


