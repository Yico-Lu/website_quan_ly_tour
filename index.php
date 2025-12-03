<?php

// Nạp cấu hình chung của ứng dụng
$config = require __DIR__ . '/config/config.php';

// Nạp các file chứa hàm trợ giúp
require_once __DIR__ . '/src/helpers/helpers.php'; // Helper chứa các hàm trợ giúp (hàm xử lý view, block, asset, session, ...)
require_once __DIR__ . '/src/helpers/database.php'; // Helper kết nối database(kết nối với cơ sở dữ liệu)

// Nạp các file chứa model
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Account.php';

// Nạp các file chứa controller
require_once __DIR__ . '/src/controllers/HomeController.php';
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/admin/AccountController.php';

// Khởi tạo các controller
$homeController = new HomeController();
$authController = new AuthController();
$accountController = new AccountController();

// Xác định route dựa trên tham số act (mặc định là trang chủ '/')
$act = $_GET['act'] ?? '/';

// Xử lý routing đơn giản
if ($act === '/' || $act === 'welcome') {
    $homeController->welcome();
} elseif ($act === 'home') {
    $homeController->home();
} elseif ($act === 'login') {
    $authController->login();
} elseif ($act === 'check-login') {
    $authController->checkLogin();
} elseif ($act === 'logout') {
    $authController->logout();
} elseif ($act === 'accounts') {
    $accountController->index();
} elseif ($act === 'accounts/admins') {
    $accountController->admins();
} elseif ($act === 'accounts/create') {
    $accountController->create();
} elseif ($act === 'accounts/store') {
    $accountController->store();
} elseif (preg_match('/^accounts\/show\/(\d+)$/', $act, $matches)) {
    $accountController->show($matches[1]);
} elseif (preg_match('/^accounts\/edit\/(\d+)$/', $act, $matches)) {
    $accountController->edit($matches[1]);
} elseif (preg_match('/^accounts\/update\/(\d+)$/', $act, $matches)) {
    $accountController->update($matches[1]);
} elseif (preg_match('/^accounts\/delete\/(\d+)$/', $act, $matches)) {
    $accountController->delete($matches[1]);
} elseif (preg_match('/^accounts\/change-password\/(\d+)$/', $act, $matches)) {
    $accountController->changePassword($matches[1]);
} else {
    $homeController->notFound();
}
