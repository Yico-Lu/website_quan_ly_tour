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
    public $hdv_id; // ID của HDV từ bảng hdv để tạo link
    public $lich_khoi_hanh_id;
    public $ngay_gio_xuat_phat;
    public $diem_tap_trung;
    public $thoi_gian_ket_thuc;
    public $lich_ghi_chu;
    public $gia_tour;

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
            $this->hdv_id = $data['hdv_id'] ?? null;
            $this->lich_khoi_hanh_id = $data['lich_khoi_hanh_id'] ?? null;
            $this->ngay_gio_xuat_phat = $data['ngay_gio_xuat_phat'] ?? null;
            $this->diem_tap_trung = $data['diem_tap_trung'] ?? null;
            $this->thoi_gian_ket_thuc = $data['thoi_gian_ket_thuc'] ?? null;
            $this->lich_ghi_chu = $data['lich_ghi_chu'] ?? null;
            $this->gia_tour = $data['gia_tour'] ?? null;
        }
    }

    // Lấy danh sách tất cả booking với thông tin liên kết
    public static function getAll()
    {
        $pdo = getDB();
        $sql = "SELECT b.*,
                       t.ten_tour,
                       t.gia AS gia_tour,
                       /* Lấy bản ghi lịch khởi hành mới nhất của booking */
                       (SELECT l1.id FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS lich_khoi_hanh_id,
                       (SELECT l1.ngay_gio_xuat_phat FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS ngay_gio_xuat_phat,
                       (SELECT l1.diem_tap_trung FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS diem_tap_trung,
                       (SELECT l1.thoi_gian_ket_thuc FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS thoi_gian_ket_thuc,
                       COALESCE(
                           (SELECT GROUP_CONCAT(DISTINCT CONCAT(tk.ho_ten, ' (', tk.email, ')') SEPARATOR ', ')
                            FROM booking_hdv bh2
                            INNER JOIN hdv h2 ON bh2.hdv_id = h2.id
                            INNER JOIN tai_khoan tk ON h2.tai_khoan_id = tk.id
                            WHERE bh2.booking_id = b.id),
                           tk.ho_ten,
                           ''
                       ) AS ten_hdv,
                       COALESCE(
                           (SELECT h2.id
                            FROM booking_hdv bh2
                            INNER JOIN hdv h2 ON bh2.hdv_id = h2.id
                            WHERE bh2.booking_id = b.id
                            LIMIT 1),
                           h.id
                       ) AS hdv_id
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                LEFT JOIN lich_khoi_hanh lkh ON lkh.booking_id = b.id
                LEFT JOIN hdv h ON b.assigned_hdv_id = h.id
                LEFT JOIN tai_khoan tk ON h.tai_khoan_id = tk.id
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
                       t.ten_tour,
                       t.gia AS gia_tour,
                       /* Lấy bản ghi lịch khởi hành mới nhất của booking */
                       (SELECT l1.id FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS lich_khoi_hanh_id,
                       (SELECT l1.ngay_gio_xuat_phat FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS ngay_gio_xuat_phat,
                       (SELECT l1.diem_tap_trung FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS diem_tap_trung,
                       (SELECT l1.thoi_gian_ket_thuc FROM lich_khoi_hanh l1 WHERE l1.booking_id = b.id ORDER BY l1.id DESC LIMIT 1) AS thoi_gian_ket_thuc,
                       COALESCE(
                           (SELECT GROUP_CONCAT(DISTINCT CONCAT(tk.ho_ten, ' (', tk.email, ')') SEPARATOR ', ')
                            FROM booking_hdv bh2
                            INNER JOIN hdv h2 ON bh2.hdv_id = h2.id
                            INNER JOIN tai_khoan tk ON h2.tai_khoan_id = tk.id
                            WHERE bh2.booking_id = b.id),
                           tk.ho_ten,
                           ''
                       ) AS ten_hdv,
                       COALESCE(
                           (SELECT h2.id
                            FROM booking_hdv bh2
                            INNER JOIN hdv h2 ON bh2.hdv_id = h2.id
                            WHERE bh2.booking_id = b.id
                            LIMIT 1),
                           h.id
                       ) AS hdv_id
                FROM booking b
                LEFT JOIN tour t ON b.tour_id = t.id
                LEFT JOIN lich_khoi_hanh lkh ON lkh.booking_id = b.id
                LEFT JOIN hdv h ON b.assigned_hdv_id = h.id
                LEFT JOIN tai_khoan tk ON h.tai_khoan_id = tk.id
                WHERE b.id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Booking($data) : null;
    }

    // Lấy danh sách tour đang hoạt động kèm giá
    public static function getTourList()
    {
        $pdo = getDB();
        $sql = "SELECT 
                    t.id, 
                    t.ten_tour,
                    t.gia
                FROM tour t
                WHERE t.trang_thai = 1
                ORDER BY t.ten_tour";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách hướng dẫn viên từ bảng hdv
    public static function getGuideList()
    {
        $pdo = getDB();
        $sql = "SELECT h.id, tk.ho_ten, tk.email 
                FROM hdv h
                INNER JOIN tai_khoan tk ON h.tai_khoan_id = tk.id
                WHERE tk.trang_thai = 'hoat_dong' 
                ORDER BY tk.ho_ten";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Tạo booking mới
    public static function create(Booking $booking)
    {
        $pdo = getDB();
        try {
            $sql = "INSERT INTO booking (tai_khoan_id, assigned_hdv_id, tour_id, loai_khach, ten_nguoi_dat, so_luong, thoi_gian_tour, lien_he, yeu_cau_dac_biet, trang_thai, ghi_chu, ngay_tao, ngay_cap_nhat)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
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
            
            // Trả về ID của booking vừa tạo
            if ($result) {
                return $pdo->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error creating booking: ' . $e->getMessage());
            return false;
        }
    }

    // Cập nhật booking
    public static function update(Booking $booking)
    {
        $pdo = getDB();
        try {
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
            $result = $stmt->execute([
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
            return $result;
        } catch (PDOException $e) {
            error_log('Error updating booking: ' . $e->getMessage());
            return false;
        }
    }

    // Xóa booking hoàn toàn
    public static function delete($id)
    {
        $pdo = getDB();
        if (!$pdo) {
            error_log('Booking::delete() - Database connection failed');
            return false;
        }

        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $pdo->beginTransaction();

            // Xóa dữ liệu liên quan trước (nếu có)
            $tables = ['booking_nhat_ky_log', 'booking_dich_vu', 'booking_hdv', 'booking_hoa_don', 'booking_chi_tiet']; // Các bảng có thể liên quan

            foreach ($tables as $table) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM `$table` WHERE booking_id = ?");
                    $stmt->execute([$id]);
                } catch (PDOException $e) {
                    // Bỏ qua nếu bảng không tồn tại hoặc có lỗi (không phải lỗi nghiêm trọng)
                    error_log("Warning: Could not delete from $table for booking $id: " . $e->getMessage());
                    // Tiếp tục xóa các bảng khác
                }
            }

            // Xóa điểm danh khách dựa trên booking_khach
            try {
                $pdo->prepare("
                    DELETE FROM diem_danh_khach
                    WHERE booking_khach_id IN (SELECT id FROM booking_khach WHERE booking_id = ?)
                ")->execute([$id]);
            } catch (PDOException $e) {
                error_log("Warning: Could not delete diem_danh_khach for booking $id: " . $e->getMessage());
            }

            // Xóa booking_khach
            try {
                $pdo->prepare("DELETE FROM booking_khach WHERE booking_id = ?")->execute([$id]);
            } catch (PDOException $e) {
                error_log("Warning: Could not delete booking_khach for booking $id: " . $e->getMessage());
            }

            // Xóa lich_khoi_hanh
            try {
                $pdo->prepare("DELETE FROM lich_khoi_hanh WHERE booking_id = ?")->execute([$id]);
            } catch (PDOException $e) {
                error_log("Warning: Could not delete lich_khoi_hanh for booking $id: " . $e->getMessage());
            }

            // Xóa booking chính
            $sql = "DELETE FROM booking WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$id]);

            if (!$result) {
                throw new PDOException("Failed to delete booking with id: $id");
            }

            // Commit transaction
            $pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Cannot delete booking: ' . $e->getMessage());
            error_log('Error code: ' . $e->getCode());
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

    // ========== QUẢN LÝ DỊCH VỤ ==========
    
    // Lấy danh sách dịch vụ của booking
    public function getDichVus()
    {
        $pdo = getDB();
        $sql = "SELECT * FROM booking_dich_vu WHERE booking_id = ? ORDER BY ngay_tao ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function deleteDichVuByBooking($bookingId)
    {
        $pdo = getDB();
        if (!$pdo) return false;
        $stmt = $pdo->prepare("DELETE FROM booking_dich_vu WHERE booking_id = ?");
        return $stmt->execute([$bookingId]);
    }

    // Thêm dịch vụ mới
    public function addDichVu($ten_dich_vu, $chi_tiet)
    {
        $pdo = getDB();
        $sql = "INSERT INTO booking_dich_vu (booking_id, ten_dich_vu, chi_tiet, ngay_tao)
                VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$this->id, $ten_dich_vu, $chi_tiet]);
    }

    // Cập nhật dịch vụ
    public static function updateDichVu($id, $ten_dich_vu, $chi_tiet)
    {
        $pdo = getDB();
        $sql = "UPDATE booking_dich_vu SET
                        ten_dich_vu = ?,
                        chi_tiet = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$ten_dich_vu, $chi_tiet, $id]);
    }

    // Xóa dịch vụ
    public static function deleteDichVu($id)
    {
        $pdo = getDB();
        $sql = "DELETE FROM booking_dich_vu WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ========== QUẢN LÝ HDV ==========
    
    // Lấy danh sách HDV của booking
    public function getHdvs()
    {
        $pdo = getDB();
        $sql = "SELECT bh.id,
                       bh.booking_id,
                       bh.hdv_id,
                       bh.vai_tro,
                       bh.chi_tiet,
                       h.id as hdv_table_id,
                       tk.ho_ten, 
                       tk.email,
                       tk.phan_quyen
                FROM booking_hdv bh
                LEFT JOIN hdv h ON bh.hdv_id = h.id
                LEFT JOIN tai_khoan tk ON h.tai_khoan_id = tk.id
                WHERE bh.booking_id = ?
                ORDER BY bh.id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Lọc bỏ các bản ghi không có thông tin HDV hợp lệ
        $filteredResults = [];
        foreach ($results as $result) {
            // Chỉ thêm vào nếu có ho_ten (tức là có thông tin từ hdv và tai_khoan)
            if (!empty($result['ho_ten']) && !empty($result['hdv_table_id'])) {
                $filteredResults[] = $result;
            }
        }
        
        return $filteredResults;
    }

    // Thêm HDV mới
    public function addHdv($hdv_id, $vai_tro, $chi_tiet)
    {
        $pdo = getDB();
        try {
            // Kiểm tra xem HDV có tồn tại trong bảng hdv không
            $checkSql = "SELECT id FROM hdv WHERE id = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$hdv_id]);
            if (!$checkStmt->fetch()) {
                error_log("HDV ID {$hdv_id} không tồn tại trong bảng hdv");
                return false;
            }
            
            // Kiểm tra xem đã tồn tại chưa (tránh trùng lặp)
            $checkExistSql = "SELECT id FROM booking_hdv WHERE booking_id = ? AND hdv_id = ?";
            $checkExistStmt = $pdo->prepare($checkExistSql);
            $checkExistStmt->execute([$this->id, $hdv_id]);
            if ($checkExistStmt->fetch()) {
                error_log("HDV ID {$hdv_id} đã tồn tại trong booking này");
                return false;
            }
            
            $sql = "INSERT INTO booking_hdv (booking_id, hdv_id, vai_tro, chi_tiet)
                    VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $this->id, 
                $hdv_id, 
                $vai_tro ?: 'hdv', 
                $chi_tiet ?: null
            ]);
            
            if ($result) {
                error_log("Successfully added HDV {$hdv_id} to booking {$this->id}");
            } else {
                error_log("Failed to add HDV {$hdv_id} to booking {$this->id}. Error info: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log('Error adding HDV: ' . $e->getMessage());
            error_log('Error code: ' . $e->getCode());
            error_log('Booking ID: ' . $this->id . ', HDV ID: ' . $hdv_id);
            error_log('SQL State: ' . $e->getCode());
            
            // Nếu lỗi foreign key, có thể do constraint sai
            if ($e->getCode() == '23000') {
                if (strpos($e->getMessage(), 'foreign key') !== false) {
                    error_log("Foreign key constraint error: hdv_id = {$hdv_id} may not exist in hdv table or constraint is wrong");
                }
            }
            return false;
        }
    }

    // Cập nhật HDV
    public static function updateHdv($id, $hdv_id, $vai_tro, $chi_tiet)
    {
        $pdo = getDB();
        try {
            // Kiểm tra xem HDV có tồn tại trong bảng hdv không
            $checkSql = "SELECT id FROM hdv WHERE id = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$hdv_id]);
            if (!$checkStmt->fetch()) {
                error_log("HDV ID {$hdv_id} không tồn tại trong bảng hdv");
                return false;
            }
            
            $sql = "UPDATE booking_hdv SET
                            hdv_id = ?,
                            vai_tro = ?,
                            chi_tiet = ?
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$hdv_id, $vai_tro ?: 'hdv', $chi_tiet ?: null, $id]);
            
            if ($result) {
                error_log("Successfully updated HDV {$id} with new HDV ID {$hdv_id}");
            } else {
                error_log("Failed to update HDV {$id}");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log('Error updating HDV: ' . $e->getMessage());
            return false;
        }
    }

    // Xóa HDV
    public static function deleteHdv($id)
    {
        $pdo = getDB();
        $sql = "DELETE FROM booking_hdv WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ========== QUẢN LÝ KHÁCH HÀNG ==========
    
    // Lấy danh sách khách hàng của booking
    public function getKhachs()
    {
        $pdo = getDB();
        $sql = "SELECT * FROM booking_khach WHERE booking_id = ? ORDER BY id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm khách hàng mới
    public function addKhach($ho_ten, $gioi_tinh, $nam_sinh, $so_giay_to, $tinh_trang_thanh_toan, $yeu_cau_ca_nhan)
    {
        $pdo = getDB();
        try {
            $sql = "INSERT INTO booking_khach (booking_id, ho_ten, gioi_tinh, nam_sinh, so_giay_to, tinh_trang_thanh_toan, yeu_cau_ca_nhan)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $this->id, 
                $ho_ten, 
                $gioi_tinh ?: null, 
                $nam_sinh ?: null, 
                $so_giay_to ?: null, 
                $tinh_trang_thanh_toan ?: 'chua_thanh_toan',
                $yeu_cau_ca_nhan ?: null
            ]);
            return $result;
        } catch (PDOException $e) {
            error_log('Error adding khach: ' . $e->getMessage());
            return false;
        }
    }

    // Cập nhật khách hàng
    public static function updateKhach($id, $ho_ten, $gioi_tinh, $nam_sinh, $so_giay_to, $tinh_trang_thanh_toan, $yeu_cau_ca_nhan)
    {
        $pdo = getDB();
        try {
        $sql = "UPDATE booking_khach SET
                        ho_ten = ?,
                        gioi_tinh = ?,
                        nam_sinh = ?,
                        so_giay_to = ?,
                        tinh_trang_thanh_toan = ?,
                        yeu_cau_ca_nhan = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
            $ho_ten, 
                $gioi_tinh ?: null, 
            $nam_sinh ?: null, 
                $so_giay_to ?: null, 
            $tinh_trang_thanh_toan ?: 'chua_thanh_toan',
                $yeu_cau_ca_nhan ?: null,
            $id
        ]);
            return $result;
        } catch (PDOException $e) {
            error_log('Error updating khach: ' . $e->getMessage());
            return false;
        }
    }

    // Xóa khách hàng
    public static function deleteKhach($id)
    {
        $pdo = getDB();
        $sql = "DELETE FROM booking_khach WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Lấy tên trạng thái thanh toán
    public static function getTinhTrangThanhToanName($status)
    {
        $statuses = [
            'chua_thanh_toan' => 'Chưa thanh toán',
            'da_thanh_toan' => 'Đã thanh toán',
            'da_coc' => 'Đã cọc'
        ];
        return $statuses[$status] ?? 'Không xác định';
    }

    // Lấy class badge cho trạng thái thanh toán
    public static function getTinhTrangThanhToanBadgeClass($status)
    {
        $classes = [
            'chua_thanh_toan' => 'text-bg-warning',
            'da_thanh_toan' => 'text-bg-success',
            'da_coc' => 'text-bg-info'
        ];
        return $classes[$status] ?? 'text-bg-secondary';
    }

    /**
     * Lấy lich_khoi_hanh_id theo booking_id (lấy bản ghi mới nhất nếu có nhiều).
     */
    public static function getLichKhoiHanhIdByBookingId($bookingId)
    {
        $pdo = getDB();
        if (!$pdo) {
            return null;
        }
        $stmt = $pdo->prepare("SELECT id FROM lich_khoi_hanh WHERE booking_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$bookingId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'] ?? null;
    }

    /**
     * Thêm/cập nhật điểm danh cho một booking_khach và lịch khởi hành.
     */
    public static function upsertDiemDanh($lichKhoiHanhId, $bookingKhachId, $trangThai, $ghiChu = null)
    {
        $pdo = getDB();
        if (!$pdo) {
            return false;
        }

        // Kiểm tra đã có bản ghi chưa
        $checkSql = "SELECT id FROM diem_danh_khach WHERE lich_khoi_hanh_id = ? AND booking_khach_id = ?";
        $stmt = $pdo->prepare($checkSql);
        $stmt->execute([$lichKhoiHanhId, $bookingKhachId]);
        $existing = $stmt->fetch();

        if ($existing && !empty($existing['id'])) {
            $updateSql = "UPDATE diem_danh_khach 
                          SET trang_thai = ?, ghi_chu = ?, ngay_gio = NOW()
                          WHERE id = ?";
            $updateStmt = $pdo->prepare($updateSql);
            return $updateStmt->execute([$trangThai, $ghiChu ?: null, $existing['id']]);
        }

        $insertSql = "INSERT INTO diem_danh_khach (lich_khoi_hanh_id, booking_khach_id, trang_thai, ghi_chu, ngay_gio)
                      VALUES (?, ?, ?, ?, NOW())";
        $insertStmt = $pdo->prepare($insertSql);
        return $insertStmt->execute([$lichKhoiHanhId, $bookingKhachId, $trangThai, $ghiChu ?: null]);
    }

    /**
     * Lấy danh sách điểm danh theo booking (gộp theo từng booking_khach).
     * Trả về các cột: id, booking_khach_id, lich_khoi_hanh_id, trang_thai, ghi_chu, ngay_gio.
     */
    public static function getDiemDanhByBooking($bookingId, $lichKhoiHanhId = null)
    {
        $pdo = getDB();
        if (!$pdo) {
            return [];
        }

        // Nếu chưa truyền lich_khoi_hanh_id, lấy bản ghi mới nhất theo booking
        if ($lichKhoiHanhId === null) {
            $lichKhoiHanhId = self::getLichKhoiHanhIdByBookingId($bookingId);
        }

        $params = [];
        $conditionLkh = '';
        if ($lichKhoiHanhId !== null) {
            $conditionLkh = 'AND dd.lich_khoi_hanh_id = ?';
            $params[] = $lichKhoiHanhId;
        }
        $params[] = $bookingId;

        $sql = "
            SELECT 
                dd.id,
                bk.id AS booking_khach_id,
                dd.lich_khoi_hanh_id,
                dd.trang_thai,
                dd.ghi_chu,
                dd.ngay_gio
            FROM booking_khach bk
            LEFT JOIN diem_danh_khach dd
                ON dd.booking_khach_id = bk.id
                {$conditionLkh}
            WHERE bk.booking_id = ?
            ORDER BY bk.id ASC, dd.id DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getLatestLichKhoiHanh($bookingId)
    {
        $pdo = getDB();
        if (!$pdo) return null;
        $stmt = $pdo->prepare("
            SELECT id, booking_id, ngay_gio_xuat_phat, diem_tap_trung, thoi_gian_ket_thuc, ghi_chu AS lich_ghi_chu
            FROM lich_khoi_hanh
            WHERE booking_id = ?
            ORDER BY id DESC
            LIMIT 1
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Thêm hoặc cập nhật lịch khởi hành cho booking.
     */
    public static function upsertLichKhoiHanh($bookingId, $ngayGioXuatPhat = null, $diemTapTrung = null, $thoiGianKetThuc = null, $ghiChu = null)
    {
        $pdo = getDB();
        if (!$pdo) {
            return false;
        }

        $select = $pdo->prepare("SELECT id FROM lich_khoi_hanh WHERE booking_id = ? ORDER BY id DESC LIMIT 1");
        $select->execute([$bookingId]);
        $row = $select->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['id'])) {
            $stmt = $pdo->prepare("
                UPDATE lich_khoi_hanh
                SET ngay_gio_xuat_phat = ?, diem_tap_trung = ?, thoi_gian_ket_thuc = ?, ghi_chu = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $ngayGioXuatPhat ?: null,
                $diemTapTrung ?: null,
                $thoiGianKetThuc ?: null,
                $ghiChu ?: null,
                $row['id']
            ]);
        }

        $stmt = $pdo->prepare("
            INSERT INTO lich_khoi_hanh (booking_id, ngay_gio_xuat_phat, diem_tap_trung, thoi_gian_ket_thuc, ghi_chu)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $bookingId,
            $ngayGioXuatPhat ?: null,
            $diemTapTrung ?: null,
            $thoiGianKetThuc ?: null,
            $ghiChu ?: null
        ]);
    }
}
?>
