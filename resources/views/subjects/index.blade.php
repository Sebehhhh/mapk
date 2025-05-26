@extends('layouts.app')
@section('title', 'Manajemen Mata Pelajaran')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Mata Pelajaran</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">Tambah Mata Pelajaran</button>
        </div>

        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive mt-4">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Mata Pelajaran</th>
                <th>Semester</th>
                <th>Level Kelas</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($subjects as $subject)
              <tr>
                <td>{{ $loop->iteration + ($subjects->currentPage() - 1) * $subjects->perPage() }}</td>
                <td>{{ $subject->name }}</td>
                <td>{{ $subject->semester }}</td>
                <td>{{ $subject->class_level }}</td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editSubjectModal{{ $subject->id }}">Edit</button>
                  <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $subject->id }})">Hapus</button>
                </td>
              </tr>
              @empty
              <tr>
                  <td colspan="5" class="text-center">Tidak ada data mata pelajaran.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $subjects->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
@foreach($subjects as $subject)
<div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="editSubjectModalLabel{{ $subject->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('subjects.update', $subject->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editSubjectModalLabel{{ $subject->id }}">Edit Mata Pelajaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="name{{ $subject->id }}" class="form-label">Nama Mata Pelajaran</label>
          <input type="text" name="name" class="form-control" id="name{{ $subject->id }}" value="{{ $subject->name }}" required>
        </div>
        <div class="mb-3">
          <label for="semester{{ $subject->id }}" class="form-label">Semester</label>
          <select name="semester" class="form-control" id="semester{{ $subject->id }}" required>
            <option value="Ganjil" {{ $subject->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="Genap" {{ $subject->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
            {{-- Tambahkan opsi semester lain jika diperlukan --}}
          </select>
        </div>
        <div class="mb-3">
          <label for="class_level{{ $subject->id }}" class="form-label">Level Kelas</label>
          <select name="class_level" class="form-control" id="class_level{{ $subject->id }}" required>
            <option value="X" {{ $subject->class_level == 'X' ? 'selected' : '' }}>X</option>
            <option value="XI" {{ $subject->class_level == 'XI' ? 'selected' : '' }}>XI</option>
            <option value="XII" {{ $subject->class_level == 'XII' ? 'selected' : '' }}>XII</option>
            {{-- Tambahkan opsi level kelas lain jika diperlukan (misal: 7, 8, 9 untuk SMP) --}}
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

{{-- Modal Tambah --}}
<div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('subjects.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="createSubjectModalLabel">Tambah Mata Pelajaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="name" class="form-label">Nama Mata Pelajaran</label>
          <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
          <label for="semester" class="form-label">Semester</label>
          <select name="semester" class="form-control" id="semester" required>
            <option value="">Pilih Semester</option>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
            {{-- Tambahkan opsi semester lain jika diperlukan --}}
          </select>
        </div>
        <div class="mb-3">
          <label for="class_level" class="form-label">Level Kelas</label>
          <select name="class_level" class="form-control" id="class_level" required>
            <option value="">Pilih Level Kelas</option>
            <option value="X">X</option>
            <option value="XI">XI</option>
            <option value="XII">XII</option>
            {{-- Tambahkan opsi level kelas lain jika diperlukan --}}
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmDelete(subjectId) {
    Swal.fire({
      title: 'Yakin ingin menghapus mata pelajaran ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/subjects/${subjectId}`; // Menggunakan rute subjects
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
</script>
@endsection
