@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
<!-- Row 1 -->
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen User</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <span class="iconify" data-icon="mdi:plus-circle" data-width="22"></span> Tambah User
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


        <div class="table-responsive mt-4">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr>
                <td>{{ $loop->iteration + ($users->firstItem() - 1) }}</td>
                <td class="text-center">
                  @if($user->photo)
                  <img src="{{ asset('storage/'.$user->photo) }}" alt="Foto" class="rounded-circle" width="48"
                    height="48" style="object-fit:cover;">
                  @else
                  <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=48"
                    class="rounded-circle" width="48" height="48" alt="Default">
                  @endif
                </td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'success' }}">
                    {{ ucfirst($user->role) }}
                  </span>
                </td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editUserModal{{ $user->id }}">
                    <span class="iconify" data-icon="mdi:pencil" data-width="18"></span> Edit
                  </button>
                  <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">
                    <span class="iconify" data-icon="mdi:delete" data-width="18"></span> Hapus
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $users->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit User -->
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('users.update', $user->id) }}"
      enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 text-center">
          @if($user->photo)
          <img src="{{ asset('storage/'.$user->photo) }}" alt="Foto" class="rounded-circle mb-2" width="72" height="72"
            style="object-fit:cover;">
          @else
          <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=72" class="rounded-circle mb-2"
            width="72" height="72" alt="Default">
          @endif
        </div>
        <div class="mb-3">
          <label>Foto (Opsional, untuk ganti foto)</label>
          <input type="file" name="photo" accept="image/*" class="form-control">
          <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
        </div>
        <div class="mb-3">
          <label>Nama</label>
          <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
          <label>Role</label>
          <select name="role" class="form-control" required>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Password (isi jika ingin mengubah)</label>
          <input type="password" name="password" class="form-control">
          <input type="password" name="password_confirmation" class="form-control mt-1"
            placeholder="Konfirmasi Password">
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

<!-- Modal Tambah User -->
<div class="modal fade" id="createUserModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 text-center">
          <img src="https://ui-avatars.com/api/?name=User+Baru&size=72" class="rounded-circle mb-2" width="72"
            height="72" alt="Preview" id="addPhotoPreview">
        </div>
        <div class="mb-3">
          <label>Foto (Opsional)</label>
          <input type="file" name="photo" accept="image/*" class="form-control" onchange="previewAddPhoto(event)">
        </div>
        <div class="mb-3">
          <label>Nama</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
          <input type="password" name="password_confirmation" class="form-control mt-1"
            placeholder="Konfirmasi Password">
        </div>
        <div class="mb-3">
          <label>Role</label>
          <select name="role" class="form-control" required>
            <option value="admin">Admin</option>
            <option value="siswa">Siswa</option>
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
  function confirmDelete(userId) {
    Swal.fire({
      title: 'Yakin ingin menghapus user ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  // Preview foto saat tambah user
  function previewAddPhoto(event) {
    const input = event.target;
    const reader = new FileReader();
    reader.onload = function(){
      document.getElementById('addPhotoPreview').src = reader.result;
    };
    if(input.files && input.files[0]) {
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection