<?php
    class CategoryController
    {
        public function index():void
        {
            //Kiểm tra quyền admin
            requireAdmin();

            $categories = Category::getAll();

            //hiển thị view danh sách danh mục
            view('admin.categories.index', [
                'title' => $title ?? 'Danh sách Danh mục Tour - Quản lý Tour',
                'pageTitle' => $pageTitle ?? 'Danh sách Danh mục Tour',
                'categories' => $categories,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories', 'active' => true],
                ],
            ]);
        }

        //hiển thị form thêm danh mục
        public function create():void
        {
            requireAdmin();

            view('admin.categories.create', [
                'title' => 'Thêm Danh mục Mới',
                'pageTitle' => 'Thêm Danh mục Mới',
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                    ['label' => 'Thêm danh mục mới', 'url' => BASE_URL . 'categories/create', 'active' => true],
                ],
            ]);
        }

        //thêm danh mục mới
        public function store():void
        {
            requireAdmin();

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('Location: ' . BASE_URL . 'categories/create');
                exit;
            }
            //lấy dữ liệu từ form
            $ten_danh_muc = trim($_POST['ten_danh_muc'] ?? '');
            $mo_ta = trim($_POST['mo_ta'] ?? '');
            $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

            //kiểm tra dữ liệu
            $errors = [];
            if(empty($ten_danh_muc)) $errors[] = 'Vui lòng nhập tên danh mục';
            if(empty($mo_ta)) $errors[] = 'Vui lòng nhập mô tả';

            if(!empty($errors)){
                view('admin.categories.create', [
                'title' => 'Thêm danh mục mới',
                'pageTitle' => 'Thêm danh mục mới',
                'errors' => $errors,
                'old' => $_POST,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                    ['label' => 'Thêm danh mục mới', 'url' => BASE_URL . 'categories/create', 'active' => true],
                ],
                ]);
                return;
            }

            //tạo danh mục mới
            $category = new Category([
                'ten_danh_muc' => $ten_danh_muc,
                'mo_ta' => $mo_ta,
                'trang_thai' => $trang_thai
            ]);

            if(Category::save($category)){
                $_SESSION['success'] = 'Thêm danh mục mới thành công';
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }else{
                $_SESSION['error'] = 'Thêm danh mục mới thất bại';
                header('Location: ' . BASE_URL . 'categories/create');
                exit;
            }
        }

        //hiển thị form sửa danh mục
        public function edit($id):void
        {
            requireAdmin();

            $category = Category::find($id);
            if(!$category){
                $_SESSION['error'] = 'Danh mục không tồn tại';
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }

            view('admin.categories.edit', [
                'title' => 'Sửa Danh mục',
                'pageTitle' => 'Sửa Danh mục',
                'category' => $category,
                'breadcrumb' => [
                    ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                    ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                    ['label' => 'Sửa danh mục', 'url' => BASE_URL . 'categories/edit/' . $id, 'active' => true],
                ],
            ]);
        }

        //cập nhật danh mục
        public function update():void
        {
            requireAdmin();

            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }

            $id = $_POST['id'] ?? null;
            if(!$id){
                $_SESSION['error'] = 'ID không hợp lệ';
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }

            //lấy dữ liệu từ form
            $ten_danh_muc = trim($_POST['ten_danh_muc'] ?? '');
            $mo_ta = trim($_POST['mo_ta'] ?? '');
            $trang_thai = isset($_POST['trang_thai']) ? 1 : 0;

            //kiểm tra dữ liệu
            $errors = [];
            if(empty($ten_danh_muc)) $errors[] = 'Vui lòng nhập tên danh mục';
            if(empty($mo_ta)) $errors[] = 'Vui lòng nhập mô tả';

            if(!empty($errors)){
                $category = Category::find($id);

                view('admin.categories.edit', [
                    'title' => 'Sửa danh mục',
                    'pageTitle' => 'Sửa danh mục',
                    'category' => $category,
                    'errors' => $errors,
                    'old' => $_POST,
                    'breadcrumb' => [
                        ['label' => 'Trang chủ', 'url' => BASE_URL . 'home'],
                        ['label' => 'Danh sách danh mục', 'url' => BASE_URL . 'categories'],
                        ['label' => 'Sửa danh mục', 'url' => BASE_URL . 'categories/edit/' . $id, 'active' => true],
                    ],
                ]);
                return;
            }

            //tạo danh mục để cập nhật
            $category = new Category([
                'id' => $id,
                'ten_danh_muc' => $ten_danh_muc,
                'mo_ta' => $mo_ta,
                'trang_thai' => $trang_thai
            ]);

            if(Category::update($category)){
                $_SESSION['success'] = 'Cập nhật danh mục thành công';
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }else{
                $_SESSION['error'] = 'Cập nhật danh mục thất bại';
                header('Location: ' . BASE_URL . 'categories/edit/' . $id);
                exit;
            }
        }

        //xoá danh mục
        public function delete():void
        {
            requireAdmin();
            if($_SERVER['REQUEST_METHOD'] !== 'POST'){
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }

            $id = $_POST['id'] ?? null;
            if(!$id){
                $_SESSION['error'] = 'ID không hợp lệ';
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }

            if(Category::delete($id)){
                $_SESSION['success'] = 'Xoá danh mục thành công';
            }else {
                $_SESSION['error'] = 'Không thể xóa danh mục này vì đang được sử dụng trong tour';
            }
            header('Location: ' . BASE_URL . 'categories');
            exit;
        }
    }

?>
