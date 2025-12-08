<?php

// Hàm xác định đường dẫn tuyệt đối tới file view tương ứng
function view_path(string $view): string
{
    $normalized = str_replace('.', DIRECTORY_SEPARATOR, $view);
    return BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $normalized . '.php';
}

// Hàm xác định đường dẫn tuyệt đối tới file block tương ứng(thành phần layouts)
function block_path(string $block): string
{
    return BASE_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'blocks' . DIRECTORY_SEPARATOR . $block . '.php';
}

// Hàm view: nạp dữ liệu và hiển thị giao diện
function view(string $view, array $data = []): void
{
    $file = view_path($view);

    if (!file_exists($file)) {
        throw new RuntimeException("View '{$view}' not found at {$file}");
    }

    extract($data, EXTR_OVERWRITE); // biến hóa mảng $data thành biến riêng lẻ
    include $file;
}

// Hàm include block: nạp một block từ thư mục blocks(thành phần layouts)
function block(string $block, array $data = []): void
{
    $file = block_path($block);

    if (!file_exists($file)) {
        throw new RuntimeException("Block '{$block}' not found at {$file}");
    }

    extract($data, EXTR_OVERWRITE); // biến hóa mảng $data thành biến riêng lẻ
    include $file;
}

// Tạo đường dẫn tới asset (css/js/images) trong thư mục public(tài nguyên)
function asset(string $path): string
{
    $trimmed = ltrim($path, '/');
    return rtrim(BASE_URL, '/') . '/public/' . $trimmed;
}

// Khởi động session nếu chưa khởi động(session là một cơ chế để lưu trữ dữ liệu trên server)
function startSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Lưu thông tin user vào session sau khi đăng nhập thành công
// @param User $user Đối tượng User cần lưu vào session
function loginUser($user)
{
    startSession();
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_role'] = $user->role;
}

// Đăng xuất: xóa toàn bộ thông tin user khỏi session
function logoutUser()
{
    startSession();
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_role']);
    session_destroy();
}

// Kiểm tra xem user đã đăng nhập chưa
// @return bool true nếu đã đăng nhập, false nếu chưa
function isLoggedIn()
{
    startSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Lấy thông tin user hiện tại từ session
// @return User|null Trả về đối tượng User nếu đã đăng nhập, null nếu chưa
function getCurrentUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    startSession();
    return new User([
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role'],
    ]);
}

// Kiểm tra xem user hiện tại có phải là admin không
// @return bool true nếu là admin, false nếu không
function isAdmin()
{
    $user = getCurrentUser();
    return $user && $user->isAdmin();
}

// Kiểm tra xem user hiện tại có phải là hướng dẫn viên không
// @return bool true nếu là hướng dẫn viên, false nếu không
function isGuide()
{
    $user = getCurrentUser();
    return $user && $user->isGuide();
}

// Yêu cầu đăng nhập: nếu chưa đăng nhập thì chuyển hướng về trang login
// @param string $redirectUrl URL chuyển hướng sau khi đăng nhập (mặc định là trang hiện tại)
function requireLogin($redirectUrl = null)
{
    if (!isLoggedIn()) {
        $redirect = $redirectUrl ?: $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . '?act=login&redirect=' . urlencode($redirect));
        exit;
    }
}

// Yêu cầu quyền admin: nếu không phải admin thì chuyển hướng về trang chủ
function requireAdmin()
{
    requireLogin();
    
    if (!isAdmin()) {
        header('Location: ' . BASE_URL);
        exit;
    }
}

// Yêu cầu quyền hướng dẫn viên hoặc admin
function requireGuideOrAdmin()
{
    requireLogin();

    if (!isGuide() && !isAdmin()) {
        header('Location: ' . BASE_URL);
        exit;
    }
}

// Thiết lập thông báo flash message
function setFlashMessage($type, $message)
{
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message,
        'timestamp' => time()
    ];
}

