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
        public $chinh_sach;      
        public $lich_trinh;      
        public $nha_cung_cap;    
        public $anh_tour_chi_tiet; 

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
                $this->chinh_sach = $data['chinh_sach'] ?? [];
                $this->lich_trinh = $data['lich_trinh'] ?? [];
                $this->nha_cung_cap = $data['nha_cung_cap'] ?? [];
                $this->anh_tour_chi_tiet = $data['anh_tour_chi_tiet'] ?? [];
            }
        }

        //lấy danh sách danh mục tour
        public static function getDanhMucList()
        {
            $pdo = getDB();
            $sql = "SELECT * FROM danh_muc_tour WHERE trang_thai = 1 ORDER BY ten_danh_muc";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }


        //hien thi danh sach tour
        public static function getAll($loadRelated = false)
        {
            $pdo = getDB();
            $sql = "SELECT t.*, dm.ten_danh_muc
                    FROM tour t
                    LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                    ORDER BY t.ngay_tao DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $tours = [];

            foreach($stmt->fetchAll() as $row){
                $tour = new Tour($row);

                // Load dữ liệu liên quan nếu được yêu cầu
                if ($loadRelated) {
                    $tour->loadRelatedData();
                }

                $tours[] = $tour;
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
        //Lưu ý: Chỉ lưu thông tin cơ bản của tour. Dữ liệu liên quan (chính sách, lịch trình, NCC)
        //cần được thêm riêng bằng các phương thức addChinhSach, addLichTrinh, addNhaCungCap
        public static function save(Tour $tour)
        {
            $pdo = getDB();
            $sql = "INSERT INTO tour (danh_muc_id, ten_tour, mo_ta, gia, trang_thai, anh_tour, ngay_tao, ngay_cap_nhat)
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);
            if($stmt->execute([
                $tour->danh_muc_id,
                $tour->ten_tour,
                $tour->mo_ta,
                $tour->gia,
                $tour->trang_thai,
                $tour->anh_tour
            ])){
                // Trả về ID của tour vừa tạo
                return $pdo->lastInsertId();
            }
            return false;
        }
        
        //lấy tour theo ID
        public static function find($id, $loadRelated = false)
        {
            $pdo = getDB();
            $sql = "SELECT t.*, dm.ten_danh_muc
                    FROM tour t
                    LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                    WHERE t.id = ? LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch();

            if (!$data) {
                return null;
            }

            $tour = new Tour($data);

            // Load dữ liệu liên quan nếu được yêu cầu
            if ($loadRelated) {
                $tour->loadRelatedData();
            }

            return $tour;
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

        //lấy danh sách chính sách của tour
        public function getChinhSach()
        {
            if (!empty($this->chinh_sach)) {
                return $this->chinh_sach;
            }

            $pdo = getDB();
            $sql = "SELECT * FROM tour_chinh_sach WHERE tour_id = ? ORDER BY ngay_tao";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->id]);
            $this->chinh_sach = $stmt->fetchAll();
            return $this->chinh_sach;
        }

        //lấy lịch trình tour
        public function getLichTrinh()
        {
            if (!empty($this->lich_trinh)) {
                return $this->lich_trinh;
            }

            $pdo = getDB();
            $sql = "SELECT * FROM tour_lich_trinh WHERE tour_id = ? ORDER BY ngay ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->id]);
            $this->lich_trinh = $stmt->fetchAll();
            return $this->lich_trinh;
        }

        //lấy danh sách nhà cung cấp
        public function getNhaCungCap()
        {
            if (!empty($this->nha_cung_cap)) {
                return $this->nha_cung_cap;
            }

            $pdo = getDB();
            $sql = "SELECT * FROM tour_nha_cung_cap WHERE tour_id = ? ORDER BY ngay_tao";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->id]);
            $this->nha_cung_cap = $stmt->fetchAll();
            return $this->nha_cung_cap;
        }

        //lấy danh sách ảnh chi tiết
        public function getAnhChiTiet()
        {
            if (!empty($this->anh_tour_chi_tiet)) {
                return $this->anh_tour_chi_tiet;
            }

            $pdo = getDB();
            $sql = "SELECT * FROM tour_anh WHERE tour_id = ? ORDER BY ngay_tao";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$this->id]);
            $this->anh_tour_chi_tiet = $stmt->fetchAll();
            return $this->anh_tour_chi_tiet;
        }

        //load tất cả dữ liệu liên quan
        public function loadRelatedData()
        {
            $this->getChinhSach();
            $this->getLichTrinh();
            $this->getNhaCungCap();
            $this->getAnhChiTiet();
        }

        //lấy tour với tất cả thông tin liên quan (JOIN tất cả bảng)
        public static function getTourWithDetails($id)
        {
            return self::find($id, true); // true = load related data
        }

        //lưu lịch trình cho tour
        public static function saveLichTrinh($tour_id, $lich_trinh_text)
        {
            if (!empty(trim($lich_trinh_text))) {
                $pdo = getDB();
                $sql = "INSERT INTO tour_lich_trinh (tour_id, ngay, diem_tham_quan, hoat_dong)
                        VALUES (?, 1, 'Lịch trình', ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$tour_id, trim($lich_trinh_text)]);
            }
        }

        //lưu ảnh chi tiết cho tour
        public static function saveAnhChiTiet($tour_id, $duong_dan)
        {
            if (!empty(trim($duong_dan))){
                $pdo = getDB();
                $sql = "INSERT INTO tour_anh (tour_id, duong_dan, ngay_tao)
                        VALUES (?, ?, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$tour_id, trim($duong_dan)]);
            }
        }

        //quản lý chi tiết ảnh
        public static function addAnhChiTiet($tour_id, $duong_dan)
        {
            $pdo = getDB();
            $sql = "INSERT INTO tour_anh (tour_id, duong_dan, ngay_tao)
                    VALUES (?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$tour_id, $duong_dan]);
        }

        public static function deleteAnhChiTiet($id)
        {
            $pdo = getDB();
            $sql = "DELETE FROM tour_anh WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        }


    }
?>
