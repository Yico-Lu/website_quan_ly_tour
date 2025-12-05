<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="<?= BASE_URL . 'guide' ?>" class="brand-link">
      <!--begin::Brand Image-->
      <img
        src="<?= asset('dist/assets/img/AdminLTELogo.png') ?>"
        alt="AdminLTE Logo"
        class="brand-image opacity-75 shadow"
      />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">HDV - Quản Lý Tour</span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="menu"
        data-accordion="false"
      >
        <li class="nav-item">
          <a href="<?= BASE_URL . 'guide' ?>" class="nav-link">
            <i class="nav-icon bi bi-house-door"></i>
            <p>Trang chủ HDV</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-map"></i>
            <p>
              Tour được phân công
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= BASE_URL . 'guide' ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Danh sách Tour</p>
              </a>
            </li>
            <!-- TODO: Thêm các menu con khác như tour đang vận hành, tour sắp tới, etc. -->
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-clipboard-check"></i>
            <p>
              Vận hành Tour
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Quản lý khách hàng</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Cập nhật tiến độ</p>
              </a>
            </li>
          
          </ul>
        </li>
        <li class="nav-item">
          <a href="<?= BASE_URL . 'logout' ?>" class="nav-link">
            <i class="nav-icon bi bi-box-arrow-right"></i>
            <p>Đăng xuất</p>
          </a>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->