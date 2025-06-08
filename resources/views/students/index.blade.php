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
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">Tambah
            Siswa</button>
        </div>

        <div class="table-responsive mt-4">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($students as $student)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->nisn }}</td>
                <td>{{ $student->user->name }}</td>
                <td>
                  <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                    data-bs-target="#showStudentModal{{ $student->id }}">Detail</button>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editStudentModal{{ $student->id }}">Edit</button>
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
            <option value="{{ $user->id }}" {{ $student->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}
            </option>
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
          <select name="class" class="form-select" required>
            <option value="X" {{ $student->class == 'X' ? 'selected' : '' }}>X</option>
            <option value="XI" {{ $student->class == 'XI' ? 'selected' : '' }}>XI</option>
            <option value="XII" {{ $student->class == 'XII' ? 'selected' : '' }}>XII</option>
          </select>
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
        <div class="mb-3">
          <label>Tempat Lahir</label>
          <input type="text" name="place_of_birth" class="form-control" value="{{ $student->place_of_birth }}">
        </div>
        <div class="mb-3">
          <label>Agama</label>
          <input type="text" name="religion" class="form-control" value="{{ $student->religion }}">
        </div>
        <div class="mb-3">
          <label>Provinsi</label>
          <input type="text" name="province" class="form-control" value="{{ $student->province }}">
        </div>
        <div class="mb-3">
          <label>Kabupaten</label>
          <input type="text" name="district" class="form-control" value="{{ $student->district }}">
        </div>
        <div class="mb-3">
          <label>Kecamatan</label>
          <input type="text" name="sub_district" class="form-control" value="{{ $student->sub_district }}">
        </div>
        <div class="mb-3">
          <label>Kelurahan</label>
          <input type="text" name="village" class="form-control" value="{{ $student->village }}">
        </div>
        <div class="mb-3">
          <label>Asal Sekolah</label>
          <input type="text" name="origin_school_name" class="form-control" value="{{ $student->origin_school_name }}">
        </div>
        <div class="mb-3">
          <label>Alamat Sekolah</label>
          <textarea name="origin_school_address" class="form-control">{{ $student->origin_school_address }}</textarea>
        </div>
        <div class="mb-3">
          <label>Tahun Lulus</label>
          <input type="text" name="graduation_year" class="form-control" value="{{ $student->graduation_year }}">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="showStudentModal{{ $student->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Siswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Nama</label>
          <input type="text" class="form-control" value="{{ $student->user->name }}" disabled>
        </div>
        <div class="mb-3">
          <label>NISN</label>
          <input type="text" class="form-control" value="{{ $student->nisn }}" disabled>
        </div>
        <div class="mb-3">
          <label>Jenis Kelamin</label>
          <input type="text" class="form-control" value="{{ $student->gender }}" disabled>
        </div>
        <div class="mb-3">
          <label>Kelas</label>
          <input type="text" class="form-control" value="{{ $student->class }}" disabled>
        </div>
        <div class="mb-3">
          <label>Alamat</label>
          <textarea class="form-control" disabled>{{ $student->address }}</textarea>
        </div>
        <div class="mb-3">
          <label>Tanggal Lahir</label>
          <input type="text" class="form-control" value="{{ $student->birth_date }}" disabled>
        </div>
        <div class="mb-3">
          <label>No. HP</label>
          <input type="text" class="form-control" value="{{ $student->phone }}" disabled>
        </div>
        <div class="mb-3">
          <label>Tempat Lahir</label>
          <input type="text" class="form-control" value="{{ $student->place_of_birth }}" disabled>
        </div>
        <div class="mb-3">
          <label>Agama</label>
          <input type="text" class="form-control" value="{{ $student->religion }}" disabled>
        </div>
        <div class="mb-3">
          <label>Provinsi</label>
          <input type="text" class="form-control" value="{{ $student->province }}" disabled>
        </div>
        <div class="mb-3">
          <label>Kabupaten</label>
          <input type="text" class="form-control" value="{{ $student->district }}" disabled>
        </div>
        <div class="mb-3">
          <label>Kecamatan</label>
          <input type="text" class="form-control" value="{{ $student->sub_district }}" disabled>
        </div>
        <div class="mb-3">
          <label>Kelurahan</label>
          <input type="text" class="form-control" value="{{ $student->village }}" disabled>
        </div>
        <div class="mb-3">
          <label>Asal Sekolah</label>
          <input type="text" class="form-control" value="{{ $student->origin_school_name }}" disabled>
        </div>
        <div class="mb-3">
          <label>Alamat Sekolah</label>
          <textarea class="form-control" disabled>{{ $student->origin_school_address }}</textarea>
        </div>
        <div class="mb-3">
          <label>Tahun Lulus</label>
          <input type="text" class="form-control" value="{{ $student->graduation_year }}" disabled>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
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
          <select name="class" class="form-select" required>
            <option value="" disabled selected>Pilih Kelas</option>
            <option value="X">X</option>
            <option value="XI">XI</option>
            <option value="XII">XII</option>
          </select>
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
        <div class="mb-3">
          <label>Tempat Lahir</label>
          <input type="text" name="place_of_birth" class="form-control">
        </div>
        <div class="mb-3">
          <label>Agama</label>
          <input type="text" name="religion" class="form-control">
        </div>
        <div class="mb-3">
          <label>Provinsi</label>
          <input type="text" name="province" class="form-control">
        </div>
        <div class="mb-3">
          <label>Kabupaten</label>
          <input type="text" name="district" class="form-control">
        </div>
        <div class="mb-3">
          <label>Kecamatan</label>
          <input type="text" name="sub_district" class="form-control">
        </div>
        <div class="mb-3">
          <label>Kelurahan</label>
          <input type="text" name="village" class="form-control">
        </div>
        <div class="mb-3">
          <label>Asal Sekolah</label>
          <input type="text" name="origin_school_name" class="form-control">
        </div>
        <div class="mb-3">
          <label>Alamat Sekolah</label>
          <textarea name="origin_school_address" class="form-control"></textarea>
        </div>
        <div class="mb-3">
          <label>Tahun Lulus</label>
          <input type="text" name="graduation_year" class="form-control">
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