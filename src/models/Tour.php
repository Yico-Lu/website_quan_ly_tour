<?php
    class Tour
    {
        // Các thuộc tính của tour
        public $id;
        public $danh_muc_id;
        public $ten_danh_muc;
        public $ten_tour;
        public $mo_ta;
        public $gia;
        public $trang_thai;
        public $ngay_tao;
        public $ngay_cap_nhat;
        public $anh_tour;

        // Constructor để khởi tạo thực thể Tour
        public function __construct($data = [])
        {
            // Nếu truyền vào mảng dữ liệu thì gán vào các thuộc tính
            if (is_array($data)) {
                $this->id = $data['id'] ?? null;
                $this->danh_muc_id = $data['danh_muc_id'] ?? null;
                $this->ten_danh_muc = $data['ten_danh_muc'] ?? '';
                $this->ten_tour = $data['ten_tour'] ?? '';
                $this->mo_ta = $data['mo_ta'] ?? '';
                $this->gia = $data['gia'] ?? 0;
                $this->trang_thai = $data['trang_thai'] ?? 1;
                $this->ngay_tao = $data['ngay_tao'] ?? date('Y-m-d H:i:s');
                $this->ngay_cap_nhat = $data['ngay_cap_nhat'] ?? date('Y-m-d H:i:s');
                $this->anh_tour = $data['anh_tour'] ?? '';
            } 
        }

        //lấy danh sách danh mục tour
        public static function getDanhMucList()
        {
            $pdo = getDB();
            $sql = "SELECT * FROM danh_muc_tour WHERE trang_thai = 1 ORDER BY ten_danh_muc";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        //hien thi danh sach tour
        public static function getAll()
        {
            $pdo = getDB();
            $sql = "SELECT t.*, dm.ten_danh_muc
                    FROM tour t
                    LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                    ORDER BY t.ngay_tao DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $tours = [];
            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                $tours[] = new Tour($row);
            }
            return $tours;
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

        //format gia tien
        public function formatGia()
        {
            return number_format($this->gia, 0, ',', '.') . ' VND';
        }

        //xoá tour theo ID
        public static function delete($id)
        {
            $pdo = getDB();
            // kiểm tra xem tour có đang được đặt không
            $sqlCheckBooking = "SELECT COUNT(*) FROM booking WHERE tour_id = ?";
            $stmtCheckBooking = $pdo->prepare($sqlCheckBooking);
            $stmtCheckBooking->execute([$id]);

            if($stmtCheckBooking->fetchColumn() > 0){
                return false; // Không thể xóa vì tour đang được đặt
            }
           
            // kiểm tra xem tour có trong báo cáo không
            $sqlCheckBaoCao = "SELECT COUNT(*) FROM bao_cao_tong_hop_tour WHERE tour_id = ?";
            $stmtCheckBaoCao = $pdo->prepare($sqlCheckBaoCao);
            $stmtCheckBaoCao->execute([$id]);

            if($stmtCheckBaoCao->fetchColumn() > 0){
                return false; // Không thể xóa vì có trong báo cáo
            }

            $sql = "DELETE FROM tour WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        }

        //lưu tour mới vào db
        public static function save(Tour $tour)
        {
            $pdo = getDB();
            $sql = "INSERT INTO tour (danh_muc_id, ten_tour, mo_ta, gia, trang_thai, anh_tour, ngay_tao, ngay_cap_nhat)
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $tour->danh_muc_id,
                $tour->ten_tour,
                $tour->mo_ta,
                $tour->gia,
                $tour->trang_thai,
                $tour->anh_tour
            ]);
        }
        
        //lấy tour theo ID
        public static function find($id)
        {
            $pdo = getDB();
            $sql = "SELECT t.*, dm.ten_danh_muc
                    FROM tour t
                    LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                    WHERE t.id = ? LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new Tour($data) : null;
        }

        //cập nhật tour
        public static function update(Tour $tour)
        {
            $pdo = getDB();
            $sql = "UPDATE tour SET
                        danh_muc_id = ?,
                        ten_tour = ?,
                        mo_ta = ?,
                        gia = ?,
                        trang_thai = ?,
                        anh_tour = ?,
                        ngay_cap_nhat = NOW()
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $tour->danh_muc_id,
                $tour->ten_tour,
                $tour->mo_ta,
                $tour->gia,
                $tour->trang_thai,
                $tour->anh_tour,
                $tour->id
            ]);
        }


    }
?>