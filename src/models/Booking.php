<?php
class Booking
{
    // Các thuộc tính của booking
    public $id;
    public $tour_id;
    public $ho_ten;
    public $email;
    public $so_dien_thoai;
    public $so_luong_nguoi_lon;
    public $so_luong_tre_em;
    public $tong_tien;
    public $trang_thai;
    public $ngay_dat;
    public $ghi_chu;
    public $da_checkin;

    // Constructor để khởi tạo thực thể Booking
    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->tour_id = $data['tour_id'] ?? null;
            $this->ho_ten = $data['ho_ten'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->so_dien_thoai = $data['so_dien_thoai'] ?? '';
            $this->so_luong_nguoi_lon = $data['so_luong_nguoi_lon'] ?? 1;
            $this->so_luong_tre_em = $data['so_luong_tre_em'] ?? 0;
            $this->tong_tien = $data['tong_tien'] ?? 0;
            $this->trang_thai = $data['trang_thai'] ?? 'chua_xac_nhan';
            $this->ngay_dat = $data['ngay_dat'] ?? $data['ngay_tao'] ?? date('Y-m-d H:i:s');
            $this->ghi_chu = $data['ghi_chu'] ?? '';
            $this->da_checkin = $data['da_checkin'] ?? 0;
        }
    }

    // Lấy danh sách booking theo tour_id
    public static function getByTourId($tourId)
    {
        $pdo = getDB();
        // Sử dụng ORDER BY id DESC để tương thích với cấu trúc bảng hiện tại
        $sql = "SELECT * FROM booking WHERE tour_id = ? ORDER BY id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tourId]);
        $bookings = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $bookings[] = new Booking($row);
        }

        return $bookings;
    }

    // Lấy thông tin booking theo ID
    public static function find($id)
    {
        $pdo = getDB();
        $sql = "SELECT * FROM booking WHERE id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Booking($data) : null;
    }

    // Cập nhật trạng thái check-in
    public static function updateCheckinStatus($id, $checkinStatus)
    {
        $pdo = getDB();

        // Kiểm tra xem cột da_checkin có tồn tại không
        try {
            $sql = "UPDATE booking SET da_checkin = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$checkinStatus, $id]);
        } catch (Exception $e) {
            // Nếu cột da_checkin không tồn tại, báo lỗi
            return false;
        }
    }

    // Import danh sách khách hàng từ file CSV
    public static function importFromCSV($tourId, $filePath)
    {
        $pdo = getDB();
        $importedCount = 0;
        $errors = [];

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            // Bỏ qua dòng header
            fgetcsv($handle, 1000, ",");

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                try {
                    // Giả định cấu trúc CSV: ho_ten,email,so_dien_thoai,so_luong_nguoi_lon,so_luong_tre_em,tong_tien,ghi_chu
                    $bookingData = [
                        'tour_id' => $tourId,
                        'ho_ten' => trim($data[0] ?? ''),
                        'email' => trim($data[1] ?? ''),
                        'so_dien_thoai' => trim($data[2] ?? ''),
                        'so_luong_nguoi_lon' => intval($data[3] ?? 1),
                        'so_luong_tre_em' => intval($data[4] ?? 0),
                        'tong_tien' => floatval($data[5] ?? 0),
                        'ghi_chu' => trim($data[6] ?? ''),
                        'trang_thai' => 'da_xac_nhan',
                        'da_checkin' => 0
                    ];

                    // Validate dữ liệu cơ bản
                    if (empty($bookingData['ho_ten'])) {
                        $errors[] = "Dòng " . ($importedCount + 2) . ": Thiếu họ tên";
                        continue;
                    }

                    // Thêm vào database - chỉ insert các cột có thể có
                    $sql = "INSERT INTO booking (tour_id, ho_ten";

                    $params = [
                        $bookingData['tour_id'],
                        $bookingData['ho_ten']
                    ];

                    // Thêm các cột tùy chọn nếu có trong dữ liệu
                    if (!empty($bookingData['email'])) {
                        $sql .= ", email";
                        $params[] = $bookingData['email'];
                    }
                    if (!empty($bookingData['so_dien_thoai'])) {
                        $sql .= ", so_dien_thoai";
                        $params[] = $bookingData['so_dien_thoai'];
                    }
                    if (isset($bookingData['so_luong_nguoi_lon'])) {
                        $sql .= ", so_luong_nguoi_lon";
                        $params[] = $bookingData['so_luong_nguoi_lon'];
                    }
                    if (isset($bookingData['so_luong_tre_em'])) {
                        $sql .= ", so_luong_tre_em";
                        $params[] = $bookingData['so_luong_tre_em'];
                    }
                    if (isset($bookingData['tong_tien'])) {
                        $sql .= ", tong_tien";
                        $params[] = $bookingData['tong_tien'];
                    }
                    if (isset($bookingData['trang_thai'])) {
                        $sql .= ", trang_thai";
                        $params[] = $bookingData['trang_thai'];
                    }
                    if (isset($bookingData['ghi_chu'])) {
                        $sql .= ", ghi_chu";
                        $params[] = $bookingData['ghi_chu'];
                    }
                    if (isset($bookingData['da_checkin'])) {
                        $sql .= ", da_checkin";
                        $params[] = $bookingData['da_checkin'];
                    }

                    $sql .= ") VALUES (?" . str_repeat(", ?", count($params) - 1) . ")";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);

                    $importedCount++;

                } catch (Exception $e) {
                    $errors[] = "Dòng " . ($importedCount + 2) . ": " . $e->getMessage();
                }
            }
            fclose($handle);
        }

        return [
            'imported' => $importedCount,
            'errors' => $errors
        ];
    }

    // Lấy thống kê khách hàng theo tour
    public static function getTourStats($tourId)
    {
        $pdo = getDB();

        // Tổng số booking
        $totalBookings = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE tour_id = ?");
        $totalBookings->execute([$tourId]);
        $total = $totalBookings->fetchColumn();

        // Các thống kê khác - kiểm tra xem cột có tồn tại không
        $stats = [
            'total_bookings' => $total,
            'checked_in' => 0,
            'total_people' => 0,
            'total_revenue' => 0,
            'pending_checkin' => $total
        ];

        // Kiểm tra và lấy thống kê check-in nếu cột da_checkin tồn tại
        try {
            $checkedIn = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE tour_id = ? AND da_checkin = 1");
            $checkedIn->execute([$tourId]);
            $stats['checked_in'] = $checkedIn->fetchColumn();
            $stats['pending_checkin'] = $total - $stats['checked_in'];
        } catch (Exception $e) {
            // Nếu cột da_checkin không tồn tại, bỏ qua
        }

        // Kiểm tra và lấy thống kê số người nếu các cột tồn tại
        try {
            $totalPeople = $pdo->prepare("SELECT SUM(so_luong_nguoi_lon + so_luong_tre_em) FROM booking WHERE tour_id = ?");
            $totalPeople->execute([$tourId]);
            $stats['total_people'] = $totalPeople->fetchColumn() ?: 0;
        } catch (Exception $e) {
            // Nếu các cột không tồn tại, để mặc định là 0
        }

        // Kiểm tra và lấy thống kê doanh thu nếu cột tong_tien tồn tại
        try {
            $totalRevenue = $pdo->prepare("SELECT SUM(tong_tien) FROM booking WHERE tour_id = ?");
            $totalRevenue->execute([$tourId]);
            $stats['total_revenue'] = $totalRevenue->fetchColumn() ?: 0;
        } catch (Exception $e) {
            // Nếu cột tong_tien không tồn tại, để mặc định là 0
        }

        return $stats;
    }

    // Lấy trạng thái check-in dạng badge
    public function getCheckinBadge()
    {
        if ($this->da_checkin == 1) {
            return '<span class="badge bg-success">Đã check-in</span>';
        } else {
            return '<span class="badge bg-warning">Chưa check-in</span>';
        }
    }

    // Lấy trạng thái check-in dạng text
    public function getCheckinStatus()
    {
        return $this->da_checkin == 1 ? 'Đã check-in' : 'Chưa check-in';
    }

    // Format tiền tệ
    public function formatTongTien()
    {
        return number_format($this->tong_tien, 0, ',', '.') . ' VND';
    }

    // Lấy tổng số người trong booking
    public function getTongSoNguoi()
    {
        return $this->so_luong_nguoi_lon + $this->so_luong_tre_em;
    }
}
?>