// Lấy và xóa thông báo flash message
function getFlashMessages()
{
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

// Upload một ảnh đơn
function uploadImage($file, $prefix = 'file', $uploadDir = 'uploads/general/')
{
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        return null; // Invalid file type
    }

    // Validate file size (5MB max)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        return null; // File too large
    }

    // Get file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Validate extension as additional security
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $allowedExtensions)) {
        return null; // Invalid extension
    }

    // Generate unique filename
    $fileName = $prefix . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;

    // Full path to save file
    $fullUploadDir = __DIR__ . '/../../public/' . $uploadDir;
    $filePath = $fullUploadDir . $fileName;

    // Create directory if it doesn't exist
    if (!is_dir($fullUploadDir)) {
        mkdir($fullUploadDir, 0755, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return '/' . $uploadDir . $fileName; // Return web path
    }

    return null;
}

// Upload nhiều ảnh
function uploadMultipleImages($files, $prefix = 'file', $uploadDir = 'uploads/general/')
{
    $uploadedPaths = [];

    if (!$files || !is_array($files['name'])) {
        return $uploadedPaths;
    }

    // Process each file
    foreach ($files['name'] as $key => $name) {
        if ($files['error'][$key] !== UPLOAD_ERR_NO_FILE) {
            $file = [
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            ];

            $path = uploadImage($file, $prefix, $uploadDir);
            if ($path) {
                $uploadedPaths[] = $path;
            }
        }
    }

    return $uploadedPaths;
}

// Đọc file Excel/CSV và trả về mảng dữ liệu
function readExcelFile($filePath, $startRow = 2)
{
    $data = [];
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    
    if ($extension === 'csv') {
        // Đọc file CSV
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $rowNum = 0;
            // Đọc với encoding UTF-8
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $rowNum++;
                if ($rowNum < $startRow) continue; // Bỏ qua header
                
                // Chuyển đổi encoding nếu cần
                $row = array_map(function($cell) {
                    // Thử detect và convert encoding
                    if (!mb_check_encoding($cell, 'UTF-8')) {
                        $cell = mb_convert_encoding($cell, 'UTF-8', 'Windows-1252');
                    }
                    return trim($cell);
                }, $row);
                
                if (empty(array_filter($row))) continue; // Bỏ qua dòng trống
                $data[] = $row;
            }
            fclose($handle);
        }
    } elseif (in_array($extension, ['xls', 'xlsx'])) {
        // Đọc file Excel - sử dụng PhpSpreadsheet nếu có
        if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                
                for ($row = $startRow; $row <= $highestRow; $row++) {
                    $rowData = [];
                    $cellValue = $worksheet->getCell('A' . $row)->getCalculatedValue();
                    if (empty($cellValue)) continue; // Bỏ qua dòng trống
                    
                    // Đọc các cột: A=Họ tên, B=Giới tính, C=Năm sinh, D=Số giấy tờ, E=Yêu cầu cá nhân
                    $rowData[] = trim($worksheet->getCell('A' . $row)->getCalculatedValue() ?? ''); // Họ tên
                    $rowData[] = trim($worksheet->getCell('B' . $row)->getCalculatedValue() ?? ''); // Giới tính
                    $rowData[] = trim($worksheet->getCell('C' . $row)->getCalculatedValue() ?? ''); // Năm sinh
                    $rowData[] = trim($worksheet->getCell('D' . $row)->getCalculatedValue() ?? ''); // Số giấy tờ
                    $rowData[] = trim($worksheet->getCell('E' . $row)->getCalculatedValue() ?? ''); // Yêu cầu cá nhân
                    
                    if (!empty($rowData[0])) { // Có ít nhất họ tên
                        $data[] = $rowData;
                    }
                }
            } catch (Exception $e) {
                error_log('Error reading Excel file: ' . $e->getMessage());
                return null;
            }
        } elseif ($extension === 'xlsx' && class_exists('ZipArchive')) {
            // Fallback: Đọc file XLSX bằng cách giải nén và parse XML (không cần PhpSpreadsheet)
            try {
                $data = readXlsxWithoutPhpSpreadsheet($filePath, $startRow);
                if ($data !== null) {
                    return $data;
                }
            } catch (Exception $e) {
                error_log('Error reading XLSX file (fallback method): ' . $e->getMessage());
            }
            // Nếu fallback không thành công, hướng dẫn user
            throw new Exception('Để import file Excel, vui lòng cài đặt thư viện PhpSpreadsheet (chạy: composer require phpoffice/phpspreadsheet) hoặc export file sang định dạng CSV');
        } else {
            // Nếu không có PhpSpreadsheet và không phải XLSX hoặc không có ZipArchive
            throw new Exception('Để import file Excel, vui lòng cài đặt thư viện PhpSpreadsheet (chạy: composer require phpoffice/phpspreadsheet) hoặc export file sang định dạng CSV');
        }
    }
    
    return $data;
}

