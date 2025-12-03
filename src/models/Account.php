<?php

// Model Account đại diện cho tài khoản trong hệ thống
class Account
{
    public $id;
    public $ten_dang_nhap;
    public $mat_khau;
    public $ho_ten;
    public $email;
    public $sdt;
    public $phan_quyen;
    public $trang_thai;
    public $ngay_tao;
    public $ngay_cap_nhat;

    // Thông tin bổ sung cho HDV
    public $ngay_sinh;
    public $anh_dai_dien;
    public $lien_he;
    public $nhom;
    public $chuyen_mon;

    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->ten_dang_nhap = $data['ten_dang_nhap'] ?? '';
            $this->mat_khau = $data['mat_khau'] ?? '';
            $this->ho_ten = $data['ho_ten'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->sdt = $data['sdt'] ?? '';
            $this->phan_quyen = $data['phan_quyen'] ?? 'hdv';
            $this->trang_thai = $data['trang_thai'] ?? 'hoat_dong';
            $this->ngay_tao = $data['ngay_tao'] ?? null;
            $this->ngay_cap_nhat = $data['ngay_cap_nhat'] ?? null;

            // Thông tin HDV
            $this->ngay_sinh = $data['ngay_sinh'] ?? null;
            $this->anh_dai_dien = $data['anh_dai_dien'] ?? '';
            $this->lien_he = $data['lien_he'] ?? '';
            $this->nhom = $data['nhom'] ?? '';
            $this->chuyen_mon = $data['chuyen_mon'] ?? '';
        }
    }

    // Lấy tất cả tài khoản
    public static function all()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT t.*, 
                   MAX(h.ngay_sinh) as ngay_sinh, 
                   MAX(h.anh_dai_dien) as anh_dai_dien, 
                   MAX(h.lien_he) as lien_he, 
                   MAX(h.nhom) as nhom, 
                   MAX(h.chuyen_mon) as chuyen_mon
            FROM tai_khoan t
            LEFT JOIN hdv h ON t.id = h.tai_khoan_id
            GROUP BY t.id
            ORDER BY t.ngay_tao DESC
        ");
        $accounts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $accounts[] = new self($row);
        }
        return $accounts;
    }

    // Lấy tài khoản theo ID
    public static function find($id)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT t.*, 
                   MAX(h.ngay_sinh) as ngay_sinh, 
                   MAX(h.anh_dai_dien) as anh_dai_dien, 
                   MAX(h.lien_he) as lien_he, 
                   MAX(h.nhom) as nhom, 
                   MAX(h.chuyen_mon) as chuyen_mon
            FROM tai_khoan t
            LEFT JOIN hdv h ON t.id = h.tai_khoan_id
            WHERE t.id = ?
            GROUP BY t.id
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new self($data) : null;
    }

    // Lấy tài khoản theo tên đăng nhập
    public static function findByUsername($username)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT t.*, 
                   MAX(h.ngay_sinh) as ngay_sinh, 
                   MAX(h.anh_dai_dien) as anh_dai_dien, 
                   MAX(h.lien_he) as lien_he, 
                   MAX(h.nhom) as nhom, 
                   MAX(h.chuyen_mon) as chuyen_mon
            FROM tai_khoan t
            LEFT JOIN hdv h ON t.id = h.tai_khoan_id
            WHERE t.ten_dang_nhap = ?
            GROUP BY t.id
            LIMIT 1
        ");
        $stmt->execute([$username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new self($data) : null;
    }

    // Lấy tài khoản theo email
    public static function findByEmail($email)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT t.*, 
                   MAX(h.ngay_sinh) as ngay_sinh, 
                   MAX(h.anh_dai_dien) as anh_dai_dien, 
                   MAX(h.lien_he) as lien_he, 
                   MAX(h.nhom) as nhom, 
                   MAX(h.chuyen_mon) as chuyen_mon
            FROM tai_khoan t
            LEFT JOIN hdv h ON t.id = h.tai_khoan_id
            WHERE t.email = ?
            GROUP BY t.id
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new self($data) : null;
    }

    // Lấy danh sách admin
    public static function getAdmins()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT t.*, 
                   MAX(h.ngay_sinh) as ngay_sinh, 
                   MAX(h.anh_dai_dien) as anh_dai_dien, 
                   MAX(h.lien_he) as lien_he, 
                   MAX(h.nhom) as nhom, 
                   MAX(h.chuyen_mon) as chuyen_mon
            FROM tai_khoan t
            LEFT JOIN hdv h ON t.id = h.tai_khoan_id
            WHERE t.phan_quyen = 'admin'
            GROUP BY t.id
            ORDER BY t.ngay_tao DESC
        ");
        $accounts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $accounts[] = new self($row);
        }
        return $accounts;
    }

    // Lấy danh sách hướng dẫn viên
    public static function getGuides()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT t.*, 
                   MAX(h.ngay_sinh) as ngay_sinh, 
                   MAX(h.anh_dai_dien) as anh_dai_dien, 
                   MAX(h.lien_he) as lien_he, 
                   MAX(h.nhom) as nhom, 
                   MAX(h.chuyen_mon) as chuyen_mon
            FROM tai_khoan t
            LEFT JOIN hdv h ON t.id = h.tai_khoan_id
            WHERE t.phan_quyen = 'hdv'
            GROUP BY t.id
            ORDER BY t.ngay_tao DESC
        ");
        $accounts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $accounts[] = new self($row);
        }
        return $accounts;
    }

    // Lưu tài khoản (tạo mới hoặc cập nhật)
    public function save()
    {
        $db = getDB();

        if ($this->id) {
            // Cập nhật
            $stmt = $db->prepare("
                UPDATE tai_khoan SET
                    ten_dang_nhap = ?,
                    ho_ten = ?,
                    email = ?,
                    sdt = ?,
                    phan_quyen = ?,
                    trang_thai = ?,
                    ngay_cap_nhat = NOW()
                WHERE id = ?
            ");
            $result = $stmt->execute([
                $this->ten_dang_nhap,
                $this->ho_ten,
                $this->email,
                $this->sdt,
                $this->phan_quyen,
                $this->trang_thai,
                $this->id
            ]);

            if ($result && $this->phan_quyen === 'hdv') {
                $this->saveGuideInfo();
            }

            return $result;
        } else {
            // Tạo mới
            $stmt = $db->prepare("
                INSERT INTO tai_khoan
                    (ten_dang_nhap, mat_khau, ho_ten, email, sdt, phan_quyen, trang_thai, ngay_tao, ngay_cap_nhat)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $result = $stmt->execute([
                $this->ten_dang_nhap,
                $this->mat_khau,
                $this->ho_ten,
                $this->email,
                $this->sdt,
                $this->phan_quyen,
                $this->trang_thai
            ]);

            if ($result) {
                $this->id = $db->lastInsertId();

                if ($this->phan_quyen === 'hdv') {
                    $this->saveGuideInfo();
                }
            }

            return $result;
        }
    }

    // Lưu thông tin hướng dẫn viên
    private function saveGuideInfo()
    {
        $db = getDB();

        // Kiểm tra xem đã có thông tin HDV chưa
        $stmt = $db->prepare("SELECT id FROM hdv WHERE tai_khoan_id = ?");
        $stmt->execute([$this->id]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // Cập nhật
            $stmt = $db->prepare("
                UPDATE hdv SET
                    ngay_sinh = ?,
                    anh_dai_dien = ?,
                    lien_he = ?,
                    nhom = ?,
                    chuyen_mon = ?
                WHERE tai_khoan_id = ?
            ");
            $stmt->execute([
                $this->ngay_sinh,
                $this->anh_dai_dien,
                $this->lien_he,
                $this->nhom,
                $this->chuyen_mon,
                $this->id
            ]);
        } else {
            // Tạo mới
            $stmt = $db->prepare("
                INSERT INTO hdv
                    (tai_khoan_id, ngay_sinh, anh_dai_dien, lien_he, nhom, chuyen_mon, ngay_tao)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $this->id,
                $this->ngay_sinh,
                $this->anh_dai_dien,
                $this->lien_he,
                $this->nhom,
                $this->chuyen_mon
            ]);
        }
    }

    // Xóa tài khoản
    public function delete()
    {
        if (!$this->id) {
            return false;
        }

        $db = getDB();

        try {
            // Bắt đầu transaction
            $db->beginTransaction();

            $currentUser = getCurrentUser();

            // Admin có thể xóa tất cả, HDV chỉ có thể xóa tài khoản không có booking
            if (!$currentUser->isAdmin() && $this->phan_quyen === 'hdv') {
                // Kiểm tra xem tài khoản HDV có đang được sử dụng trong booking không
                $stmt = $db->prepare("SELECT COUNT(*) as count FROM booking WHERE tai_khoan_id = ? OR assigned_hdv_id = ?");
                $stmt->execute([$this->id, $this->id]);
                $bookingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

                if ($bookingCount > 0) {
                    // Không cho xóa nếu tài khoản có booking
                    $db->rollBack();
                    return false;
                }
            }

            // Xóa các bản ghi trong booking_nhat_ky_log trước
            $stmt = $db->prepare("DELETE FROM booking_nhat_ky_log WHERE tai_khoan_id = ?");
            $stmt->execute([$this->id]);

            // Xóa thông tin HDV (nếu có)
            if ($this->phan_quyen === 'hdv') {
                $stmt = $db->prepare("DELETE FROM hdv WHERE tai_khoan_id = ?");
                $stmt->execute([$this->id]);
            }

            // Xóa tài khoản
            $stmt = $db->prepare("DELETE FROM tai_khoan WHERE id = ?");
            $result = $stmt->execute([$this->id]);

            // Commit transaction
            $db->commit();

            return $result;

        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $db->rollBack();
            return false;
        }
    }

    // Đổi mật khẩu
    public function changePassword($newPassword)
    {
        if (!$this->id) {
            return false;
        }

        $db = getDB();
        $stmt = $db->prepare("
            UPDATE tai_khoan SET
                mat_khau = ?,
                ngay_cap_nhat = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$newPassword, $this->id]);
    }

    // Kiểm tra mật khẩu
    public function verifyPassword($password)
    {
        return password_verify($password, $this->mat_khau);
    }

    // Hash mật khẩu
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Kiểm tra xem có phải admin không
    public function isAdmin()
    {
        return $this->phan_quyen === 'admin';
    }

    // Kiểm tra xem có phải HDV không
    public function isGuide()
    {
        return $this->phan_quyen === 'hdv';
    }

    // Lấy tên hiển thị
    public function getDisplayName()
    {
        return $this->ho_ten ?: $this->ten_dang_nhap;
    }

    // Lấy trạng thái hiển thị
    public function getStatusText()
    {
        return match($this->trang_thai) {
            'hoat_dong' => 'Hoạt động',
            'ngung' => 'Ngưng hoạt động',
            default => 'Không xác định'
        };
    }

    // Lấy màu trạng thái cho hiển thị
    public function getStatusColor()
    {
        return match($this->trang_thai) {
            'hoat_dong' => 'success',
            'ngung' => 'danger',
            default => 'secondary'
        };
    }

    // Lấy tên vai trò
    public function getRoleText()
    {
        return match($this->phan_quyen) {
            'admin' => 'Quản trị viên',
            'hdv' => 'Hướng dẫn viên',
            default => 'Không xác định'
        };
    }

    // Lấy icon vai trò
    public function getRoleIcon()
    {
        return match($this->phan_quyen) {
            'admin' => 'bi-shield-check',
            'hdv' => 'bi-person-badge',
            default => 'bi-question-circle'
        };
    }

    // Kiểm tra xem tài khoản có thể xóa được không
    public function canDelete()
    {
        if (!$this->id) {
            return false;
        }

        // Không cho xóa tài khoản admin hiện tại
        $currentUser = getCurrentUser();
        if ($this->id == $currentUser->id) {
            return false;
        }

        // Admin có thể xóa tất cả, HDV chỉ có thể xóa nếu không có booking
        if ($currentUser->isAdmin()) {
            return true; // Admin có thể xóa bất kỳ tài khoản nào
        }

        // HDV chỉ có thể xóa tài khoản của chính mình nếu không có booking
        if ($this->phan_quyen === 'hdv') {
            $db = getDB();
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM booking WHERE tai_khoan_id = ? OR assigned_hdv_id = ?");
            $stmt->execute([$this->id, $this->id]);
            $bookingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            return $bookingCount == 0;
        }

        return false;
    }

    // Lấy lý do không thể xóa
    public function getDeleteReason()
    {
        if (!$this->id) {
            return 'Tài khoản không tồn tại';
        }

        $currentUser = getCurrentUser();
        if ($this->id == $currentUser->id) {
            return 'Không thể xóa tài khoản đang đăng nhập';
        }

        // Nếu là admin, có thể xóa tất cả
        if ($currentUser->isAdmin()) {
            return '';
        }

        // HDV kiểm tra booking
        if ($this->phan_quyen === 'hdv') {
            $db = getDB();
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM booking WHERE tai_khoan_id = ? OR assigned_hdv_id = ?");
            $stmt->execute([$this->id, $this->id]);
            $bookingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            if ($bookingCount > 0) {
                return 'Tài khoản đang có dữ liệu booking liên quan';
            }
        }

        return '';
    }
}
