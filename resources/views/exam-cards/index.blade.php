@extends('layouts.app')
@section('title', 'Manajemen Kartu Ujian')
@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Kartu Ujian</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createExamCardModal">Tambah Kartu Ujian</button>
        </div>

        <div class="table-responsive mt-4">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Jenis Ujian</th>
                <th>Semester</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($examCards as $examCard)
              <tr>
                <td>{{ $loop->iteration + ($examCards->currentPage() - 1) * $examCards->perPage() }}</td>
                <td>{{ $examCard->student->user->name ?? 'N/A' }}</td>
                <td>{{ $examCard->exam_type }}</td>
                <td>{{ $examCard->semester }}</td>
                <td>{{ $examCard->year }}</td>
                <td>{{ $examCard->status_label }}</td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editExamCardModal{{ $examCard->id }}">Edit</button>
                  <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $examCard->id }})">Hapus</button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center">Belum ada data kartu ujian.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $examCards->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createExamCardModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('exam-cards.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kartu Ujian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Nama Siswa</label>
          <select name="student_id" class="form-control" required>
            <option value="">Pilih Siswa</option>
            @foreach($students as $student)
              <option value="{{ $student->id }}">{{ $student->user->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label>Jenis Ujian</label>
          <select name="exam_type" class="form-control" required>
            <option value="UTS">UTS</option>
            <option value="UAS">UAS</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Semester</label>
          <select name="semester" class="form-control" required>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Tahun</label>
          <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
        </div>
        <div class="mb-3">
          <label>Status</label>
          <select name="status" class="form-control" required>
            <option value="1">Aktif</option>
            <option value="0" selected>Tidak Aktif</option>
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

<!-- Modal Edit -->
@foreach($examCards as $examCard)
<div class="modal fade" id="editExamCardModal{{ $examCard->id }}" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('exam-cards.update', $examCard->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Kartu Ujian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Nama Siswa</label>
          <select name="student_id" class="form-control" required>
            @foreach($students as $student)
              <option value="{{ $student->id }}" {{ $examCard->student_id == $student->id ? 'selected' : '' }}>
                {{ $student->user->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label>Jenis Ujian</label>
          <select name="exam_type" class="form-control" required>
            <option value="UTS" {{ $examCard->exam_type == 'UTS' ? 'selected' : '' }}>UTS</option>
            <option value="UAS" {{ $examCard->exam_type == 'UAS' ? 'selected' : '' }}>UAS</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Semester</label>
          <select name="semester" class="form-control" required>
            <option value="Ganjil" {{ $examCard->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="Genap" {{ $examCard->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Tahun</label>
          <input type="number" name="year" class="form-control" value="{{ $examCard->year }}" required>
        </div>
        <div class="mb-3">
          <label>Status</label>
          <select name="status" class="form-control" required>
            <option value="1" {{ $examCard->status == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $examCard->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function confirmDelete(id) {
    Swal.fire({
      title: 'Yakin ingin menghapus kartu ujian ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/exam-cards/${id}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
</script>
@endsection