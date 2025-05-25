<aside class="left-sidebar">
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="./index.html" class="text-nowrap logo-img">
          <img src="assets/images/logos/logo.svg" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-6"></i>
        </div>
      </div>
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
            <span class="hide-menu">Home</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
              <i class="ti ti-atom"></i>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
              <i class="ti ti-users"></i>
              <span class="hide-menu">Manajemen User</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('students.index') }}" aria-expanded="false">
              <i class="ti ti-user-check"></i> <span class="hide-menu">Manajemen Siswa</span>
            </a>
          </li>
          </ul>
      </nav>
      </div>
    </aside>