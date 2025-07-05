<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<!-- Header Start -->
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
    </ul>

    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        <li class="nav-item dropdown">
          <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
            @if(Auth::user()->photo)
              <img src="{{ asset('storage/'.Auth::user()->photo) }}" alt="Foto Profil" width="35" height="35"
                class="rounded-circle" style="object-fit:cover;">
            @else
              <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=35" 
                alt="Foto Profil" width="35" height="35" class="rounded-circle">
            @endif
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
            <div class="dropdown-item">
              <div class="d-flex align-items-center">
                <div class="me-3">
                  @if(Auth::user()->photo)
                    <img src="{{ asset('storage/'.Auth::user()->photo) }}" alt="Foto Profil" width="40" height="40"
                      class="rounded-circle" style="object-fit:cover;">
                  @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=40" 
                      alt="Foto Profil" width="40" height="40" class="rounded-circle">
                  @endif
                </div>
                <div>
                  <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                  <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
              </div>
            </div>
            <hr class="dropdown-divider">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
            <a class="dropdown-item d-flex align-items-center gap-2" href="#" id="logout-link">
              <span class="iconify" data-icon="mdi:logout" data-width="20" data-height="20"></span>
              Logout
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!-- Header End -->

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
      logoutLink.addEventListener('click', function (e) {
        e.preventDefault(); // Jangan langsung redirect!
        Swal.fire({
          title: 'Yakin ingin logout?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, logout!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
          }
        });
      });
    }
  });
</script>