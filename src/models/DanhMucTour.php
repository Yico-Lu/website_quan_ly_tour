<?php
class DanhMucTour
{
    // Các thuộc tính của danh mục tour
    public $id;
    public $ten_danh_muc;
    public $mo_ta;
    public $ngay_tao;
    public $ngay_cap_nhat;
    public $trang_thai;

    // Constructor để khởi tạo thực thể DanhMucTour
    public function __construct($data = [])
    {
        // Nếu truyền vào mảng dữ liệu thì gán vào các thuộc tính
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->ten_danh_muc = $data['ten_danh_muc'] ?? '';
            $this->mo_ta = $data['mo_ta'] ?? '';
            $this->ngay_tao = $data['ngay_tao'] ?? date('Y-m-d H:i:s');
            $this->ngay_cap_nhat = $data['ngay_cap_nhat'] ?? date('Y-m-d H:i:s');
            $this->trang_thai = $data['trang_thai'] ?? 1;
        }
    }

    // Lấy danh sách tất cả danh mục tour
    public static function getAll()
    {
        $pdo = getDB();
        $sql = "SELECT * FROM danh_muc_tour ORDER BY ngay_tao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $danhMucs = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $danhMucs[] = new DanhMucTour($row);
        }
        return $danhMucs;
    }

    // Lấy danh sách danh mục tour đang hoạt động
    public static function getActiveList()
    {
        $pdo = getDB();
        $sql = "SELECT * FROM danh_muc_tour WHERE trang_thai = 1 ORDER BY ten_danh_muc";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $danhMucs = [];
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
            $danhMucs[] = new DanhMucTour($row);
        }
        return $danhMucs;
    }

    // Tìm danh mục tour theo ID
    public static function find($id)
    {
        $pdo = getDB();
        $sql = "SELECT * FROM danh_muc_tour WHERE id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new DanhMucTour($data) : null;
    }

    // Tạo danh mục tour mới
    public static function create(DanhMucTour $danhMuc)
    {
        $pdo = getDB();
        $sql = "INSERT INTO danh_muc_tour (ten_danh_muc, mo_ta, trang_thai, ngay_tao, ngay_cap_nhat)
                VALUES (?, ?, ?, NOW(), NOW())";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $danhMuc->ten_danh_muc,
            $danhMuc->mo_ta,
            $danhMuc->trang_thai
        ]);
    }

    // Cập nhật danh mục tour
    public static function update(DanhMucTour $danhMuc)
    {
        $pdo = getDB();
        $sql = "UPDATE danh_muc_tour SET
                        ten_danh_muc = ?,
                        mo_ta = ?,
                        trang_thai = ?,
                        ngay_cap_nhat = NOW()
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $danhMuc->ten_danh_muc,
            $danhMuc->mo_ta,
            $danhMuc->trang_thai,
            $danhMuc->id
        ]);
    }

    // Xóa danh mục tour
    public static function delete($id)
    {
        $pdo = getDB();

        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $pdo->beginTransaction();

            // Kiểm tra xem danh mục có đang được sử dụng không
            $sqlCheck = "SELECT COUNT(*) FROM tour WHERE danh_muc_id = ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$id]);

            if ($stmtCheck->fetchColumn() > 0) {
                // Nếu có tour đang sử dụng danh mục này, không cho xóa
                $pdo->rollBack();
                return false;
            }

            // Xóa danh mục
            $sql = "DELETE FROM danh_muc_tour WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$id]);

            // Commit transaction
            $pdo->commit();

            return $result;
        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            $pdo->rollBack();
            error_log('Cannot delete danh muc tour: ' . $e->getMessage());
            return false;
        }
    }

    // Lấy tên trạng thái
    public function getTrangThai()
    {
        return $this->trang_thai == 1 ? 'Hoạt động' : 'Ngưng hoạt động';
    }

    // Lấy class badge cho trạng thái
    public function getTrangThaiBadgeClass()
    {
        return $this->trang_thai == 1 ? 'text-bg-success' : 'text-bg-danger';
    }

    // Đếm số tour trong danh mục này
    public function getSoLuongTour()
    {
        $pdo = getDB();
        $sql = "SELECT COUNT(*) FROM tour WHERE danh_muc_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$this->id]);
        return $stmt->fetchColumn();
    }
}
?>



