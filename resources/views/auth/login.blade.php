<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Sistem Informasi Akademik MAPK NU Haruyan</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />

  {{-- SweetAlert2 CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-sm-10 col-12 col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0 shadow-lg border-0 rounded-4">
              <div class="card-body" style="background: linear-gradient(135deg,#f6f7fb 60%,#e0eafc 100%);">
                </a>
                <h4 class="text-center fw-bolder mb-4" style="color:#184ebd;letter-spacing:0.5px;">Sistem Informasi
                  Akademik<br>MAPK NU Haruyan</h4>

                <form method="POST" action="{{ route('login') }}">
                  @csrf

                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                      name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                  </div>

                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                      name="password" required autocomplete="current-password">
                    @error('password')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                  </div>


                  <button type="submit" class="btn btn-primary w-100 py-3 fs-5 mb-4 rounded-3"
                    style="transition:background 0.2s;">Masuk</button>

                  <div class="alert alert-info mt-2 text-center rounded-3" style="font-size:1.07rem;">
                    Belum punya akun? <b>Hubungi Admin</b>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

  <script>
    @if (session('status'))
      Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: "{{ session('status') }}",
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
      });
    @endif
  </script>

  <script>
    document.querySelector('button[type="submit"]').addEventListener('mouseover', function() {
      this.style.background='#184ebd';
    });
    document.querySelector('button[type="submit"]').addEventListener('mouseout', function() {
      this.style.background='';
    });
  </script>
</body>

</html>