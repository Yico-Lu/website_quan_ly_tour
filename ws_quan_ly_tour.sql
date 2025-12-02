-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 26, 2025 at 11:16 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ws_quan_ly_tour`
--

-- --------------------------------------------------------

--
-- Table structure for table `bao_cao_tong_hop_tour`
--

CREATE TABLE `bao_cao_tong_hop_tour` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `ky_bao_cao` varchar(50) DEFAULT NULL,
  `nam` int DEFAULT NULL,
  `doanh_thu` decimal(12,0) DEFAULT NULL,
  `chi_phi` decimal(12,0) DEFAULT NULL,
  `loi_nhuan` decimal(12,0) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bao_cao_tong_hop_tour`
--

INSERT INTO `bao_cao_tong_hop_tour` (`id`, `tour_id`, `ky_bao_cao`, `nam`, `doanh_thu`, `chi_phi`, `loi_nhuan`, `ngay_tao`) VALUES
(1, 1, 'thang', 2025, 7000000, 3000000, 4000000, '2025-11-26 18:14:18'),
(2, 2, 'thang', 2025, 67500000, 25000000, 42500000, '2025-11-26 18:14:18'),
(3, 3, 'thang', 2025, 12000000, 5000000, 7000000, '2025-11-26 18:14:18'),
(4, 4, 'thang', 2025, 10000000, 4000000, 6000000, '2025-11-26 18:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int NOT NULL,
  `tai_khoan_id` int DEFAULT NULL,
  `assigned_hdv_id` int DEFAULT NULL,
  `tour_id` int DEFAULT NULL,
  `loai_khach` varchar(50) DEFAULT NULL,
  `ten_nguoi_dat` varchar(100) DEFAULT NULL,
  `so_luong` int DEFAULT NULL,
  `thoi_gian_tour` datetime DEFAULT NULL,
  `lien_he` varchar(150) DEFAULT NULL,
  `yeu_cau_dac_biet` text,
  `trang_thai` varchar(50) DEFAULT NULL,
  `ghi_chu` text,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_cap_nhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `tai_khoan_id`, `assigned_hdv_id`, `tour_id`, `loai_khach`, `ten_nguoi_dat`, `so_luong`, `thoi_gian_tour`, `lien_he`, `yeu_cau_dac_biet`, `trang_thai`, `ghi_chu`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 1, 2, 1, 'le', 'Nguyễn Văn A', 2, '2025-12-01 08:00:00', '0912345678', 'Ăn chay', 'cho_xac_nhan', '', '2025-11-26 18:08:14', '2025-11-26 18:08:14'),
(2, 1, 3, 2, 'doan', 'Công ty ABC', 15, '2025-12-05 07:30:00', '0987654321', 'Cần xe lớn', 'da_coc', '', '2025-11-26 18:08:14', '2025-11-26 18:08:14'),
(3, 1, 1, 3, 'le', 'Trần Thị B', 1, '2025-12-10 09:00:00', '0911223344', '', 'hoan_tat', '', '2025-11-26 18:08:14', '2025-11-26 18:08:14'),
(4, 1, 2, 1, 'doan', 'Công ty XYZ', 10, '2025-12-15 08:30:00', '0998877665', 'Tour riêng', 'cho_xac_nhan', '', '2025-11-26 18:08:14', '2025-11-26 18:08:14');

-- --------------------------------------------------------

--
-- Table structure for table `booking_dich_vu`
--

CREATE TABLE `booking_dich_vu` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `ten_dich_vu` varchar(100) DEFAULT NULL,
  `chi_tiet` text,
  `ngay_tao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_dich_vu`
--

INSERT INTO `booking_dich_vu` (`id`, `booking_id`, `ten_dich_vu`, `chi_tiet`, `ngay_tao`) VALUES
(1, 1, 'Xe đưa đón', 'Xe 4 chỗ đưa đón khách từ Hà Nội đi Hạ Long', '2025-11-26 18:11:52'),
(2, 2, 'Ăn trưa', 'Bữa trưa cho 15 khách tại Hội An', '2025-11-26 18:11:52'),
(3, 3, 'Visa', 'Hỗ trợ thủ tục visa Thái Lan', '2025-11-26 18:11:52'),
(4, 4, 'Ăn tối', 'Bữa tối cho đoàn theo yêu cầu riêng', '2025-11-26 18:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `booking_hdv`
--

CREATE TABLE `booking_hdv` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `hdv_id` int DEFAULT NULL,
  `vai_tro` varchar(50) DEFAULT NULL,
  `chi_tiet` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_hdv`
--

INSERT INTO `booking_hdv` (`id`, `booking_id`, `hdv_id`, `vai_tro`, `chi_tiet`) VALUES
(1, 1, 1, 'hdv', 'Hướng dẫn viên chính cho khách cá nhân'),
(2, 2, 2, 'hdv', 'Hướng dẫn viên cho đoàn 15 người'),
(3, 3, 3, 'hdv', 'Hướng dẫn viên đi tour Bangkok'),
(4, 4, 4, 'hdv', 'Hướng dẫn viên cho tour riêng theo yêu cầu');

-- --------------------------------------------------------

--
-- Table structure for table `booking_khach`
--

CREATE TABLE `booking_khach` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `gioi_tinh` varchar(50) DEFAULT NULL,
  `nam_sinh` year DEFAULT NULL,
  `so_giay_to` varchar(50) DEFAULT NULL,
  `tinh_trang_thanh_toan` varchar(50) DEFAULT NULL,
  `yeu_cau_ca_nhan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_khach`
--

INSERT INTO `booking_khach` (`id`, `booking_id`, `ho_ten`, `gioi_tinh`, `nam_sinh`, `so_giay_to`, `tinh_trang_thanh_toan`, `yeu_cau_ca_nhan`) VALUES
(1, 1, 'Nguyễn Văn A', 'nam', '1990', '123456789', 'da_thanh_toan', 'Ăn chay'),
(2, 2, 'Công ty ABC - Khách 1', 'nam', '1985', '987654321', 'chua_thanh_toan', ''),
(3, 3, 'Trần Thị B', 'nu', '1992', '112233445', 'da_thanh_toan', ''),
(4, 4, 'Công ty XYZ - Khách 1', 'nu', '1988', '556677889', 'chua_thanh_toan', 'Tour riêng yêu cầu riêng');

-- --------------------------------------------------------

--
-- Table structure for table `booking_nhat_ky_log`
--

CREATE TABLE `booking_nhat_ky_log` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `nhat_ky_id` int DEFAULT NULL,
  `tour_id` int DEFAULT NULL,
  `tai_khoan_id` int DEFAULT NULL,
  `trang_thai_cu` varchar(50) DEFAULT NULL,
  `trang_thai_moi` varchar(50) DEFAULT NULL,
  `noi_dung_cu` text,
  `noi_dung_moi` text,
  `ghi_chu` text,
  `thoi_gian_thay_doi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_nhat_ky_log`
--

INSERT INTO `booking_nhat_ky_log` (`id`, `booking_id`, `nhat_ky_id`, `tour_id`, `tai_khoan_id`, `trang_thai_cu`, `trang_thai_moi`, `noi_dung_cu`, `noi_dung_moi`, `ghi_chu`, `thoi_gian_thay_doi`) VALUES
(1, 1, 1, 1, 1, 'cho_xac_nhan', 'da_coc', '', '', 'them', '2025-11-24 10:00:00'),
(2, 2, 2, 2, 1, 'da_coc', 'hoan_tat', '', '', 'sua', '2025-11-25 11:00:00'),
(3, 3, 3, 3, 1, 'cho_xac_nhan', 'hoan_tat', '', '', 'them', '2025-11-26 09:30:00'),
(4, 4, 4, 4, 1, 'cho_xac_nhan', 'da_coc', '', '', 'them', '2025-11-27 08:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc_tour`
--

CREATE TABLE `danh_muc_tour` (
  `id` int NOT NULL,
  `ten_danh_muc` varchar(100) DEFAULT NULL,
  `mo_ta` text,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_cap_nhat` datetime DEFAULT NULL,
  `trang_thai` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danh_muc_tour`
--

INSERT INTO `danh_muc_tour` (`id`, `ten_danh_muc`, `mo_ta`, `ngay_tao`, `ngay_cap_nhat`, `trang_thai`) VALUES
(1, 'Tour Trong Nước', 'Các tour du lịch khám phá các tỉnh, thành phố trong Việt Nam', '2025-11-26 17:51:56', '2025-11-26 17:51:56', 1),
(2, 'Tour Quốc Tế', 'Các tour du lịch ra nước ngoài với nhiều điểm đến hấp dẫn', '2025-11-26 17:51:56', '2025-11-26 17:51:56', 1),
(3, 'Tour Yêu Cầu', 'Các tour được thiết kế riêng theo yêu cầu của khách hàng', '2025-11-26 17:51:56', '2025-11-26 17:51:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `diem_danh_khach`
--

CREATE TABLE `diem_danh_khach` (
  `id` int NOT NULL,
  `booking_khach_id` int DEFAULT NULL,
  `lich_khoi_hanh_id` int DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT NULL,
  `ghi_chu` text,
  `ngay_gio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `diem_danh_khach`
--

INSERT INTO `diem_danh_khach` (`id`, `booking_khach_id`, `lich_khoi_hanh_id`, `trang_thai`, `ghi_chu`, `ngay_gio`) VALUES
(1, 1, 1, 'da_den', 'Đến đúng giờ', '2025-12-01 08:00:00'),
(2, 2, 2, 'vang_mat', 'Vắng mặt do bận việc', '2025-12-05 07:30:00'),
(3, 3, 3, 'da_den', '', '2025-12-10 09:00:00'),
(4, 4, 4, 'tre', 'Đến trễ 15 phút', '2025-12-15 08:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `hdv`
--

CREATE TABLE `hdv` (
  `id` int NOT NULL,
  `tai_khoan_id` int DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `anh_dai_dien` varchar(255) DEFAULT NULL,
  `lien_he` varchar(150) DEFAULT NULL,
  `nhom` varchar(50) DEFAULT NULL,
  `chuyen_mon` varchar(100) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hdv`
--

INSERT INTO `hdv` (`id`, `tai_khoan_id`, `ngay_sinh`, `anh_dai_dien`, `lien_he`, `nhom`, `chuyen_mon`, `ngay_tao`) VALUES
(1, 2, '1990-05-15', 'hdv1.jpg', '0912345678', 'noi_dia', 'Hướng dẫn văn hóa, lịch sử', '2025-11-26 18:07:26'),
(2, 3, '1988-11-20', 'hdv2.jpg', '0987654321', 'quoc_te', 'Hướng dẫn du lịch quốc tế', '2025-11-26 18:07:26'),
(3, 2, '1992-02-10', 'hdv3.jpg', '0911223344', 'noi_dia', 'Hướng dẫn du lịch thiên nhiên', '2025-11-26 18:07:26'),
(4, 3, '1991-07-25', 'hdv4.jpg', '0998877665', 'yeu_cau', 'Hướng dẫn theo yêu cầu khách', '2025-11-26 18:07:26');

-- --------------------------------------------------------

--
-- Table structure for table `khuyen_mai`
--

CREATE TABLE `khuyen_mai` (
  `id` int NOT NULL,
  `ten_khuyen_mai` varchar(100) DEFAULT NULL,
  `loai` varchar(50) DEFAULT NULL,
  `gia_tri` decimal(12,0) DEFAULT NULL,
  `mo_ta` text,
  `bat_dau` datetime DEFAULT NULL,
  `ket_thuc` datetime DEFAULT NULL,
  `dieu_kien` text,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_cap_nhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khuyen_mai`
--

INSERT INTO `khuyen_mai` (`id`, `ten_khuyen_mai`, `loai`, `gia_tri`, `mo_ta`, `bat_dau`, `ket_thuc`, `dieu_kien`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'Giảm 10% Tour Nội Địa', 'phan_tram', 10, 'Áp dụng cho tất cả tour trong nước', '2025-12-01 00:00:00', '2025-12-31 23:59:59', 'Đặt trước 7 ngày', '2025-11-26 18:13:53', '2025-11-26 18:13:53'),
(2, 'Tặng quà khi đặt Tour Quốc Tế', 'mien_phi', 0, 'Khách nhận quà lưu niệm', '2025-12-01 00:00:00', '2025-12-31 23:59:59', 'Đặt tour trên 5 khách', '2025-11-26 18:13:53', '2025-11-26 18:13:53'),
(3, 'Giảm 500.000 VNĐ', 'tien_mat', 500000, 'Giảm trực tiếp trên giá tour', '2025-12-05 00:00:00', '2025-12-20 23:59:59', 'Tour từ 3 ngày trở lên', '2025-11-26 18:13:53', '2025-11-26 18:13:53'),
(4, 'Miễn phí ăn tối', 'mien_phi', 0, 'Miễn phí bữa tối cho đoàn', '2025-12-10 00:00:00', '2025-12-25 23:59:59', 'Áp dụng cho tour đoàn 10 khách trở lên', '2025-11-26 18:13:53', '2025-11-26 18:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `lich_khoi_hanh`
--

CREATE TABLE `lich_khoi_hanh` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `ngay_gio_xuat_phat` datetime DEFAULT NULL,
  `diem_tap_trung` varchar(255) DEFAULT NULL,
  `thoi_gian_ket_thuc` datetime DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lich_khoi_hanh`
--

INSERT INTO `lich_khoi_hanh` (`id`, `booking_id`, `ngay_gio_xuat_phat`, `diem_tap_trung`, `thoi_gian_ket_thuc`, `ghi_chu`) VALUES
(1, 1, '2025-12-01 08:00:00', 'Hà Nội, Nhà hát Lớn', '2025-12-03 18:00:00', 'Khởi hành đúng giờ'),
(2, 2, '2025-12-05 07:30:00', 'Đà Nẵng, Sân bay Đà Nẵng', '2025-12-08 20:00:00', 'Đoàn đi 15 người'),
(3, 3, '2025-12-10 09:00:00', 'Bangkok, Khách sạn Centara', '2025-12-15 19:00:00', ''),
(4, 3, '2025-12-15 08:30:00', 'Hà Nội, Công ty XYZ', '2025-12-18 18:00:00', 'Tour riêng theo yêu cầu');

-- --------------------------------------------------------

--
-- Table structure for table `nhat_ky_tour`
--

CREATE TABLE `nhat_ky_tour` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `ngay_gio` datetime DEFAULT NULL,
  `noi_dung` text,
  `danh_gia_hdv` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nhat_ky_tour`
--

INSERT INTO `nhat_ky_tour` (`id`, `booking_id`, `ngay_gio`, `noi_dung`, `danh_gia_hdv`) VALUES
(1, 1, '2025-12-01 10:00:00', 'Khách tham quan Hồ Hoàn Kiếm và Lăng Bác', 'HDV hướng dẫn tốt, nhiệt tình'),
(2, 2, '2025-12-05 12:00:00', 'Đoàn tham quan Ngũ Hành Sơn và Cầu Rồng', 'HDV chuyên nghiệp, hướng dẫn chi tiết'),
(3, 3, '2025-12-10 14:00:00', 'Khách tham quan cung điện Hoàng Gia Bangkok', 'HDV thân thiện, nói tiếng Anh tốt'),
(4, 4, '2025-12-15 09:00:00', 'Tour riêng theo yêu cầu khách', 'HDV linh hoạt, đáp ứng yêu cầu khách');

-- --------------------------------------------------------

--
-- Table structure for table `tai_khoan`
--

CREATE TABLE `tai_khoan` (
  `id` int NOT NULL,
  `ten_dang_nhap` varchar(50) DEFAULT NULL,
  `mat_khau` varchar(255) DEFAULT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `phan_quyen` varchar(50) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_cap_nhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tai_khoan`
--

INSERT INTO `tai_khoan` (`id`, `ten_dang_nhap`, `mat_khau`, `ho_ten`, `email`, `sdt`, `phan_quyen`, `trang_thai`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'admin01', '$2y$10$CwTycUXWue0Thq9StjUM0uJ8y7ZVdQOaFlW2pMfxo2bX9J1Y0qM6e', 'Nguyễn Văn A', 'admin01@gmail.com', '0912345678', 'admin', 'hoat_dong', '2025-11-26 18:01:44', '2025-11-26 18:01:44'),
(2, 'hdv01', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36TSVZrh74w4dz9ZdG9i6yK', 'Trần Thị B', 'hdv01@gmail.com', '0987654321', 'hdv', 'hoat_dong', '2025-11-26 18:01:44', '2025-11-26 18:01:44'),
(3, 'hdv02', '$2y$10$9u/uw4xEN9yLZK2PO2ONgO8sLxM4iF7gCZyU7cC1Wj.QwpmJ.yxHm', 'Lê Văn C', 'hdv02@gmail.com', '0911223344', 'hdv', 'hoat_dong', '2025-11-26 18:01:44', '2025-11-26 18:01:44'),
(4, 'admin02', '$2y$10$7sN/6k6pXbZyE5R2M8Z7FOD8oxbH1Gv7vZgCwJX2mU3q8a7yFQ9Lm', 'Phạm Thị D', 'admin02@gmail.com', '0998877665', 'admin', 'ngung', '2025-11-26 18:01:44', '2025-11-26 18:01:44');

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `id` int NOT NULL,
  `danh_muc_id` int DEFAULT NULL,
  `ten_tour` varchar(200) DEFAULT NULL,
  `mo_ta` text,
  `gia` decimal(12,0) DEFAULT NULL,
  `trang_thai` tinyint NOT NULL,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_cap_nhat` datetime DEFAULT NULL,
  `anh_tour` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`id`, `danh_muc_id`, `ten_tour`, `mo_ta`, `gia`, `trang_thai`, `ngay_tao`, `ngay_cap_nhat`, `anh_tour`) VALUES
(1, 1, 'Tour Hà Nội – Hạ Long 3 Ngày', 'Khám phá thủ đô Hà Nội và Vịnh Hạ Long', 3500000, 1, '2025-11-26 18:00:12', '2025-11-26 18:00:12', 'ha_noi_ha_long.jpg'),
(2, 1, 'Tour Đà Nẵng – Hội An 4 Ngày', 'Tham quan Đà Nẵng, phố cổ Hội An', 4500000, 1, '2025-11-26 18:00:12', '2025-11-26 18:00:12', 'da_nang_hoi_an.jpg'),
(3, 2, 'Tour Thái Lan 5 Ngày', 'Khám phá Bangkok, Pattaya', 12000000, 1, '2025-11-26 18:00:12', '2025-11-26 18:00:12', 'thai_lan.jpg'),
(4, 3, 'Tour Theo Yêu Cầu', 'Tour riêng thiết kế theo yêu cầu khách hàng', 0, 1, '2025-11-26 18:00:12', '2025-11-26 18:00:12', 'tu_chon.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tour_anh`
--

CREATE TABLE `tour_anh` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `duong_dan` varchar(255) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_anh`
--

INSERT INTO `tour_anh` (`id`, `tour_id`, `duong_dan`, `ngay_tao`) VALUES
(1, 1, 'ha_noi_ha_long_1.jpg', '2025-11-26 18:06:06'),
(2, 1, 'ha_noi_ha_long_2.jpg', '2025-11-26 18:06:06'),
(3, 2, 'da_nang_hoi_an_1.jpg', '2025-11-26 18:06:06'),
(4, 3, 'thai_lan_1.jpg', '2025-11-26 18:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `tour_chinh_sach`
--

CREATE TABLE `tour_chinh_sach` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `ten_chinh_sach` varchar(100) DEFAULT NULL,
  `noi_dung` text,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_cap_nhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_chinh_sach`
--

INSERT INTO `tour_chinh_sach` (`id`, `tour_id`, `ten_chinh_sach`, `noi_dung`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 1, 'Hủy tour', 'Khách hủy trước 7 ngày được hoàn 50%', '2025-11-26 18:06:06', '2025-11-26 18:06:06'),
(2, 1, 'Trẻ em', 'Trẻ em dưới 6 tuổi miễn phí, từ 6-12 tuổi tính 50%', '2025-11-26 18:06:06', '2025-11-26 18:06:06'),
(3, 2, 'Hủy tour', 'Khách hủy trước 10 ngày được hoàn 70%', '2025-11-26 18:06:06', '2025-11-26 18:06:06'),
(4, 3, 'Visa', 'Khách hàng tự chuẩn bị visa', '2025-11-26 18:06:06', '2025-11-26 18:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `tour_lich_trinh`
--

CREATE TABLE `tour_lich_trinh` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `ngay` int DEFAULT NULL,
  `diem_tham_quan` varchar(255) DEFAULT NULL,
  `hoat_dong` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_lich_trinh`
--

INSERT INTO `tour_lich_trinh` (`id`, `tour_id`, `ngay`, `diem_tham_quan`, `hoat_dong`) VALUES
(1, 1, 1, 'Hồ Hoàn Kiếm, Lăng Bác', 'Tham quan các địa điểm nổi tiếng tại Hà Nội'),
(2, 1, 2, 'Vịnh Hạ Long', 'Du thuyền tham quan Vịnh Hạ Long, tắm biển, chèo kayak'),
(3, 2, 1, 'Ngũ Hành Sơn, Cầu Rồng', 'Khám phá các điểm nổi bật tại Đà Nẵng'),
(4, 3, 1, 'Bangkok', 'Tham quan cung điện Hoàng Gia và chùa Wat Pho');

-- --------------------------------------------------------

--
-- Table structure for table `tour_nha_cung_cap`
--

CREATE TABLE `tour_nha_cung_cap` (
  `id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `ten_nha_cung_cap` varchar(100) DEFAULT NULL,
  `loai` varchar(50) DEFAULT NULL,
  `lien_he` varchar(150) DEFAULT NULL,
  `ghi_chu` text,
  `ngay_tao` datetime DEFAULT NULL,
  `ngay_cap_nhat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_nha_cung_cap`
--

INSERT INTO `tour_nha_cung_cap` (`id`, `tour_id`, `ten_nha_cung_cap`, `loai`, `lien_he`, `ghi_chu`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 1, 'Khách sạn Halong Bay', 'khach_san', '0909123456', 'Gần bờ vịnh', '2025-11-26 18:06:06', '2025-11-26 18:06:06'),
(2, 1, 'Xe Limousine Hạ Long', 'xe', '0909988776', 'Đưa đón sân bay và khách sạn', '2025-11-26 18:06:06', '2025-11-26 18:06:06'),
(3, 2, 'Nhà hàng Hội An', 'nha_hang', '0911223344', 'Ăn tối tại nhà hàng nổi tiếng', '2025-11-26 18:06:06', '2025-11-26 18:06:06'),
(4, 3, 'Công ty du lịch Thái Lan', 'khac', '0888877666', 'Hướng dẫn viên bản địa', '2025-11-26 18:06:06', '2025-11-26 18:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `yeu_cau_dac_biet`
--

CREATE TABLE `yeu_cau_dac_biet` (
  `id` int NOT NULL,
  `booking_khach_id` int DEFAULT NULL,
  `noi_dung` text,
  `ngay_tao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `yeu_cau_dac_biet`
--

INSERT INTO `yeu_cau_dac_biet` (`id`, `booking_khach_id`, `noi_dung`, `ngay_tao`) VALUES
(1, 1, 'Ăn chay và không dùng hải sản', '2025-11-26 18:12:33'),
(2, 2, 'Cần xe lớn cho đoàn', '2025-11-26 18:12:33'),
(3, 3, 'Muốn hướng dẫn viên nói tiếng Anh', '2025-11-26 18:12:33'),
(4, 4, 'Tour riêng theo yêu cầu khách hàng', '2025-11-26 18:12:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bao_cao_tong_hop_tour`
--
ALTER TABLE `bao_cao_tong_hop_tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tai_khoan_id` (`tai_khoan_id`),
  ADD KEY `assigned_hdv_id` (`assigned_hdv_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `booking_dich_vu`
--
ALTER TABLE `booking_dich_vu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `booking_hdv`
--
ALTER TABLE `booking_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `hdv_id` (`hdv_id`);

--
-- Indexes for table `booking_khach`
--
ALTER TABLE `booking_khach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `booking_nhat_ky_log`
--
ALTER TABLE `booking_nhat_ky_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `nhat_ky_id` (`nhat_ky_id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `tai_khoan_id` (`tai_khoan_id`);

--
-- Indexes for table `danh_muc_tour`
--
ALTER TABLE `danh_muc_tour`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diem_danh_khach`
--
ALTER TABLE `diem_danh_khach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_khach_id` (`booking_khach_id`),
  ADD KEY `lich_khoi_hanh_id` (`lich_khoi_hanh_id`);

--
-- Indexes for table `hdv`
--
ALTER TABLE `hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tai_khoan_id` (`tai_khoan_id`);

--
-- Indexes for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `danh_muc_id` (`danh_muc_id`);

--
-- Indexes for table `tour_anh`
--
ALTER TABLE `tour_anh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_chinh_sach`
--
ALTER TABLE `tour_chinh_sach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_lich_trinh`
--
ALTER TABLE `tour_lich_trinh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_nha_cung_cap`
--
ALTER TABLE `tour_nha_cung_cap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `yeu_cau_dac_biet`
--
ALTER TABLE `yeu_cau_dac_biet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_khach_id` (`booking_khach_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bao_cao_tong_hop_tour`
--
ALTER TABLE `bao_cao_tong_hop_tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `booking_dich_vu`
--
ALTER TABLE `booking_dich_vu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `booking_hdv`
--
ALTER TABLE `booking_hdv`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `booking_khach`
--
ALTER TABLE `booking_khach`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `booking_nhat_ky_log`
--
ALTER TABLE `booking_nhat_ky_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `danh_muc_tour`
--
ALTER TABLE `danh_muc_tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `diem_danh_khach`
--
ALTER TABLE `diem_danh_khach`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hdv`
--
ALTER TABLE `hdv`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `khuyen_mai`
--
ALTER TABLE `khuyen_mai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tai_khoan`
--
ALTER TABLE `tai_khoan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tour_anh`
--
ALTER TABLE `tour_anh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tour_chinh_sach`
--
ALTER TABLE `tour_chinh_sach`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tour_lich_trinh`
--
ALTER TABLE `tour_lich_trinh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tour_nha_cung_cap`
--
ALTER TABLE `tour_nha_cung_cap`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `yeu_cau_dac_biet`
--
ALTER TABLE `yeu_cau_dac_biet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bao_cao_tong_hop_tour`
--
ALTER TABLE `bao_cao_tong_hop_tour`
  ADD CONSTRAINT `bao_cao_tong_hop_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`tai_khoan_id`) REFERENCES `tai_khoan` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`assigned_hdv_id`) REFERENCES `tai_khoan` (`id`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`);

--
-- Constraints for table `booking_dich_vu`
--
ALTER TABLE `booking_dich_vu`
  ADD CONSTRAINT `booking_dich_vu_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Constraints for table `booking_hdv`
--
ALTER TABLE `booking_hdv`
  ADD CONSTRAINT `booking_hdv_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `booking_hdv_ibfk_2` FOREIGN KEY (`hdv_id`) REFERENCES `hdv` (`id`);

--
-- Constraints for table `booking_khach`
--
ALTER TABLE `booking_khach`
  ADD CONSTRAINT `booking_khach_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Constraints for table `booking_nhat_ky_log`
--
ALTER TABLE `booking_nhat_ky_log`
  ADD CONSTRAINT `booking_nhat_ky_log_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `booking_nhat_ky_log_ibfk_2` FOREIGN KEY (`nhat_ky_id`) REFERENCES `nhat_ky_tour` (`id`),
  ADD CONSTRAINT `booking_nhat_ky_log_ibfk_3` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`),
  ADD CONSTRAINT `booking_nhat_ky_log_ibfk_4` FOREIGN KEY (`tai_khoan_id`) REFERENCES `tai_khoan` (`id`);

--
-- Constraints for table `diem_danh_khach`
--
ALTER TABLE `diem_danh_khach`
  ADD CONSTRAINT `diem_danh_khach_ibfk_1` FOREIGN KEY (`booking_khach_id`) REFERENCES `booking_khach` (`id`),
  ADD CONSTRAINT `diem_danh_khach_ibfk_2` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`);

--
-- Constraints for table `hdv`
--
ALTER TABLE `hdv`
  ADD CONSTRAINT `hdv_ibfk_1` FOREIGN KEY (`tai_khoan_id`) REFERENCES `tai_khoan` (`id`);

--
-- Constraints for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  ADD CONSTRAINT `lich_khoi_hanh_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Constraints for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  ADD CONSTRAINT `nhat_ky_tour_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`);

--
-- Constraints for table `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `tour_ibfk_1` FOREIGN KEY (`danh_muc_id`) REFERENCES `danh_muc_tour` (`id`);

--
-- Constraints for table `tour_anh`
--
ALTER TABLE `tour_anh`
  ADD CONSTRAINT `tour_anh_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`);

--
-- Constraints for table `tour_chinh_sach`
--
ALTER TABLE `tour_chinh_sach`
  ADD CONSTRAINT `tour_chinh_sach_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`);

--
-- Constraints for table `tour_lich_trinh`
--
ALTER TABLE `tour_lich_trinh`
  ADD CONSTRAINT `tour_lich_trinh_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`);

--
-- Constraints for table `tour_nha_cung_cap`
--
ALTER TABLE `tour_nha_cung_cap`
  ADD CONSTRAINT `tour_nha_cung_cap_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`);

--
-- Constraints for table `yeu_cau_dac_biet`
--
ALTER TABLE `yeu_cau_dac_biet`
  ADD CONSTRAINT `yeu_cau_dac_biet_ibfk_1` FOREIGN KEY (`booking_khach_id`) REFERENCES `booking_khach` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
