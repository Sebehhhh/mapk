@extends('layouts.app')
@section('title', 'Manajemen Pengumuman')
@section('content')
<!-- Row 1 -->
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Pengumuman</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPengumumanModal">
            <span class="iconify" data-icon="mdi:plus-circle" data-width="22"></span> Tambah Pengumuman
          </button>
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
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Isi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pengumuman as $item)
              <tr>
                <td>{{ $loop->iteration + ($pengumuman->firstItem() - 1) }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                <td>{{ $item->isi }}</td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPengumumanModal{{ $item->id }}">
                    <span class="iconify" data-icon="mdi:pencil" data-width="18"></span> Edit
                  </button>
                  <button class="btn btn-danger btn-sm" onclick="confirmDeletePengumuman({{ $item->id }})">
                    <span class="iconify" data-icon="mdi:delete" data-width="18"></span> Hapus
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $pengumuman->links('pagination::bootstrap-4') }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Pengumuman -->
@foreach($pengumuman as $item)
<div class="modal fade" id="editPengumumanModal{{ $item->id }}" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('pengumuman.update', $item->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Pengumuman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Judul</label>
          <input type="text" name="judul" class="form-control" value="{{ $item->judul }}" required>
        </div>
        <div class="mb-3">
          <label>Tanggal</label>
          <input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal }}" required>
        </div>
        <div class="mb-3">
          <label>Isi</label>
          <textarea name="isi" class="form-control" required>{{ $item->isi }}</textarea>
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

<!-- Modal Tambah Pengumuman -->
<div class="modal fade" id="createPengumumanModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('pengumuman.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pengumuman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Judul</label>
          <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Tanggal</label>
          <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Isi</label>
          <textarea name="isi" class="form-control" required></textarea>
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
  function confirmDeletePengumuman(id) {
    Swal.fire({
      title: 'Yakin ingin menghapus pengumuman ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/pengumuman/${id}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
</script>
@endsection