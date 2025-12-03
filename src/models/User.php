<?php

// Model User đại diện cho thực thể người dùng trong hệ thống
class User
{
    // Các thuộc tính của User
    public $id;
    public $name;
    public $email;
    public $role;
    public $status;

    // Constructor để khởi tạo thực thể User
    public function __construct($data = [])
    {
        // Nếu truyền vào mảng dữ liệu thì gán vào các thuộc tính
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->role = $data['role'] ?? 'huong_dan_vien';
            $this->status = $data['status'] ?? 1;
        } else {
            // Nếu truyền vào string thì coi như tên (tương thích với code cũ)
            $this->name = $data;
        }
    }

    // Trả về tên người dùng để hiển thị
    public function getName()
    {
        return $this->name;
    }

    // Kiểm tra xem user có phải là admin không
    // @return bool true nếu là admin, false nếu không
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Kiểm tra xem user có phải là hướng dẫn viên không
    // @return bool true nếu là hướng dẫn viên, false nếu không
    public function isGuide()
    {
        return $this->role === 'huong_dan_vien';
    }

    // Phương thức xác thực đăng nhập từ database
    public static function authenticate($email, $password)
    {
        $pdo = getDB();
        //tìm user theo email
        $sql = "SELECT * FROM tai_khoan WHERE email = ? AND trang_thai = 'hoat_dong' LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Nếu không tìm thấy user hoặc password không đúng
        if(!$userData || !password_verify($password, $userData['mat_khau'])){
            return null;
        }

        //phan quyen
        $role = 'huong_dan_vien';
        if($userData['phan_quyen'] === 'admin'){
            $role = 'admin';
        }elseif($userData['phan_quyen'] === 'hdv'){
            $role = 'huong_dan_vien';
        }

        // Tạo và trả về User object
        return new User([
            'id' => $userData['id'],
            'name' => $userData['ho_ten'],
            'email' => $userData['email'],
            'role' => $role,
            'status' => 1,
        ]);
    }
}
