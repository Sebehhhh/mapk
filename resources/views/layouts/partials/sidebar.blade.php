<aside class="left-sidebar">
  <div>
    <!-- Logo -->
    <div class="sidebar-title py-3 px-2"
      style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; margin: 10px;">
      <div class="d-flex align-items-center justify-content-start">
        <img src="{{ asset('assets/images/logos/logo.jpeg') }}" alt="Logo MAPK NU Haruyan" class="img-fluid me-2"
          style="max-height: 60px;">
        <h4 class="hide-menu mb-0"
          style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.25rem; color: white; letter-spacing: 0.5px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
          MAPK NU Haruyan</h4>
      </div>
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
          </ul>
        </li>
        <!-- Kategori Akademik -->
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow" href="#">
            <i class="ti ti-school"></i>
            <span class="hide-menu">Akademik</span>
          </a>
          <ul class="collapse first-level">
            <li><a class="sidebar-link" href="{{ route('subject-users.index') }}"><i class="ti ti-list-check"></i> Mapel
                Siswa</a></li>
            <li><a class="sidebar-link" href="{{ route('scores.index') }}"><i class="ti ti-chart-bar"></i> Nilai</a>
            </li>
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
            <li><a class="sidebar-link" href="{{ route('subjects.subject') }}"><i class="ti ti-book"></i> Mata
                Pelajaran</a>
            </li>
          </ul>
        </li>
        @endif

      </ul>
    </nav>
  </div>
</aside>