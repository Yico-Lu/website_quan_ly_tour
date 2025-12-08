<?php
    class Tour
    {
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

        public function __construct($data = [])
        {
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

        // Lấy danh sách danh mục tour
        public static function getDanhMucList()
        {
            $pdo = getDB();
            $sql = "SELECT * FROM danh_muc_tour WHERE trang_thai = 1 ORDER BY ten_danh_muc";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }


        // Lấy danh sách tất cả tour
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
                if ($loadRelated) {
                    $tour->loadRelatedData();
                }
                $tours[] = $tour;
            }
            return $tours;
        }

        // Tìm kiếm và lọc tour
        public static function search($keyword = '', $danh_muc_id = '', $trang_thai = '')
        {
            $pdo = getDB();
            $sql = "SELECT t.*, dm.ten_danh_muc
                    FROM tour t
                    LEFT JOIN danh_muc_tour dm ON t.danh_muc_id = dm.id
                    WHERE 1=1";
            
            $params = [];
            
            // Tìm kiếm theo tên tour
            if (!empty($keyword)) {
                $sql .= " AND t.ten_tour LIKE ?";
                $params[] = '%' . $keyword . '%';
            }
            
            // Lọc theo danh mục
            if (!empty($danh_muc_id)) {
                $sql .= " AND t.danh_muc_id = ?";
                $params[] = $danh_muc_id;
            }
            
            // Lọc theo trạng thái
            if ($trang_thai !== '') {
                $sql .= " AND t.trang_thai = ?";
                $params[] = $trang_thai;
            }
            
            $sql .= " ORDER BY t.ngay_tao DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $tours = [];

            foreach($stmt->fetchAll() as $row){
                $tours[] = new Tour($row);
            }
            return $tours;
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

        // Format giá tiền
        public function formatGia()
        {
            return number_format($this->gia, 0, ',', '.') . ' VND';
        }

        // Xóa tour theo ID
        public static function delete($id)
        {
            $pdo = getDB();
            
            // Kiểm tra booking - nếu có booking thì không cho xóa
            $sqlCheckBooking = "SELECT COUNT(*) FROM booking WHERE tour_id = ?";
            $stmtCheckBooking = $pdo->prepare($sqlCheckBooking);
            $stmtCheckBooking->execute([$id]);
            if($stmtCheckBooking->fetchColumn() > 0){
                return false;
            }
           
            // Kiểm tra báo cáo - nếu có báo cáo thì không cho xóa
            $sqlCheckBaoCao = "SELECT COUNT(*) FROM bao_cao_tong_hop_tour WHERE tour_id = ?";
            $stmtCheckBaoCao = $pdo->prepare($sqlCheckBaoCao);
            $stmtCheckBaoCao->execute([$id]);
            if($stmtCheckBaoCao->fetchColumn() > 0){
                return false;
            }

            // Xóa các dữ liệu liên quan trước (phải xóa trước vì có foreign key)
            $pdo->prepare("DELETE FROM tour_anh WHERE tour_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM tour_chinh_sach WHERE tour_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM tour_lich_trinh WHERE tour_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM tour_nha_cung_cap WHERE tour_id = ?")->execute([$id]);

            // Cuối cùng mới xóa tour
            $sql = "DELETE FROM tour WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        }

        // Lưu tour mới vào database
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
                //trả về id tour vừa tạo
                return $pdo->lastInsertId();
            }
            return false;
        }
        
        // Lấy tour theo ID
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
            if ($loadRelated) {
                //load dữ liệu liên quan nếu được yêu cầu
                $tour->loadRelatedData();
            }

            return $tour;
        }

        // Cập nhật tour
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

        // Lấy danh sách chính sách của tour
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

        // Lấy lịch trình tour
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

        // Lấy danh sách nhà cung cấp
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

        // Lấy danh sách ảnh chi tiết
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

        // Load tất cả dữ liệu liên quan
        public function loadRelatedData()
        {
            $this->getChinhSach();
            $this->getLichTrinh();
            $this->getNhaCungCap();
            $this->getAnhChiTiet();
        }

        public static function getTourWithDetails($id)
        {
         // Lấy tour với tất cả thông tin liên quan(join bảng)
           return self::find($id, true); //true = load reload data
        }

        // Lưu nhiều chính sách cho tour
        public static function luuNhieuChinhSach($tour_id, $chinh_sach_list)
        {
            if (empty($chinh_sach_list) || !is_array($chinh_sach_list)) {
                return;
            }

            $pdo = getDB();
            $sql = "INSERT INTO tour_chinh_sach (tour_id, ten_chinh_sach, noi_dung, ngay_tao, ngay_cap_nhat)
                    VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);

            foreach ($chinh_sach_list as $chinh_sach) {
                $ten = trim($chinh_sach['ten'] ?? '');
                $noi_dung = trim($chinh_sach['noi_dung'] ?? '');

                if (!empty($ten) && !empty($noi_dung)) {
                    $stmt->execute([$tour_id, $ten, $noi_dung]);
                }
            }
        }

        // Lưu nhiều lịch trình cho tour
        public static function luuNhieuLichTrinh($tour_id, $lich_trinh_list)
        {
            if (empty($lich_trinh_list) || !is_array($lich_trinh_list)) {
                return;
            }

            $pdo = getDB();
            $sql = "INSERT INTO tour_lich_trinh (tour_id, ngay, diem_tham_quan, hoat_dong)
                    VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            foreach ($lich_trinh_list as $index => $lich_trinh) {
                $ngay = $index + 1; // Tự động tính số ngày từ index (bắt đầu từ 1)
                $diem_tham_quan = trim($lich_trinh['diem_tham_quan'] ?? '');
                $hoat_dong = trim($lich_trinh['hoat_dong'] ?? '');

                if (!empty($diem_tham_quan) || !empty($hoat_dong)) {
                    $stmt->execute([$tour_id, $ngay, $diem_tham_quan, $hoat_dong]);
                }
            }
        }

        // Lưu nhiều nhà cung cấp cho tour
        public static function luuNhieuNhaCungCap($tour_id, $nha_cung_cap_list)
        {
            if (empty($nha_cung_cap_list) || !is_array($nha_cung_cap_list)) {
                return;
            }

            $pdo = getDB();
            $sql = "INSERT INTO tour_nha_cung_cap (tour_id, ten_nha_cung_cap, loai, lien_he, ghi_chu, ngay_tao, ngay_cap_nhat)
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);

            foreach ($nha_cung_cap_list as $nha_cung_cap) {
                $ten = trim($nha_cung_cap['ten'] ?? '');
                $loai = trim($nha_cung_cap['loai'] ?? '');
                $lien_he = trim($nha_cung_cap['lien_he'] ?? '');
                $ghi_chu = trim($nha_cung_cap['ghi_chu'] ?? '');

                if (!empty($ten)) {
                    $stmt->execute([$tour_id, $ten, $loai, $lien_he, $ghi_chu]);
                }
            }
        }

        // Lưu ảnh chi tiết cho tour
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

        // Thêm ảnh chi tiết
        public static function addAnhChiTiet($tour_id, $duong_dan)
        {
            $pdo = getDB();
            $sql = "INSERT INTO tour_anh (tour_id, duong_dan, ngay_tao)
                    VALUES (?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$tour_id, $duong_dan]);
        }

        // Xóa ảnh chi tiết
        public static function deleteAnhChiTiet($id)
        {
            $pdo = getDB();
            $sql = "DELETE FROM tour_anh WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        }


    }
?>
