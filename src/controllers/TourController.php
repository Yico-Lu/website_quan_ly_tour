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
        $lich_trinh = trim($_POST['lich_trinh'] ?? '');
        $chinh_sach_ten = trim($_POST['chinh_sach_ten'] ?? '');
        $chinh_sach_noi_dung = trim($_POST['chinh_sach_noi_dung'] ?? '');
        $nha_cung_cap_ten = trim($_POST['nha_cung_cap_ten'] ?? '');
        $nha_cung_cap_loai = trim($_POST['nha_cung_cap_loai'] ?? '');
        $nha_cung_cap_lien_he = trim($_POST['nha_cung_cap_lien_he'] ?? '');

        // Xử lý upload ảnh tour chính
        $anh_tour_path = null;
        if (isset($_FILES['anh_tour']) && $_FILES['anh_tour']['error'] !== UPLOAD_ERR_NO_FILE) {
            $anh_tour_path = uploadImage($_FILES['anh_tour'], 'tour_main', 'uploads/tours/');
        }

        // Xử lý upload ảnh chi tiết
        $anh_chi_tiet_paths = [];
        if (isset($_FILES['anh_chi_tiet'])) {
            $anh_chi_tiet_paths = uploadMultipleImages($_FILES['anh_chi_tiet'], 'tour_detail', 'uploads/tours/');
        }

        //kiểm tra dữ liệu
        $errors = [];
        if(empty($ten_tour)) $errors[] = 'Vui lòng nhập tên tour';
        if(empty($danh_muc_id)) $errors[] = 'Vui lòng chọn danh mục';
        if(empty($mo_ta)) $errors[] = 'Vui lòng nhập mô tả';
        if($gia <= 0) $errors[] = 'Giá tour phải lớn hơn 0';

        if(!empty($errors)){
            $danhMucList = Tour::getDanhMucList();

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
        $tour = new Tour([
            'ten_tour' => $ten_tour,
            'danh_muc_id' => $danh_muc_id,
            'mo_ta' => $mo_ta,
            'gia' => $gia,
            'trang_thai' => $trang_thai,
            'anh_tour' => $anh_tour_path ?: '' // Sử dụng ảnh đã upload hoặc để trống
        ]);

        $tourId = Tour::save($tour);
        if($tourId){
            // Lưu chính sách
            if(!empty($chinh_sach_ten) && !empty($chinh_sach_noi_dung)){
                $pdo = getDB();
                $sql = "INSERT INTO tour_chinh_sach (tour_id, ten_chinh_sach, noi_dung, ngay_tao, ngay_cap_nhat)
                        VALUES (?, ?, ?, NOW(), NOW())";
                $pdo->prepare($sql)->execute([$tourId, $chinh_sach_ten, $chinh_sach_noi_dung]);
            }

            // Lưu lịch trình
            Tour::saveLichTrinh($tourId, $lich_trinh);

            // Lưu nhà cung cấp
            if(!empty($nha_cung_cap_ten) && !empty($nha_cung_cap_loai)){
                $pdo = getDB();
                $sql = "INSERT INTO tour_nha_cung_cap (tour_id, ten_nha_cung_cap, loai, lien_he, ngay_tao, ngay_cap_nhat)
                        VALUES (?, ?, ?, ?, NOW(), NOW())";
                $pdo->prepare($sql)->execute([$tourId, $nha_cung_cap_ten, $nha_cung_cap_loai, $nha_cung_cap_lien_he]);
            }

            // Lưu ảnh chi tiết đã upload
            foreach($anh_chi_tiet_paths as $anh_path){
                Tour::saveAnhChiTiet($tourId, $anh_path);
            }

            setFlashMessage('success', 'Thêm tour mới thành công');
            header('Location: ' . BASE_URL . 'tours');
            exit;
        }else{
            setFlashMessage('error', 'Thêm tour mới thất bại');
            header('Location: ' . BASE_URL . 'tours/create');
            exit;
        }
    }

    //hiển thị form sửa tour
    public function edit($id):void
    {
        requireAdmin();

        $tour = Tour::find($id, true); // Load dữ liệu liên quan
        if(!$tour){
            setFlashMessage('error', 'Tour không tồn tại');
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
            setFlashMessage('error', 'ID không hợp lệ');
            header('Location: ' . BASE_URL . 'tours');
            exit;
        }

        //lấy dữ liệu từ form
        $ten_tour = trim($_POST['ten_tour'] ?? '');
        $danh_muc_id = trim($_POST['danh_muc_id'] ?? '');
        $mo_ta = trim($_POST['mo_ta'] ?? '');
        $gia = trim($_POST['gia'] ?? 0);
        $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;
        $lich_trinh = trim($_POST['lich_trinh'] ?? '');
        $chinh_sach_ten = trim($_POST['chinh_sach_ten'] ?? '');
        $chinh_sach_noi_dung = trim($_POST['chinh_sach_noi_dung'] ?? '');
        $nha_cung_cap_ten = trim($_POST['nha_cung_cap_ten'] ?? '');
        $nha_cung_cap_loai = trim($_POST['nha_cung_cap_loai'] ?? '');
        $nha_cung_cap_lien_he = trim($_POST['nha_cung_cap_lien_he'] ?? '');

        // Xử lý upload ảnh tour chính (nếu có upload mới)
        $anh_tour_path = null;
        if (isset($_FILES['anh_tour']) && $_FILES['anh_tour']['error'] !== UPLOAD_ERR_NO_FILE) {
            $anh_tour_path = uploadImage($_FILES['anh_tour'], 'tour_main', 'uploads/tours/');
        }

        // Xử lý upload ảnh chi tiết mới (thêm vào, không xóa cũ)
        $anh_chi_tiet_paths = [];
        if (isset($_FILES['anh_chi_tiet'])) {
            $anh_chi_tiet_paths = uploadMultipleImages($_FILES['anh_chi_tiet'], 'tour_detail', 'uploads/tours/');
        }

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

        // Lấy tour hiện tại để giữ ảnh cũ nếu không upload mới
        $currentTour = Tour::find($id);
        $final_anh_tour = $anh_tour_path ?: ($currentTour ? $currentTour->anh_tour : '');

        //tạo tour để cập nhật
        $tour = new Tour([
            'id' => $id,
            'ten_tour' => $ten_tour,
            'danh_muc_id' => $danh_muc_id,
            'mo_ta' => $mo_ta,
            'gia' => $gia,
            'trang_thai' => $trang_thai,
            'anh_tour' => $final_anh_tour
        ]);

        if(Tour::update($tour)){
            $pdo = getDB();

            // Cập nhật chính sách - xóa cũ và thêm mới
            $pdo->prepare("DELETE FROM tour_chinh_sach WHERE tour_id = ?")->execute([$id]);
            if(!empty($chinh_sach_ten) && !empty($chinh_sach_noi_dung)){
                $sql = "INSERT INTO tour_chinh_sach (tour_id, ten_chinh_sach, noi_dung, ngay_tao, ngay_cap_nhat)
                        VALUES (?, ?, ?, NOW(), NOW())";
                $pdo->prepare($sql)->execute([$id, $chinh_sach_ten, $chinh_sach_noi_dung]);
            }

            // Cập nhật lịch trình - xóa cũ và thêm mới
            $pdo->prepare("DELETE FROM tour_lich_trinh WHERE tour_id = ?")->execute([$id]);
            Tour::saveLichTrinh($id, $lich_trinh);

            // Cập nhật nhà cung cấp - xóa cũ và thêm mới
            $pdo->prepare("DELETE FROM tour_nha_cung_cap WHERE tour_id = ?")->execute([$id]);
            if(!empty($nha_cung_cap_ten) && !empty($nha_cung_cap_loai)){
                $sql = "INSERT INTO tour_nha_cung_cap (tour_id, ten_nha_cung_cap, loai, lien_he, ngay_tao, ngay_cap_nhat)
                        VALUES (?, ?, ?, ?, NOW(), NOW())";
                $pdo->prepare($sql)->execute([$id, $nha_cung_cap_ten, $nha_cung_cap_loai, $nha_cung_cap_lien_he]);
            }

            // Thêm ảnh chi tiết mới (không xóa ảnh cũ)
            foreach($anh_chi_tiet_paths as $anh_path){
                Tour::saveAnhChiTiet($id, $anh_path);
            }

            setFlashMessage('success', 'Cập nhật tour thành công');
            header('Location: ' . BASE_URL . 'tours');
            exit;
        }else{
            setFlashMessage('error', 'Cập nhật tour thất bại');
            header('Location: ' . BASE_URL . 'tours/edit/' . $id);
            exit;
        }
    }

    //xem chi tiết tour
    public function show($id):void
    {
        requireAdmin();

        $tour = Tour::getTourWithDetails($id); // Lấy tour với tất cả dữ liệu
        if(!$tour){
            setFlashMessage('error', 'Tour không tồn tại');
            header('Location: ' . BASE_URL . 'tours');
            exit;
        }

        view('admin.tours.show', [
            'title' => 'Chi tiết Tour: ' . $tour->ten_tour,
            'pageTitle' => 'Chi tiết Tour',
            'tour' => $tour,
            'breadcrumb' => [
                ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                ['label' => 'Danh sách tour', 'url' => BASE_URL . 'tours'],
                ['label' => 'Chi tiết tour', 'url' => BASE_URL . 'tours/show/' . $id, 'active' => true],
            ],
        ]);
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
            setFlashMessage('error', 'ID không hợp lệ');
            header('Location: ' . BASE_URL . 'tours');
            exit;
        }

        if(Tour::delete($id)){
            setFlashMessage('success', 'Xoá tour thành công');
        }else {
            setFlashMessage('error', 'Không thể xóa tour này vì đang được sử dụng trong hệ thống (báo cáo hoặc đặt tour)');
        }
        header('Location: ' . BASE_URL . 'tours');
        exit;
    }
}
?>