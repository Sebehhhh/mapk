@extends('layouts.app')
@section('title', 'Manajemen Ekstrakurikuler')
@section('content')
<div class="row">
  <div class="col-lg-11 mx-auto">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Ekstrakurikuler</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEkstraModal">
            <span class="iconify" data-icon="mdi:plus-circle" data-width="22"></span> Tambah Ekstrakurikuler
          </button>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
          {{ session('success') }}
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
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Logo</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($extracurriculars as $ekstra)
              <tr>
                <td>{{ $loop->iteration + ($extracurriculars->firstItem() - 1) }}</td>
                <td class="text-center">
                  @if($ekstra->photo)
                  <img src="{{ asset('storage/'.$ekstra->photo) }}" alt="Logo" class="rounded" width="56" height="56" style="object-fit:cover;">
                  @else
                  <img src="https://ui-avatars.com/api/?name={{ urlencode($ekstra->name) }}&size=56"
                    class="rounded" width="56" height="56" alt="Default">
                  @endif
                </td>
                <td>{{ $ekstra->name }}</td>
                <td style="max-width:220px;">
                  <div style="white-space:pre-line;overflow:hidden;text-overflow:ellipsis;">{{ Str::limit(strip_tags($ekstra->description), 60) }}</div>
                </td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editEkstraModal{{ $ekstra->id }}">
                    <span class="iconify" data-icon="mdi:pencil" data-width="18"></span> Edit
                  </button>
                  <button class="btn btn-danger btn-sm" onclick="confirmDeleteEkstra({{ $ekstra->id }})">
                    <span class="iconify" data-icon="mdi:delete" data-width="18"></span> Hapus
                  </button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted">Belum ada data ekstrakurikuler.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $extracurriculars->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Ekstra -->
@foreach($extracurriculars as $ekstra)
<div class="modal fade" id="editEkstraModal{{ $ekstra->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('extracurriculars.update', $ekstra->id) }}"
      enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Ekstrakurikuler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 text-center">
          @if($ekstra->photo)
          <img src="{{ asset('storage/'.$ekstra->photo) }}" alt="Logo" class="rounded mb-2" width="100" height="100"
            style="object-fit:cover;">
          @else
          <img src="https://ui-avatars.com/api/?name={{ urlencode($ekstra->name) }}&size=100"
            class="rounded mb-2" width="100" height="100" alt="Default">
          @endif
        </div>
        <div class="mb-3">
          <label>Logo (Opsional, untuk ganti foto)</label>
          <input type="file" name="photo" accept="image/*" class="form-control">
          <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
        </div>
        <div class="mb-3">
          <label>Nama Ekstrakurikuler</label>
          <input type="text" name="name" class="form-control" value="{{ $ekstra->name }}" required>
        </div>
        <div class="mb-3">
          <label>Deskripsi</label>
          <textarea name="description" class="form-control" rows="3" required>{{ $ekstra->description }}</textarea>
        </div>
        {{-- Tidak ada field is_active, jadi tidak perlu input/select untuk is_active di sini --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>
@endforeach

<!-- Modal Tambah Ekstra -->
<div class="modal fade" id="createEkstraModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('extracurriculars.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Ekstrakurikuler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 text-center">
          <img src="https://ui-avatars.com/api/?name=Ekstra+Baru&size=100" class="rounded mb-2" width="100"
            height="100" alt="Preview" id="addEkstraPhotoPreview">
        </div>
        <div class="mb-3">
          <label>Logo (Opsional)</label>
          <input type="file" name="photo" accept="image/*" class="form-control" onchange="previewAddEkstraPhoto(event)">
        </div>
        <div class="mb-3">
          <label>Nama Ekstrakurikuler</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Deskripsi</label>
          <textarea name="description" class="form-control" rows="3" required></textarea>
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
  function confirmDeleteEkstra(ekstraId) {
    Swal.fire({
      title: 'Yakin ingin menghapus ekstrakurikuler ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/extracurriculars/${ekstraId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Preview foto saat tambah ekstrakurikuler
  function previewAddEkstraPhoto(event) {
    const input = event.target;
    const reader = new FileReader();
    reader.onload = function(){
      document.getElementById('addEkstraPhotoPreview').src = reader.result;
    };
    if(input.files && input.files[0]) {
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection