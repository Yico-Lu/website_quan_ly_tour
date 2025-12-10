<?php
class Booking
{
    // Các thuộc tính của booking
    public $id;
    public $tai_khoan_id;
    public $assigned_hdv_id;
    public $tour_id;
    public $loai_khach;
    public $ten_nguoi_dat;
    public $so_luong;
    public $thoi_gian_tour;
    public $lien_he;
    public $yeu_cau_dac_biet;
    public $trang_thai;
    public $ghi_chu;
    public $ngay_tao;
    public $ngay_cap_nhat;

    // Thông tin liên kết
    public $ten_tour;
    public $ten_hdv;

    // Constructor để khởi tạo thực thể Booking
    public function __construct($data = [])
    {
        // Nếu truyền vào mảng dữ liệu thì gán vào các thuộc tính
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->tai_khoan_id = $data['tai_khoan_id'] ?? null;
            $this->assigned_hdv_id = $data['assigned_hdv_id'] ?? null;
            $this->tour_id = $data['tour_id'] ?? null;
            $this->loai_khach = $data['loai_khach'] ?? 'le';
            $this->ten_nguoi_dat = $data['ten_nguoi_dat'] ?? '';
            $this->so_luong = $data['so_luong'] ?? 1;
            $this->thoi_gian_tour = $data['thoi_gian_tour'] ?? null;
            $this->lien_he = $data['lien_he'] ?? '';
            $this->yeu_cau_dac_biet = $data['yeu_cau_dac_biet'] ?? '';
            $this->trang_thai = $data['trang_thai'] ?? 'cho_xac_nhan';
            $this->ghi_chu = $data['ghi_chu'] ?? '';
            $this->ngay_tao = $data['ngay_tao'] ?? date('Y-m-d H:i:s');
            $this->ngay_cap_nhat = $data['ngay_cap_nhat'] ?? date('Y-m-d H:i:s');

            // Thông tin liên kết
            $this->ten_tour = $data['ten_tour'] ?? '';
            $this->ten_hdv = $data['ten_hdv'] ?? '';
        }
    }

    // Lấy danh sách tất cả booking với thông tin liên kết
    public static function getAll()
    {
        $pdo = getDB();
        $sql = "SELECT b.*,
                       t.ten_tour
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                ORDER BY b.ngay_tao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $bookings = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $bookings[] = new Booking($row);
        }
        return $bookings;
    }

    // Tìm booking theo ID với thông tin liên kết
    public static function find($id)
    {
        $pdo = getDB();
        $sql = "SELECT b.*,
                       t.ten_tour
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                WHERE b.id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Booking($data) : null;
    }

    // Lấy danh sách tour đang hoạt động
    public static function getTourList()
    {
        $pdo = getDB();
        $sql = "SELECT id, ten_tour FROM tour WHERE trang_thai = 1 ORDER BY ten_tour";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách hướng dẫn viên
    public static function getGuideList()
    {
        $pdo = getDB();
        $sql = "SELECT id, ho_ten, email FROM tai_khoan WHERE phan_quyen = 'hdv' AND trang_thai = 'hoat_dong' ORDER BY ho_ten";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra xem cột assigned_hdv_id có tồn tại không
    public static function hasAssignedHdvColumn()
    {
        $pdo = getDB();
        try {
            $stmt = $pdo->prepare("DESCRIBE booking");
            $stmt->execute();
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            return in_array('assigned_hdv_id', $columns);
        } catch (Exception $e) {
            return false;
        }
    }


    // Tạo booking mới
    public static function create(Booking $booking)
    {
        $pdo = getDB();

        // Kiểm tra xem cột assigned_hdv_id có tồn tại không
        $hasAssignedHdvColumn = self::hasAssignedHdvColumn();

        if ($hasAssignedHdvColumn) {
            $sql = "INSERT INTO booking (tai_khoan_id, assigned_hdv_id, tour_id, loai_khach, ten_nguoi_dat, so_luong, thoi_gian_tour, lien_he, yeu_cau_dac_biet, trang_thai, ghi_chu, ngay_tao, ngay_cap_nhat)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $booking->tai_khoan_id ?: null, // Có thể null nếu không có khách hàng
                $booking->assigned_hdv_id ?: null,
                $booking->tour_id,
                $booking->loai_khach,
                $booking->ten_nguoi_dat,
                $booking->so_luong,
                $booking->thoi_gian_tour,
                $booking->lien_he,
                $booking->yeu_cau_dac_biet,
                $booking->trang_thai,
                $booking->ghi_chu
            ]);
        } else {
            $sql = "INSERT INTO booking (tai_khoan_id, tour_id, loai_khach, ten_nguoi_dat, so_luong, thoi_gian_tour, lien_he, yeu_cau_dac_biet, trang_thai, ghi_chu, ngay_tao, ngay_cap_nhat)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $booking->tai_khoan_id ?: null, // Có thể null nếu không có khách hàng
                $booking->tour_id,
                $booking->loai_khach,
                $booking->ten_nguoi_dat,
                $booking->so_luong,
                $booking->thoi_gian_tour,
                $booking->lien_he,
                $booking->yeu_cau_dac_biet,
                $booking->trang_thai,
                $booking->ghi_chu
            ]);
        }
    }

    // Cập nhật booking
    public static function update(Booking $booking)
    {
        $pdo = getDB();

        // Kiểm tra xem cột assigned_hdv_id có tồn tại không
        $hasAssignedHdvColumn = self::hasAssignedHdvColumn();

        if ($hasAssignedHdvColumn) {
            $sql = "UPDATE booking SET
                            tai_khoan_id = ?,
                            assigned_hdv_id = ?,
                            tour_id = ?,
                            loai_khach = ?,
                            ten_nguoi_dat = ?,
                            so_luong = ?,
                            thoi_gian_tour = ?,
                            lien_he = ?,
                            yeu_cau_dac_biet = ?,
                            trang_thai = ?,
                            ghi_chu = ?,
                            ngay_cap_nhat = NOW()
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $booking->tai_khoan_id,
                $booking->assigned_hdv_id,
                $booking->tour_id,
                $booking->loai_khach,
                $booking->ten_nguoi_dat,
                $booking->so_luong,
                $booking->thoi_gian_tour,
                $booking->lien_he,
                $booking->yeu_cau_dac_biet,
                $booking->trang_thai,
                $booking->ghi_chu,
                $booking->id
            ]);
        } else {
            $sql = "UPDATE booking SET
                            tai_khoan_id = ?,
                            tour_id = ?,
                            loai_khach = ?,
                            ten_nguoi_dat = ?,
                            so_luong = ?,
                            thoi_gian_tour = ?,
                            lien_he = ?,
                            yeu_cau_dac_biet = ?,
                            trang_thai = ?,
                            ghi_chu = ?,
                            ngay_cap_nhat = NOW()
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $booking->tai_khoan_id,
                $booking->tour_id,
                $booking->loai_khach,
                $booking->ten_nguoi_dat,
                $booking->so_luong,
                $booking->thoi_gian_tour,
                $booking->lien_he,
                $booking->yeu_cau_dac_biet,
                $booking->trang_thai,
                $booking->ghi_chu,
                $booking->id
            ]);
        }
    }

    // Xóa booking hoàn toàn
    public static function delete($id)
    {
        $pdo = getDB();

        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $pdo->beginTransaction();

            // Xóa dữ liệu liên quan trước (nếu có)
            $tables = ['booking_dich_vu', 'booking_hoa_don', 'booking_chi_tiet']; // Các bảng có thể liên quan

            foreach ($tables as $table) {
                try {
                    $pdo->prepare("DELETE FROM `$table` WHERE booking_id = ?")->execute([$id]);
                } catch (Exception $e) {
                    // Bỏ qua nếu bảng không tồn tại
                }
            }

            // Xóa booking chính
            $sql = "DELETE FROM booking WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$id]);

            // Commit transaction
            $pdo->commit();

            return $result;
        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            $pdo->rollBack();
            error_log('Cannot delete booking: ' . $e->getMessage());
            return false;
        }
    }


    // Lấy tên loại khách
    public function getLoaiKhach()
    {
        $types = [
            'le' => 'Khách lẻ',
            'doan' => 'Khách đoàn'
        ];
        return $types[$this->loai_khach] ?? 'Không xác định';
    }

    // Lấy tên trạng thái
    public function getTrangThai()
    {
        $statuses = [
            'cho_xac_nhan' => 'Chờ xác nhận',
            'da_coc' => 'Đã cọc',
            'da_thanh_toan' => 'Đã thanh toán',
            'da_huy' => 'Đã hủy',
            'dang_xu_ly' => 'Đang xử lý',
            'da_xac_nhan' => 'Đã xác nhận',
            'hoan_thanh' => 'Hoàn thành'
        ];
        return $statuses[$this->trang_thai] ?? 'Không xác định';
    }

    // Lấy class badge cho trạng thái
    public function getTrangThaiBadgeClass()
    {
        $classes = [
            'cho_xac_nhan' => 'text-bg-warning',
            'da_coc' => 'text-bg-info',
            'da_thanh_toan' => 'text-bg-primary',
            'da_huy' => 'text-bg-danger',
            'dang_xu_ly' => 'text-bg-warning',
            'da_xac_nhan' => 'text-bg-info',
            'hoan_thanh' => 'text-bg-success'
        ];
        return $classes[$this->trang_thai] ?? 'text-bg-secondary';
    }

    // Lấy class badge cho loại khách
    public function getLoaiKhachBadgeClass()
    {
        return $this->loai_khach === 'le' ? 'text-bg-success' : 'text-bg-info';
    }

    // Tính tổng tiền (nếu cần)
    public function getTongTien()
    {
        // Giả sử có logic tính tổng tiền dựa trên tour và số lượng
        // Hiện tại chưa implement, có thể mở rộng sau
        return 0;
    }

    // Format tiền tệ
    public function formatTongTien()
    {
        return number_format($this->getTongTien(), 0, ',', '.') . ' VND';
    }
}
?>