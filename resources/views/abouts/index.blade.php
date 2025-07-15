@extends('layouts.app')
@section('title', 'Manajemen Tentang Sekolah')
@section('content')
<div class="row">
  <div class="col-lg-12 mx-auto">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Tentang Sekolah</h4>
          @if(!$about)
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAboutModal">
            <span class="iconify" data-icon="mdi:plus-circle" data-width="22"></span> Tambah Profil
          </button>
          @endif
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="table-responsive mt-4">
          <table class="table table-bordered align-middle text-center">
            <thead>
              <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Alamat</th>
                <th>Email</th>
                <th>Instagram</th>
                <th>Akreditasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @if($about)
              <tr>
                <td>
                  @if($about->photo)
                  <img src="{{ asset('storage/'.$about->photo) }}" alt="Foto" class="rounded" width="60" height="60"
                    style="object-fit:cover;">
                  @else
                  <img src="https://ui-avatars.com/api/?name={{ urlencode($about->title) }}&size=60" class="rounded"
                    width="60" height="60" alt="Avatar">
                  @endif
                </td>
                <td class="text-start">{{ $about->title }}</td>
                <td class="text-start" style="max-width:200px;">
                  <div style="white-space:pre-line; overflow:hidden; text-overflow:ellipsis;">
                    {{ Str::limit(strip_tags($about->description), 60) }}
                  </div>
                </td>
                <td class="text-start">{{ $about->address ?? '-' }}</td>
                <td>{{ $about->email ?? '-' }}</td>
                <td>{{ $about->instagram ?? '-' }}</td>
                <td>{{ $about->akreditasi ?? '-' }}</td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAboutModal">
                    <span class="iconify" data-icon="mdi:pencil" data-width="18"></span>
                  </button>
                </td>
              </tr>
              @else
              <tr>
                <td colspan="8" class="text-center text-muted">
                  Belum ada data profil sekolah.
                </td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@if(!$about)
<!-- Modal: Tambah Profil Sekolah -->
<div class="modal fade" id="createAboutModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('abouts.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Profil Sekolah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <div class="col-md-4 text-center">
            <img src="https://ui-avatars.com/api/?name=Profil+Sekolah&size=100" class="rounded mb-2" width="100"
              height="100" alt="Avatar" id="createAboutPhotoPreview">
            <input type="file" name="photo" accept="image/*" class="form-control mt-2"
              onchange="previewAboutPhoto(event,'createAboutPhotoPreview')">
            <small class="text-muted">Upload Gambar (opsional)</small>
          </div>
          <div class="col-md-8">
            <div class="mb-3">
              <label>Judul / Nama Sekolah</label>
              <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Deskripsi</label>
              <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label>Alamat</label>
              <input type="text" name="address" class="form-control">
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label>Instagram</label>
              <input type="text" name="instagram" class="form-control">
            </div>
            <div class="mb-3">
              <label>Akreditasi</label>
              <input type="text" name="akreditasi" class="form-control">
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endif

@if($about)
<!-- Modal: Edit Profil Sekolah -->
<div class="modal fade" id="editAboutModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('abouts.update', $about->id) }}"
      enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Profil Sekolah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <div class="col-md-4 text-center">
            @if($about->photo)
            <img src="{{ asset('storage/'.$about->photo) }}" class="rounded mb-2" width="100" height="100"
              style="object-fit:cover;" id="editAboutPhotoPreview">
            @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($about->title) }}&size=100" class="rounded mb-2"
              width="100" height="100" alt="Avatar" id="editAboutPhotoPreview">
            @endif
            <input type="file" name="photo" accept="image/*" class="form-control mt-2"
              onchange="previewAboutPhoto(event,'editAboutPhotoPreview')">
            <small class="text-muted">Upload Gambar (opsional)</small>
          </div>
          <div class="col-md-8">
            <div class="mb-3">
              <label>Judul / Nama Sekolah</label>
              <input type="text" name="title" class="form-control" value="{{ $about->title }}" required>
            </div>
            <div class="mb-3">
              <label>Deskripsi</label>
              <textarea name="description" class="form-control" rows="4" required>{{ $about->description }}</textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label>Alamat</label>
              <input type="text" name="address" class="form-control" value="{{ $about->address }}">
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" value="{{ $about->email }}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label>Instagram</label>
              <input type="text" name="instagram" class="form-control" value="{{ $about->instagram }}">
            </div>
            <div class="mb-3">
              <label>Akreditasi</label>
              <input type="text" name="akreditasi" class="form-control" value="{{ $about->akreditasi }}">
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>
@endif

<!-- Iconify -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<script>
  function previewAboutPhoto(event, previewId) {
    const reader = new FileReader();
    reader.onload = () => document.getElementById(previewId).src = reader.result;
    if (event.target.files && event.target.files[0]) {
      reader.readAsDataURL(event.target.files[0]);
    }
  }
</script>
@endsection