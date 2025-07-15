@extends('layouts.app')
@section('title', 'Manajemen Hero')
@section('content')
<!-- Row 1 -->
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Hero</h4>
          @if($heroes->count() < 1)
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createHeroModal">
            <span class="iconify" data-icon="mdi:plus-circle" data-width="22"></span> Tambah Hero
          </button>
          @endif
        </div>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive mt-4">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($heroes as $hero)
              <tr>
                <td>{{ $loop->iteration + ($heroes->firstItem() - 1) }}</td>
                <td class="text-center">
                  @if($hero->photo)
                  <img src="{{ asset('storage/'.$hero->photo) }}" alt="Foto" class="rounded" width="60"
                    height="36" style="object-fit:cover;">
                  @else
                  <img src="https://ui-avatars.com/api/?name={{ urlencode($hero->title) }}&size=60"
                    class="rounded" width="60" height="36" alt="Default">
                  @endif
                </td>
                <td>{{ $hero->title }}</td>
                <td style="max-width:220px;">
                  <div style="white-space:pre-line;overflow:hidden;text-overflow:ellipsis;">{{ Str::limit(strip_tags($hero->description), 60) }}</div>
                </td>
                <td>
                  <span class="badge bg-{{ $hero->is_active ? 'success' : 'secondary' }}">
                    {{ $hero->is_active ? 'Aktif' : 'Tidak Aktif' }}
                  </span>
                </td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editHeroModal{{ $hero->id }}">
                    <span class="iconify" data-icon="mdi:pencil" data-width="18"></span> Edit
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $heroes->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Hero -->
@foreach($heroes as $hero)
<div class="modal fade" id="editHeroModal{{ $hero->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('heroes.update', $hero->id) }}"
      enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Hero</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 text-center">
          @if($hero->photo)
          <img src="{{ asset('storage/'.$hero->photo) }}" alt="Foto" class="rounded mb-2" width="100" height="60"
            style="object-fit:cover;">
          @else
          <img src="https://ui-avatars.com/api/?name={{ urlencode($hero->title) }}&size=100"
            class="rounded mb-2" width="100" height="60" alt="Default">
          @endif
        </div>
        <div class="mb-3">
          <label>Foto (Opsional, untuk ganti foto)</label>
          <input type="file" name="photo" accept="image/*" class="form-control">
          <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
        </div>
        <div class="mb-3">
          <label>Judul</label>
          <input type="text" name="title" class="form-control" value="{{ $hero->title }}" required>
        </div>
        <div class="mb-3">
          <label>Deskripsi</label>
          <textarea name="description" class="form-control" rows="3" required>{{ $hero->description }}</textarea>
        </div>
        <div class="mb-3">
          <label>Status Hero</label>
          <select name="is_active" class="form-control" required>
            <option value="1" {{ $hero->is_active ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ !$hero->is_active ? 'selected' : '' }}>Tidak Aktif</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>
@endforeach

<!-- Modal Tambah Hero -->
<div class="modal fade" id="createHeroModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('heroes.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Hero</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 text-center">
          <img src="https://ui-avatars.com/api/?name=Hero+Baru&size=100" class="rounded mb-2" width="100"
            height="60" alt="Preview" id="addHeroPhotoPreview">
        </div>
        <div class="mb-3">
          <label>Foto (Opsional)</label>
          <input type="file" name="photo" accept="image/*" class="form-control" onchange="previewAddHeroPhoto(event)">
        </div>
        <div class="mb-3">
          <label>Judul</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Deskripsi</label>
          <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label>Status Hero</label>
          <select name="is_active" class="form-control" required>
            <option value="1">Aktif</option>
            <option value="0">Tidak Aktif</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Iconify -->
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<script>
  function confirmDeleteHero(heroId) {
    Swal.fire({
      title: 'Yakin ingin menghapus Hero ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/heroes/${heroId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Preview foto saat tambah hero
  function previewAddHeroPhoto(event) {
    const input = event.target;
    const reader = new FileReader();
    reader.onload = function(){
      document.getElementById('addHeroPhotoPreview').src = reader.result;
    };
    if(input.files && input.files[0]) {
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection