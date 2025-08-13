<!-- Sidebar scroll-->
<div>
  <div class="brand-logo d-flex align-items-center justify-content-between">
    <a href="<?= SITE_ADMIN_URL?>dashboard.php" class="text-nowrap logo-img">
      <img src="../public/templates/default/img/logo.png" class="logo-admin" alt="" />
    </a>
    <div class="close-btn d-xl-none d-block sidebarToggler cursor-pointer" id="sidebarCollapse">
      <i class="ti ti-x fs-6"></i>
    </div>
  </div>
  <!-- Sidebar navigation-->
  <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
    <ul id="sidebarNav">
      <li class="nav-small-cap">
        <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
        <span class="hide-menu">Home</span>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link"
           href="<?= SITE_ADMIN_URL?>dashboard.php" aria-expanded="false">
          <i class="ti ti-atom"></i>
          <span class="hide-menu">Dashboard</span>
        </a>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between"
           href="#"
           aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-aperture"></i>
            </span>
            <span class="hide-menu">Recent Orders</span>
          </div>
        </a>
      </li>
      <li>
        <span class="sidebar-divider lg"></span>
      </li>
      <li class="nav-small-cap">
        <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
        <span class="hide-menu">Apps</span>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-basket"></i>
            </span>
            <span class="hide-menu">Ecommerce</span>
          </div>
        </a>
        <ul aria-expanded="false" class="collapse first-level">
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">List Products</span>
              </div>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Checkout Options</span>
              </div>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#" aria-expanded="false">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Pricing</span>
              </div>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Add Product</span>
              </div>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Edit Product</span>
              </div>
            </a>
          </li>
        </ul>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between has-arrow"
           href="javascript:void(0)" aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-layout-grid"></i>
            </span>
            <span class="hide-menu">Front Pages</span>
          </div>
        </a>
        <ul aria-expanded="false" class="collapse first-level">
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Homepage</span>
              </div>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">About Us</span>
              </div>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Contact Us</span>
              </div>
            </a>
          </li>
        </ul>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between has-arrow"
           href="javascript:void(0)" aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-list-details"></i>
            </span>
            <span class="hide-menu">Customers</span>
          </div>
        </a>
        <ul aria-expanded="false" class="collapse first-level">
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Customers List</span>
              </div>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Add Customer</span>
              </div>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link justify-content-between"
               href="#">
              <div class="d-flex align-items-center gap-3">
                <div class="round-16 d-flex align-items-center justify-content-center">
                  <i class="ti ti-circle"></i>
                </div>
                <span class="hide-menu">Edit Customer</span>
              </div>
            </a>
          </li>
        </ul>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between"
           href="#" aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-file-text"></i>
            </span>
            <span class="hide-menu">Invoices</span>
          </div>
        </a>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between"
           href="<?= SITE_ADMIN_URL?>subscribers.php" aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-user-plus"></i>
            </span>
            <span class="hide-menu">Subscribers</span>
          </div>
        </a>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between"
           href="<?= SITE_ADMIN_URL?>contacts.php" aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-mail"></i>
            </span>
            <span class="hide-menu">Contact Messages</span>
          </div>
        </a>
      </li>
      <li>
        <span class="sidebar-divider lg"></span>
      </li>
      <li class="nav-small-cap">
        <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
        <span class="hide-menu">Shop Settings</span>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between"
           href="#"
           aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-settings"></i>
            </span>
            <span class="hide-menu">Account Setting</span>
          </div>
        </a>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between"
           href="#"
           aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-user-circle"></i>
            </span>
            <span class="hide-menu">User Profile</span>
          </div>
        </a>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link justify-content-between"
           href="#" aria-expanded="false">
          <div class="d-flex align-items-center gap-3">
            <span class="d-flex">
              <i class="ti ti-help"></i>
            </span>
            <span class="hide-menu">FAQ</span>
          </div>
        </a>
      </li>
    </ul>
  </nav>
  <!-- End Sidebar navigation -->
</div>
<!-- End Sidebar scroll-->
