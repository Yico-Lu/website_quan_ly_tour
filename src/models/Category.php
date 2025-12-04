<?php
    class Category
    {
        // Các thuộc tính của danh mục tour
        public $id;
        public $ten_danh_muc;
        public $mo_ta;
        public $trang_thai;
        public $ngay_tao;
        public $ngay_cap_nhat;

        // Constructor để khởi tạo thực thể Category
        public function __construct($data = [])
        {
            // Nếu truyền vào mảng dữ liệu thì gán vào các thuộc tính
            if (is_array($data)) {
                $this->id = $data['id'] ?? null;
                $this->ten_danh_muc = $data['ten_danh_muc'] ?? '';
                $this->mo_ta = $data['mo_ta'] ?? '';
                $this->trang_thai = $data['trang_thai'] ?? 1;
                $this->ngay_tao = $data['ngay_tao'] ?? date('Y-m-d H:i:s');
                $this->ngay_cap_nhat = $data['ngay_cap_nhat'] ?? date('Y-m-d H:i:s');
            }
        }

        //hien thi danh sach danh mục
        public static function getAll()
        {
            $pdo = getDB();
            $sql = "SELECT * FROM danh_muc_tour ORDER BY ngay_tao DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $categories = [];
            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                $categories[] = new Category($row);
            }
            return $categories;
        }

        //  lấy tên trạng thái
        public function getTrangThai()
        {
            return $this->trang_thai == 1 ? 'Hoạt động' : 'Ngưng hoạt động';
        }

        // lấy badge class cho trạng thái
        public function getTrangThaiBadgeClass()
        {
            return $this->trang_thai == 1 ? 'text-bg-success' : 'text-bg-danger';
        }

        //xoá danh mục theo ID
        public static function delete($id)
        {
            $pdo = getDB();

            // kiểm tra xem danh mục có đang được sử dụng trong tour không
            $sqlCheckTour = "SELECT COUNT(*) FROM tour WHERE danh_muc_id = ?";
            $stmtCheckTour = $pdo->prepare($sqlCheckTour);
            $stmtCheckTour->execute([$id]);

            if($stmtCheckTour->fetchColumn() > 0){
                return false; // Không thể xóa vì danh mục đang được sử dụng
            }

            $sql = "DELETE FROM danh_muc_tour WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        }

        //lưu danh mục mới vào db
        public static function save(Category $category)
        {
            $pdo = getDB();
            $sql = "INSERT INTO danh_muc_tour (ten_danh_muc, mo_ta, trang_thai, ngay_tao, ngay_cap_nhat)
                    VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $category->ten_danh_muc,
                $category->mo_ta,
                $category->trang_thai
            ]);
        }

        //lấy danh mục theo ID
        public static function find($id)
        {
            $pdo = getDB();
            $sql = "SELECT * FROM danh_muc_tour WHERE id = ? LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new Category($data) : null;
        }

        //cập nhật danh mục
        public static function update(Category $category)
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
                $category->ten_danh_muc,
                $category->mo_ta,
                $category->trang_thai,
                $category->id
            ]);
        }

        //lấy danh sách danh mục đang hoạt động (cho dropdown)
        public static function getActiveList()
        {
            $pdo = getDB();
            $sql = "SELECT * FROM danh_muc_tour WHERE trang_thai = 1 ORDER BY ten_danh_muc";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
