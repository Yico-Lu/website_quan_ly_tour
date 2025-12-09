<?php
class NhatKyTour
{
    public $id;
    public $booking_id;
    public $ngay_gio;
    public $noi_dung;
    public $danh_gia_hdv;

    // Khởi tạo
    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->booking_id = $data['booking_id'] ?? null;
            $this->ngay_gio = $data['ngay_gio'] ?? date('Y-m-d H:i:s');
            $this->noi_dung = $data['noi_dung'] ?? '';
            $this->danh_gia_hdv = $data['danh_gia_hdv'] ?? '';
        }
    }

    // Lấy danh sách nhật ký theo booking_id
    public static function getByBookingId($booking_id)
    {
        $pdo = getDB();
        $sql = "SELECT * FROM nhat_ky_tour WHERE booking_id = ? ORDER BY ngay_gio DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$booking_id]);
        $nhatKys = [];
        foreach($stmt->fetchAll() as $row){
            $nhatKys[] = new NhatKyTour($row);
        }
        return $nhatKys;
    }

    // Tìm nhật ký theo ID
    public static function find($id)
    {
        $pdo = getDB();
        $sql = "SELECT * FROM nhat_ky_tour WHERE id = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? new NhatKyTour($data) : null;
    }

    // Tạo nhật ký mới
    public static function create(NhatKyTour $nhatKy)
    {
        $pdo = getDB();
        $sql = "INSERT INTO nhat_ky_tour (booking_id, ngay_gio, noi_dung, danh_gia_hdv)
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if($stmt->execute([
            $nhatKy->booking_id,
            $nhatKy->ngay_gio,
            $nhatKy->noi_dung,
            $nhatKy->danh_gia_hdv
        ])){
            return $pdo->lastInsertId();
        }
        return false;
    }

    // Cập nhật nhật ký
    public static function update(NhatKyTour $nhatKy)
    {
        $pdo = getDB();
        $sql = "UPDATE nhat_ky_tour SET
                    booking_id = ?,
                    ngay_gio = ?,
                    noi_dung = ?,
                    danh_gia_hdv = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $nhatKy->booking_id,
            $nhatKy->ngay_gio,
            $nhatKy->noi_dung,
            $nhatKy->danh_gia_hdv,
            $nhatKy->id
        ]);
    }

    // Xóa nhật ký
    public static function delete($id)
    {
        $pdo = getDB();
        $sql = "DELETE FROM nhat_ky_tour WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>

