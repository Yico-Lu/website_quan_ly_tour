<?php

// Nạp cấu hình chung của ứng dụng
$config = require __DIR__ . '/config/config.php';

// Nạp các file chứa hàm trợ giúp
require_once __DIR__ . '/src/helpers/helpers.php'; // Helper chứa các hàm trợ giúp (hàm xử lý view, block, asset, session, ...)
require_once __DIR__ . '/src/helpers/database.php'; // Helper kết nối database(kết nối với cơ sở dữ liệu)

// Nạp các file chứa model
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Tour.php';
require_once __DIR__ . '/src/models/Booking.php';
require_once __DIR__ . '/src/models/DanhMucTour.php';

// Nạp các file chứa controller
require_once __DIR__ . '/src/controllers/HomeController.php';
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/TourController.php';
require_once __DIR__ . '/src/controllers/AccountController.php';
require_once __DIR__ . '/src/controllers/BookingController.php';
require_once __DIR__ . '/src/controllers/DanhMucTourController.php';
require_once __DIR__ . '/src/controllers/ReportController.php';

// Khởi tạo các controller
$homeController = new HomeController();
$authController = new AuthController();
$tourController = new TourController();
$accountController = new AccountController();
$bookingController = new BookingController();
$danhMucController = new DanhMucTourController();
$reportController = new ReportController();

// Xác định route dựa trên tham số act (mặc định là trang chủ '/')
$act = $_GET['act'] ?? '/';

// Xử lý route có tham số
if (strpos($act, 'tours/edit/') === 0) {
    $id = str_replace('tours/edit/', '', $act);
    $tourController->edit($id);
    exit;
}

if (strpos($act, 'accounts/edit/') === 0) {
    $id = str_replace('accounts/edit/', '', $act);
    $accountController->edit($id);
    exit;
}

if (strpos($act, 'accounts/show/') === 0) {
    $id = str_replace('accounts/show/', '', $act);
    $accountController->show($id);
    exit;
}

if (strpos($act, 'categories/edit/') === 0) {
    $id = str_replace('categories/edit/', '', $act);
    $danhMucController->edit($id);
    exit;
}

if (strpos($act, 'categories/show/') === 0) {
    $id = str_replace('categories/show/', '', $act);
    $danhMucController->show($id);
    exit;
}

if (strpos($act, 'bookings/edit/') === 0) {
    $id = str_replace('bookings/edit/', '', $act);
    $bookingController->edit($id);
    exit;
}

if (strpos($act, 'bookings/show/') === 0) {
    $id = str_replace('bookings/show/', '', $act);
    $bookingController->show($id);
    exit;
}

// Match đảm bảo chỉ một action tương ứng được gọi
match ($act) {
    // Trang welcome (cho người chưa đăng nhập) - mặc định khi truy cập '/'
    '/', 'welcome' => $homeController->welcome(),

    // Trang home (cho người đã đăng nhập)
    'home' => $homeController->home(),

    // Đường dẫn đăng nhập, đăng xuất
    'login' => $authController->login(),
    'check-login' => $authController->checkLogin(),
    'logout' => $authController->logout(),

    // Đường dẫn quản lý tour
    'tours' => $tourController->index(),
    'tours/create' => $tourController->create(),
    'tours/store' => $tourController->store(),
    'tours/update' => $tourController->update(),
    'tours/delete' => $tourController->delete(),

    // Đường dẫn quản lý tài khoản
    'accounts' => $accountController->index(),
    'accounts/create' => $accountController->create(),
    'accounts/store' => $accountController->store(),
    'accounts/update' => $accountController->update(),
    'accounts/delete' => $accountController->delete(),

    // Đường dẫn quản lý booking
    'bookings' => $bookingController->index(),
    'bookings/create' => $bookingController->create(),
    'bookings/store' => $bookingController->store(),
    'bookings/update' => $bookingController->update(),
    'bookings/delete' => $bookingController->delete(),

    // Đường dẫn quản lý danh mục tour
    'categories' => $danhMucController->index(),
    'categories/create' => $danhMucController->create(),
    'categories/store' => $danhMucController->store(),
    'categories/update' => $danhMucController->update(),
    'categories/delete' => $danhMucController->delete(),

    // Đường dẫn báo cáo thống kê
    'reports' => $reportController->index(),
    'reports/revenue' => $reportController->revenue(),
    'reports/revenue-by-tour' => $reportController->revenueByTour(),
    'reports/revenue-by-category' => $reportController->revenueByCategory(),
    'reports/revenue-table' => $reportController->revenueTable(),

    // Xử lý route có tham số
    default => match($route) {
        'tours' => match($param) {
        'edit' => $tourController->edit($actParts[2] ?? null),
        default => $homeController->notFound()
        },
        'tours/update' => $tourController->update(),
        default => $homeController->notFound()
    }

    // Đường dẫn không tồn tại
    // default => $homeController->notFound(),
};