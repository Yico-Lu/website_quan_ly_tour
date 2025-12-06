<?php
class HDV
{
    // Các thuộc tính của HDV
    public $id;
    public $tai_khoan_id;
    public $ngay_sinh;
    public $anh_dai_dien;
    public $lien_he;
    public $nhom;
    public $chuyen_mon;
    public $ngay_tao;
    
    // Thông tin từ bảng tai_khoan (join)
    public $ho_ten;
    public $email;
    public $trang_thai;

    // Constructor để khởi tạo thực thể HDV
    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->tai_khoan_id = $data['tai_khoan_id'] ?? null;
            $this->ngay_sinh = $data['ngay_sinh'] ?? null;
            $this->anh_dai_dien = $data['anh_dai_dien'] ?? null;
            $this->lien_he = $data['lien_he'] ?? null;
            $this->nhom = $data['nhom'] ?? '';
            $this->chuyen_mon = $data['chuyen_mon'] ?? '';
            $this->ngay_tao = $data['ngay_tao'] ?? date('Y-m-d H:i:s');
            
            // Thông tin từ tai_khoan
            $this->ho_ten = $data['ho_ten'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->trang_thai = $data['trang_thai'] ?? 'hoat_dong';
        }
    }

    // Lấy danh sách tất cả HDV
    public static function getAll()
    {
        $pdo = getDB();
        $sql = "SELECT h.id, h.tai_khoan_id, h.ngay_sinh, h.anh_dai_dien, h.lien_he, h.nhom, h.chuyen_mon, h.ngay_tao,
                       tk.ho_ten, tk.email, tk.trang_thai 
                FROM hdv h 
                INNER JOIN tai_khoan tk ON h.tai_khoan_id = tk.id 
                ORDER BY h.ngay_tao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $hdvs = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $hdvs[] = new HDV($row);
        }
        return $hdvs;
    }

    // Lấy danh sách HDV đang hoạt động
    public static function getActiveList()
    {
        $pdo = getDB();
        $sql = "SELECT h.id, h.tai_khoan_id, h.ngay_sinh, h.anh_dai_dien, h.lien_he, h.nhom, h.chuyen_mon, h.ngay_tao,
                       tk.ho_ten, tk.email, tk.trang_thai 
                FROM hdv h 
                INNER JOIN tai_khoan tk ON h.tai_khoan_id = tk.id 
                WHERE tk.trang_thai = 'hoat_dong' 
                ORDER BY tk.ho_ten";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $hdvs = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $hdvs[] = new HDV($row);
        }
        return $hdvs;
    }

    // Tìm HDV theo ID
    public static function find($id)
    {
        $pdo = getDB();
        $sql = "SELECT h.id, h.tai_khoan_id, h.ngay_sinh, h.anh_dai_dien, h.lien_he, h.nhom, h.chuyen_mon, h.ngay_tao,
                       tk.ho_ten, tk.email, tk.trang_thai 
                FROM hdv h 
                INNER JOIN tai_khoan tk ON h.tai_khoan_id = tk.id 
                WHERE h.id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$data) return null;

        return new HDV($data);
    }

    // Tìm HDV theo tai_khoan_id
    public static function findByTaiKhoanId($tai_khoan_id)
    {
        $pdo = getDB();
        $sql = "SELECT h.id, h.tai_khoan_id, h.ngay_sinh, h.anh_dai_dien, h.lien_he, h.nhom, h.chuyen_mon, h.ngay_tao,
                       tk.ho_ten, tk.email, tk.trang_thai 
                FROM hdv h 
                INNER JOIN tai_khoan tk ON h.tai_khoan_id = tk.id 
                WHERE h.tai_khoan_id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tai_khoan_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$data) return null;

        return new HDV($data);
    }

    // Tạo HDV mới
    public static function create(HDV $hdv)
    {
        $pdo = getDB();

        // Kiểm tra tai_khoan_id đã có HDV chưa
        $sqlCheck = "SELECT COUNT(*) FROM hdv WHERE tai_khoan_id = ?";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$hdv->tai_khoan_id]);
        if($stmtCheck->fetchColumn() > 0){
            return false; // Tài khoản đã có HDV
        }

        // Kiểm tra tài khoản có phải là HDV không
        $sqlCheckAccount = "SELECT phan_quyen FROM tai_khoan WHERE id = ?";
        $stmtCheckAccount = $pdo->prepare($sqlCheckAccount);
        $stmtCheckAccount->execute([$hdv->tai_khoan_id]);
        $account = $stmtCheckAccount->fetch(PDO::FETCH_ASSOC);
        if(!$account || $account['phan_quyen'] !== 'hdv'){
            return false; // Tài khoản không phải là HDV
        }

        $sql = "INSERT INTO hdv (tai_khoan_id, ngay_sinh, anh_dai_dien, lien_he, nhom, chuyen_mon, ngay_tao)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            $hdv->tai_khoan_id,
            $hdv->ngay_sinh ?: null,
            $hdv->anh_dai_dien ?: null,
            $hdv->lien_he ?: null,
            $hdv->nhom,
            $hdv->chuyen_mon
        ]);
    }

    // Cập nhật HDV
    public static function update(HDV $hdv)
    {
        $pdo = getDB();

        $sql = "UPDATE hdv SET
                    ngay_sinh = ?,
                    anh_dai_dien = ?,
                    lien_he = ?,
                    nhom = ?,
                    chuyen_mon = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            $hdv->ngay_sinh ?: null,
            $hdv->anh_dai_dien ?: null,
            $hdv->lien_he ?: null,
            $hdv->nhom,
            $hdv->chuyen_mon,
            $hdv->id
        ]);
    }

    // Xóa HDV
    public static function delete($id)
    {
        $pdo = getDB();

        try {
            $sql = "DELETE FROM hdv WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log('Cannot delete HDV: ' . $e->getMessage());
            return false;
        }
    }

    // Lấy tên nhóm
    public function getNhomName()
    {
        $nhomNames = [
            'noi_dia' => 'Nội địa',
            'quoc_te' => 'Quốc tế',
            'yeu_cau' => 'Theo yêu cầu'
        ];
        return $nhomNames[$this->nhom] ?? $this->nhom;
    }

    // Lấy badge class cho nhóm
    public function getNhomBadgeClass()
    {
        $badgeClasses = [
            'noi_dia' => 'bg-primary',
            'quoc_te' => 'bg-info',
            'yeu_cau' => 'bg-warning'
        ];
        return $badgeClasses[$this->nhom] ?? 'bg-secondary';
    }

    // Lấy tên trạng thái
    public function getTrangThaiName()
    {
        return $this->trang_thai === 'hoat_dong' ? 'Hoạt động' : 'Tạm ngưng';
    }

    // Lấy badge class cho trạng thái
    public function getTrangThaiBadgeClass()
    {
        return $this->trang_thai === 'hoat_dong' ? 'bg-success' : 'bg-warning';
    }

    // Lấy danh sách tài khoản HDV chưa có thông tin chi tiết
    public static function getAvailableAccounts()
    {
        $pdo = getDB();
        $sql = "SELECT tk.id, tk.ho_ten, tk.email 
                FROM tai_khoan tk 
                WHERE tk.phan_quyen = 'hdv' 
                AND tk.id NOT IN (SELECT tai_khoan_id FROM hdv WHERE tai_khoan_id IS NOT NULL)
                AND tk.trang_thai = 'hoat_dong'
                ORDER BY tk.ho_ten";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy số lượng booking của HDV
    public function getSoLuongBooking()
    {
        $pdo = getDB();
        $sql = "SELECT COUNT(*) FROM booking_hdv WHERE hdv_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->tai_khoan_id]);
        return $stmt->fetchColumn();
    }
}