// Đọc file XLSX không cần PhpSpreadsheet (giải nén ZIP và parse XML)
function readXlsxWithoutPhpSpreadsheet($filePath, $startRow = 2)
{
    if (!class_exists('ZipArchive')) {
        return null;
    }
    
    $zip = new ZipArchive();
    if ($zip->open($filePath) !== TRUE) {
        return null;
    }
    
    // Đọc file sharedStrings.xml để lấy danh sách chuỗi được chia sẻ
    $sharedStrings = [];
    if (($sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml')) !== false) {
        $xml = simplexml_load_string($sharedStringsXml);
        if ($xml && isset($xml->si)) {
            foreach ($xml->si as $si) {
                $text = '';
                if (isset($si->t)) {
                    $text = (string)$si->t;
                }
                $sharedStrings[] = $text;
            }
        }
    }
    
    // Đọc file xl/worksheets/sheet1.xml (sheet đầu tiên)
    $sheetData = null;
    $sheetFiles = ['xl/worksheets/sheet1.xml', 'xl/worksheets/sheet.xml'];
    foreach ($sheetFiles as $sheetFile) {
        if (($sheetXml = $zip->getFromName($sheetFile)) !== false) {
            $sheetData = $sheetXml;
            break;
        }
    }
    
    $zip->close();
    
    if (!$sheetData) {
        return null;
    }
    
    // Parse XML của sheet
    $xml = simplexml_load_string($sheetData);
    if (!$xml || !isset($xml->sheetData->row)) {
        return null;
    }
    
    $data = [];
    $rowNum = 0;
    
    foreach ($xml->sheetData->row as $row) {
        $rowNum++;
        if ($rowNum < $startRow) continue; // Bỏ qua header
        
        $rowData = ['', '', '', '', '']; // 5 cột: Họ tên, Giới tính, Năm sinh, Số giấy tờ, Yêu cầu cá nhân
        
        if (isset($row->c)) {
            foreach ($row->c as $cell) {
                $cellRef = (string)$cell['r']; // Ví dụ: A1, B1, C1...
                $col = preg_replace('/[0-9]+/', '', $cellRef); // Lấy chữ cái cột
                $colIndex = ord($col) - ord('A'); // Chuyển thành index (A=0, B=1, ...)
                
                if ($colIndex < 0 || $colIndex >= 5) continue; // Chỉ đọc 5 cột đầu
                
                $value = '';
                if (isset($cell->v)) {
                    $cellValue = (string)$cell->v;
                    
                    // Nếu có thuộc tính t="s" thì giá trị là index trong sharedStrings
                    if (isset($cell['t']) && (string)$cell['t'] === 's') {
                        $stringIndex = (int)$cellValue;
                        if (isset($sharedStrings[$stringIndex])) {
                            $value = $sharedStrings[$stringIndex];
                        }
                    } else {
                        $value = $cellValue;
                    }
                }
                
                $rowData[$colIndex] = trim($value);
            }
        }
        
        // Chỉ thêm dòng nếu có ít nhất họ tên (cột đầu tiên)
        if (!empty($rowData[0])) {
            $data[] = $rowData;
        }
    }
    
    return $data;
}