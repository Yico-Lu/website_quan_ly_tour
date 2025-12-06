<?php

// Model User đại diện cho thực thể người dùng trong hệ thống
class User
{
    // Các thuộc tính của User
    public $id;
    public $name; // ho_ten
    public $email;
    public $sdt;
    public $role; // phan_quyen
    public $status; // trang_thai
    public $ngay_tao;
    public $ngay_cap_nhat;

    // Constructor để khởi tạo thực thể User
    public function __construct($data = [])
    {
        // Nếu truyền vào mảng dữ liệu thì gán vào các thuộc tính
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? ($data['ho_ten'] ?? '');
            $this->email = $data['email'] ?? '';
            $this->sdt = $data['sdt'] ?? null;
            
            // Xử lý role/phan_quyen
            if (isset($data['role'])) {
                $this->role = $data['role'];
            } elseif (isset($data['phan_quyen'])) {
                $this->role = $data['phan_quyen'] === 'admin' ? 'admin' : 'huong_dan_vien';
            } else {
                $this->role = 'huong_dan_vien';
            }
            
            // Xử lý status/trang_thai
            if (isset($data['status'])) {
                $this->status = $data['status'] ? 1 : 0;
            } elseif (isset($data['trang_thai'])) {
                $this->status = $data['trang_thai'] === 'hoat_dong' ? 1 : 0;
            } else {
                $this->status = 1;
            }
            
            $this->ngay_tao = $data['ngay_tao'] ?? null;
            $this->ngay_cap_nhat = $data['ngay_cap_nhat'] ?? null;
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

        // Lấy danh sách tất cả tài khoản
        public static function getAll()
        {
            $pdo = getDB();
            $sql = "SELECT * FROM tai_khoan ORDER BY ngay_tao DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $users = [];
            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row){
                $users[] = new User([
                    'id' => $row['id'],
                    'ho_ten' => $row['ho_ten'],
                    'email' => $row['email'],
                    'sdt' => $row['sdt'] ?? null,
                    'phan_quyen' => $row['phan_quyen'],
                    'trang_thai' => $row['trang_thai'],
                    'ngay_tao' => $row['ngay_tao'],
                    'ngay_cap_nhat' => $row['ngay_cap_nhat'] ?? null,
                ]);
            }
            return $users;
        }

        // Tìm tài khoản theo ID
        public static function find($id)
        {
            $pdo = getDB();
            $sql = "SELECT * FROM tai_khoan WHERE id = ? LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$data) return null;

            return new User([
                'id' => $data['id'],
                'ho_ten' => $data['ho_ten'],
                'email' => $data['email'],
                'sdt' => $data['sdt'] ?? null,
                'phan_quyen' => $data['phan_quyen'],
                'trang_thai' => $data['trang_thai'],
                'ngay_tao' => $data['ngay_tao'],
                'ngay_cap_nhat' => $data['ngay_cap_nhat'] ?? null,
            ]);
        }

        // Tạo tài khoản mới
        public static function create(User $user, $password)
        {
            $pdo = getDB();

            // Kiểm tra email đã tồn tại
            $sqlCheck = "SELECT COUNT(*) FROM tai_khoan WHERE email = ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$user->email]);
            if($stmtCheck->fetchColumn() > 0){
                return false; // Email đã tồn tại
            }

            $sql = "INSERT INTO tai_khoan (ho_ten, email, sdt, mat_khau, phan_quyen, trang_thai, ngay_tao)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);

            $role = $user->isAdmin() ? 'admin' : 'hdv';
            $status = $user->status ? 'hoat_dong' : 'tam_ngung';

            if($stmt->execute([
                $user->name,
                $user->email,
                $user->sdt ?: null,
                password_hash($password, PASSWORD_DEFAULT),
                $role,
                $status
            ])){
                // Trả về ID của tài khoản vừa tạo
                return $pdo->lastInsertId();
            }
            
            return false;
        }

        // Cập nhật tài khoản
        public static function update(User $user)
        {
            $pdo = getDB();

            // Kiểm tra email đã tồn tại (trừ email hiện tại)
            $sqlCheck = "SELECT COUNT(*) FROM tai_khoan WHERE email = ? AND id != ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$user->email, $user->id]);
            if($stmtCheck->fetchColumn() > 0){
                return false; // Email đã tồn tại
            }

            $sql = "UPDATE tai_khoan SET
                        ho_ten = ?,
                        email = ?,
                        sdt = ?,
                        phan_quyen = ?,
                        trang_thai = ?,
                        ngay_cap_nhat = NOW()
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);

            $role = $user->isAdmin() ? 'admin' : 'hdv';
            $status = $user->status ? 'hoat_dong' : 'tam_ngung';

            try {
                $result = $stmt->execute([
                    $user->name,
                    $user->email,
                    $user->sdt ?: null,
                    $role,
                    $status,
                    $user->id
                ]);
                
                if (!$result) {
                    error_log('User update failed: ' . implode(', ', $stmt->errorInfo()));
                }
                
                return $result;
            } catch (PDOException $e) {
                error_log('User update error: ' . $e->getMessage());
                return false;
            }
        }

        // Cập nhật mật khẩu
        public static function updatePassword($id, $newPassword)
        {
            $pdo = getDB();
            $sql = "UPDATE tai_khoan SET
                        mat_khau = ?,
                        ngay_cap_nhat = NOW()
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                password_hash($newPassword, PASSWORD_DEFAULT),
                $id
            ]);
        }

        // Xóa tài khoản
        public static function delete($id)
        {
            $pdo = getDB();

            try {
                $sql = "DELETE FROM tai_khoan WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                return $stmt->execute([$id]);
            } catch (PDOException $e) {
                // Nếu có lỗi foreign key constraint, không xóa được
                error_log('Cannot delete user: ' . $e->getMessage());
                return false;
            }
        }

        // Lấy tên vai trò
        public function getRoleName()
        {
            return $this->role === 'admin' ? 'Quản trị viên' : 'Hướng dẫn viên';
        }

        // Lấy class badge cho vai trò
        public function getRoleBadgeClass()
        {
            return $this->role === 'admin' ? 'text-bg-primary' : 'text-bg-info';
        }

        // Lấy tên trạng thái
        public function getStatusName()
        {
            return $this->status ? 'Hoạt động' : 'Tạm ngưng';
        }

        // Lấy class badge cho trạng thái
        public function getStatusBadgeClass()
        {
            return $this->status ? 'text-bg-success' : 'text-bg-warning';
        }
}
