<aside class="left-sidebar">
  <div>
    <!-- Logo diganti teks -->
    <div class="sidebar-title fw-bold fs-5 py-3 px-2 text-center" style="color:#184ebd;letter-spacing:0.5px;">
      Akademik MAPK NU Haruyan
    </div>
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <li class="nav-small-cap">
          <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
          <span class="hide-menu">Utama</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('dashboard') }}">
            <i class="ti ti-atom"></i>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        @if(auth()->user()->role === 'admin')
        <!-- Kategori Master Data -->
        <li class="nav-small-cap mt-2"><span class="hide-menu">Master Data</span></li>
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#">
            <i class="ti ti-database"></i>
            <span class="hide-menu">Manajemen</span>
          </a>
          <ul class="collapse first-level">
            <li><a class="sidebar-link" href="{{ route('users.index') }}"><i class="ti ti-users"></i> Akun</a></li>
            <li><a class="sidebar-link" href="{{ route('student-parents.index') }}"><i class="ti ti-users"></i> Orang
                Tua</a></li>
            <li><a class="sidebar-link" href="{{ route('students.index') }}"><i class="ti ti-user-check"></i> Siswa</a>
            </li>
            <li><a class="sidebar-link" href="{{ route('subjects.index') }}"><i class="ti ti-book"></i> Mata
                Pelajaran</a></li>
            <li><a class="sidebar-link" href="{{ route('scores.index') }}"><i class="ti ti-chart-bar"></i> Nilai</a>
            </li>
          </ul>
        </li>
        <!-- Kategori Akademik -->
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#">
            <i class="ti ti-school"></i>
            <span class="hide-menu">Akademik</span>
          </a>
          <ul class="collapse first-level">
            <li><a class="sidebar-link" href="{{ route('exam-cards.index') }}"><i class="ti ti-id-badge"></i> Kartu
                Ujian</a></li>
            <li><a class="sidebar-link" href="{{ route('scores.rekap') }}"><i class="ti ti-award"></i> Rekap Ranking</a>
            </li>
          </ul>
        </li>
        @endif

        @if(auth()->user()->role === 'siswa')
        <li class="nav-small-cap mt-2"><span class="hide-menu">Profil & Akademik</span></li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('student-profile') }}">
            <i class="ti ti-user"></i>
            <span class="hide-menu">Profil Saya</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#">
            <i class="ti ti-school"></i>
            <span class="hide-menu">Akademik</span>
          </a>
          <ul class="collapse first-level">
            <li><a class="sidebar-link" href="{{ route('exam-cards.show', auth()->user()->student->id) }}"><i
                  class="ti ti-id-badge"></i> Kartu Ujian</a></li>
            <li><a class="sidebar-link" href="{{ route('student-scores') }}"><i class="ti ti-chart-bar"></i> Nilai</a>
            </li>
          </ul>
        </li>
        @endif

      </ul>
    </nav>
  </div>
</aside>