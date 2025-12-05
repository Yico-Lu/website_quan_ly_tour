<?php

// Nạp cấu hình chung của ứng dụng
$config = require __DIR__ . '/config/config.php';

// Nạp các file chứa hàm trợ giúp
require_once __DIR__ . '/src/helpers/helpers.php'; // Helper chứa các hàm trợ giúp (hàm xử lý view, block, asset, session, upload, ...)
require_once __DIR__ . '/src/helpers/database.php'; // Helper kết nối database(kết nối với cơ sở dữ liệu)

// Nạp các file chứa model
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Tour.php';

// Nạp các file chứa controller
require_once __DIR__ . '/src/controllers/HomeController.php';
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/TourController.php';
require_once __DIR__ . '/src/controllers/GuideController.php';

// Khởi tạo các controller
$homeController = new HomeController();
$authController = new AuthController();
$tourController = new TourController();
$guideController = new GuideController();

// Xác định route dựa trên tham số act (mặc định là trang chủ '/')
$act = $_GET['act'] ?? '/';

// Xử lý route có tham số trước
if (strpos($act, 'tours/edit/') === 0) {
    $id = str_replace('tours/edit/', '', $act);
    $tourController->edit($id);
    exit;
}

if (strpos($act, 'tours/show/') === 0) {
    $id = str_replace('tours/show/', '', $act);
    $tourController->show($id);
    exit;
}

if (strpos($act, 'guide/show/') === 0) {
    $id = str_replace('guide/show/', '', $act);
    $guideController->show($id);
    exit;
}

if (strpos($act, 'guide/customers/') === 0) {
    $id = str_replace('guide/customers/', '', $act);
    $guideController->customers($id);
    exit;
}

if (strpos($act, 'guide/import/') === 0) {
    $id = str_replace('guide/import/', '', $act);
    $guideController->importCustomers($id);
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

    // Đường dẫn cho hướng dẫn viên
    'guide' => $guideController->index(),
    'guide/dashboard' => $guideController->dashboard(),
    'guide/checkin' => $guideController->checkin(),
    'guide/process-import' => $guideController->processImport(),

    // Đường dẫn không tồn tại
    default => $homeController->notFound(),
};
