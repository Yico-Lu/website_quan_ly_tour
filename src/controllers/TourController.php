<?php
    class TourController
    {
        public function index():void
        {
            //Kiểm tra quyền admin
            requireAdmin();

            $tours = Tour::getAll();

            //hiển thị view danh sách tour
            view('admin.tours.index', [
                'title' => $title ?? 'Danh sách Tour - Quản lý Tour',
                'pageTitle' => $pageTitle ?? 'Danh sách Tour',
                'tours' => $tours,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách tour', 'url' => BASE_URL . 'tours', 'active' => true],
                ],
            ]);
        }

        //hiển thị form thêm tour
        public function create():void
        {
            requireAdmin();

            $danhMucList = Tour::getDanhMucList();
            view('admin.tours.create', [
                'title' => 'Thêm Tour Mới',
                'pageTitle' => 'Thêm Tour Mới',
                'danhMucList' => $danhMucList,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách tour', 'url' => BASE_URL . 'tours'],
                    ['label' => 'Thêm tour mới', 'url' => BASE_URL . 'tours/create', 'active' => true],
                ],
            ]);
        }

        //thêm tour mới
        public function store():void
        {
            requireAdmin();

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('Location: ' . BASE_URL . 'tours/create');
                exit;
            }
            //lấy dữ liệu từ form
            $ten_tour = trim($_POST['ten_tour'] ?? '');
            $danh_muc_id = trim($_POST['danh_muc_id'] ?? '');
            $mo_ta = trim($_POST['mo_ta'] ?? '');
            $gia = trim($_POST['gia'] ?? 0);
            $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;
            $anh_tour = trim($_POST['anh_tour'] ?? '');

            //kiểm tra dữ liệu
            $errors = [];
            if(empty($ten_tour)) $errors[] = 'Vui lòng nhập tên tour';
            if(empty($danh_muc_id)) $errors[] = 'Vui lòng chọn danh mục';
            if(empty($mo_ta)) $errors[] = 'Vui lòng nhập mô tả';
            if($gia <= 0) $errors[] = 'Giá tour phải lớn hơn 0';

            if(!empty($errors)){
                $danhMucList = Tour::getDanhMucList(); //quay lại form với lỗi lấy danh mục từ model

                view('admin.tours.create', [
                'title' => 'Thêm tour mới',
                'pageTitle' => 'Thêm tour mới',
                'danhMucList' => $danhMucList,
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách tour', 'url' => BASE_URL . 'tours'],
                    ['label' => 'Thêm tour mới', 'url' => BASE_URL . 'tours/create', 'active' => true],
                ],
                ]);
                return;
            }

            //tạo tour mới
            // tạo mới ok
            $tour = new Tour([
                'ten_tour' => $ten_tour,
                'danh_muc_id' => $danh_muc_id,
                'mo_ta' => $mo_ta,
                'gia' => $gia,
                'trang_thai' => $trang_thai,
                'anh_tour' => $anh_tour
            ]);

            if(Tour::save($tour)){
                $_SESSION['success'] = 'Thêm tour mới thành công';
                header('Location: ' . BASE_URL . 'tours');
                exit;
            }else{
                $_SESSION['error'] = 'Thêm tour mới thất bại';
                header('Location: ' . BASE_URL . 'tours/create');
                exit;
            }
        }

        //hiển thị form sửa tour
        public function edit($id):void
        {
            requireAdmin();

            $tour = Tour::find($id);
            if(!$tour){
                $_SESSION['error'] = 'Tour không tồn tại';
                header('Location: ' . BASE_URL . 'tours');
                exit;
            }

            $danhMucList = Tour::getDanhMucList();
            view('admin.tours.edit', [
                'title' => 'Sửa Tour',
                'pageTitle' => 'Sửa Tour',
                'tour' => $tour,
                'danhMucList' => $danhMucList,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách tour', 'url' => BASE_URL . 'tours'],
                    ['label' => 'Sửa tour', 'url' => BASE_URL . 'tours/edit/' . $id, 'active' => true],
                ],
            ]);
        }

        //cập nhật tour
        public function update():void
        {
            requireAdmin();

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('Location: ' . BASE_URL . 'tours');
                exit;
            }

            $id = $_POST['id'] ?? null;
            if(!$id){
                $_SESSION['error'] = 'ID không hợp lệ';
                header('Location: ' . BASE_URL . 'tours');
                exit;
            }

            //lấy dữ liệu từ form
            $ten_tour = trim($_POST['ten_tour'] ?? '');
            $danh_muc_id = trim($_POST['danh_muc_id'] ?? '');
            $mo_ta = trim($_POST['mo_ta'] ?? '');
            $gia = trim($_POST['gia'] ?? 0);
            $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;
            $anh_tour = trim($_POST['anh_tour'] ?? '');

            //kiểm tra dữ liệu
            $errors = [];
            if(empty($ten_tour)) $errors[] = 'Vui lòng nhập tên tour';
            if(empty($danh_muc_id)) $errors[] = 'Vui lòng chọn danh mục';
            if(empty($mo_ta)) $errors[] = 'Vui lòng nhập mô tả';
            if($gia <= 0) $errors[] = 'Giá tour phải lớn hơn 0';

            if(!empty($errors)){
                $tour = Tour::find($id);
                $danhMucList = Tour::getDanhMucList();

                view('admin.tours.edit', [
                    'title' => 'Sửa tour',
                    'pageTitle' => 'Sửa tour',
                    'tour' => $tour,
                    'danhMucList' => $danhMucList,
                    'errors' => $errors,
                    'old' => $_POST,
                    'breadcrumb' => [
                        ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                        ['label' => 'Danh sách tour', 'url' => BASE_URL . 'tours'],
                        ['label' => 'Sửa tour', 'url' => BASE_URL . 'tours/edit/' . $id, 'active' => true],
                    ],
                ]);
                return;
            }

            //tạo tour để cập nhật
            $tour = new Tour([
                'id' => $id,
                'ten_tour' => $ten_tour,
                'danh_muc_id' => $danh_muc_id,
                'mo_ta' => $mo_ta,
                'gia' => $gia,
                'trang_thai' => $trang_thai,
                'anh_tour' => $anh_tour
            ]);

            if(Tour::update($tour)){
                $_SESSION['success'] = 'Cập nhật tour thành công';
                header('Location: ' . BASE_URL . 'tours');
                exit;
            }else{
                $_SESSION['error'] = 'Cập nhật tour thất bại';
                header('Location: ' . BASE_URL . 'tours/edit/' . $id);
                exit;
            }
        }

        //xoá tour
        public function delete():void
        {
            requireAdmin();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('Location: ' . BASE_URL . 'tours');
                exit;
            }

            $id = $_POST['id'] ?? null;
            if(!$id){
                $_SESSION['error'] = 'ID không hợp lệ';
                header('Location: ' . BASE_URL . 'tours');
                exit;
            }

            if(Tour::delete($id)){
                $_SESSION['success'] = 'Xoá tour thành công';
            }else {
                $_SESSION['error'] = 'Không thể xóa tour này vì đang được sử dụng trong hệ thống (báo cáo hoặc đặt tour)';
            }
            header('Location: ' . BASE_URL . 'tours');
            exit;
        }
    }
?>