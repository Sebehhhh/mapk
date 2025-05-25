@extends('layouts.app')
@section('title', 'Manajemen Siswa')
@section('content')

<!-- Row -->
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Siswa</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">Tambah Siswa</button>
        </div>

        <div class="table-responsive mt-4">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>NISN</th>
                <th>Jenis Kelamin</th>
                <th>Kelas</th>
                <th>No. HP</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($students as $student)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->user->name }}</td>
                <td>{{ $student->nisn }}</td>
                <td>{{ $student->gender }}</td>
                <td>{{ $student->class }}</td>
                <td>{{ $student->phone }}</td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}">Edit</button>
                  <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $student->id }})">Hapus</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $students->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit -->
@foreach($students as $student)
<div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('students.update', $student->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Siswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>User</label>
          <select name="user_id" class="form-control" required>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $student->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label>NISN</label>
          <input type="text" name="nisn" class="form-control" value="{{ $student->nisn }}" required>
        </div>
        <div class="mb-3">
          <label>Jenis Kelamin</label>
          <select name="gender" class="form-control" required>
            <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Kelas</label>
          <input type="text" name="class" class="form-control" value="{{ $student->class }}" required>
        </div>
        <div class="mb-3">
          <label>Alamat</label>
          <textarea name="address" class="form-control">{{ $student->address }}</textarea>
        </div>
        <div class="mb-3">
          <label>Tanggal Lahir</label>
          <input type="date" name="birth_date" class="form-control" value="{{ $student->birth_date }}">
        </div>
        <div class="mb-3">
          <label>No. HP</label>
          <input type="text" name="phone" class="form-control" value="{{ $student->phone }}">
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

<!-- Modal Tambah -->
<div class="modal fade" id="createStudentModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('students.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Siswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>User</label>
          <select name="user_id" class="form-control" required>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label>NISN</label>
          <input type="text" name="nisn" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Jenis Kelamin</label>
          <select name="gender" class="form-control" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Kelas</label>
          <input type="text" name="class" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Alamat</label>
          <textarea name="address" class="form-control"></textarea>
        </div>
        <div class="mb-3">
          <label>Tanggal Lahir</label>
          <input type="date" name="birth_date" class="form-control">
        </div>
        <div class="mb-3">
          <label>No. HP</label>
          <input type="text" name="phone" class="form-control">
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
<script>
  function confirmDelete(studentId) {
    Swal.fire({
      title: 'Yakin ingin menghapus siswa ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/students/${studentId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
</script>
@endsection