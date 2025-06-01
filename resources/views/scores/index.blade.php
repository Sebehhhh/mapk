@extends('layouts.app')
@section('title', 'Manajemen Nilai Siswa')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Nilai Siswa</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createScoreModal">Tambah Nilai</button>
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
                <th>Nama Siswa</th>
                <th>Mata Pelajaran</th>
                <th>Semester</th>
                <th>Level Kelas</th>
                <th>Kehadiran</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($scores as $score)
              <tr>
                <td>{{ $loop->iteration + ($scores->currentPage() - 1) * $scores->perPage() }}</td>
                <td>{{ $score->student->user->name ?? 'N/A' }}</td> {{-- Mengakses nama siswa --}}
                <td>{{ $score->subject->name ?? 'N/A' }}</td> {{-- Mengakses nama mata pelajaran --}}
                <td>{{ $score->semester }}</td>
                <td>{{ $score->subject->class_level }}</td>
                <td>{{ $score->attendance }}</td>
                <td>{{ $score->assignment }}</td>
                <td>{{ $score->mid_exam }}</td>
                <td>{{ $score->final_exam }}</td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editScoreModal{{ $score->id }}">Edit</button>
                  <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $score->id }})">Hapus</button>
                </td>
              </tr>
              @empty
              <tr>
                  <td colspan="12" class="text-center">Tidak ada data nilai siswa.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $scores->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
@foreach($scores as $score)
<div class="modal fade" id="editScoreModal{{ $score->id }}" tabindex="-1" aria-labelledby="editScoreModalLabel{{ $score->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('scores.update', $score->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editScoreModalLabel{{ $score->id }}">Edit Nilai</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="student_id{{ $score->id }}" class="form-label">Nama Siswa</label>
          <select name="student_id" class="form-control" id="student_id{{ $score->id }}" required>
            <option value="">Pilih Siswa</option>
            @foreach($students as $student)
            <option value="{{ $student->id }}" {{ $score->student_id == $student->id ? 'selected' : '' }}>
              {{ $student->nisn }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="subject_id{{ $score->id }}" class="form-label">Mata Pelajaran</label>
          <select name="subject_id" class="form-control" id="subject_id{{ $score->id }}" required>
            <option value="">Pilih Mata Pelajaran</option>
            @foreach($subjects as $subject)
            <option value="{{ $subject->id }}" {{ $score->subject_id == $subject->id ? 'selected' : '' }}>
              {{ $subject->name }} ({{ $subject->class_level }})
            </option>
            @endforeach
          </select>
        </div>
      
        <div class="mb-3">
          <label for="attendance{{ $score->id }}" class="form-label">Kehadiran (%)</label>
          <input type="number" name="attendance" class="form-control" id="attendance{{ $score->id }}" value="{{ $score->attendance }}" min="0" max="100" required>
        </div>
        <div class="mb-3">
          <label for="assignment{{ $score->id }}" class="form-label">Nilai Tugas</label>
          <input type="number" name="assignment" class="form-control" id="assignment{{ $score->id }}" value="{{ $score->assignment }}" min="0" max="100" required>
        </div>
        <div class="mb-3">
          <label for="mid_exam{{ $score->id }}" class="form-label">Nilai UTS</label>
          <input type="number" name="mid_exam" class="form-control" id="mid_exam{{ $score->id }}" value="{{ $score->mid_exam }}" min="0" max="100" required>
        </div>
        <div class="mb-3">
          <label for="final_exam{{ $score->id }}" class="form-label">Nilai UAS</label>
          <input type="number" name="final_exam" class="form-control" id="final_exam{{ $score->id }}" value="{{ $score->final_exam }}" min="0" max="100" required>
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
<div class="modal fade" id="createScoreModal" tabindex="-1" aria-labelledby="createScoreModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('scores.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="createScoreModalLabel">Tambah Nilai</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="student_id" class="form-label">Nama Siswa</label>
          <select name="student_id" class="form-control" id="student_id" required>
            <option value="">Pilih Siswa</option>
            @foreach($students as $student)
            <option value="{{ $student->id }}">{{ $student->nisn }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="subject_id" class="form-label">Mata Pelajaran</label>
          <select name="subject_id" class="form-control" id="subject_id" required>
            <option value="">Pilih Mata Pelajaran</option>
            @foreach($subjects as $subject)
            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->class_level }}) - Semester {{ $subject->semester }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="attendance" class="form-label">Kehadiran (%)</label>
          <input type="number" name="attendance" class="form-control" id="attendance" min="0" max="100" placeholder="0-100" required>
        </div>
        <div class="mb-3">
          <label for="assignment" class="form-label">Nilai Tugas</label>
          <input type="number" name="assignment" class="form-control" id="assignment" min="0" max="100" placeholder="0-100" required>
        </div>
        <div class="mb-3">
          <label for="mid_exam" class="form-label">Nilai UTS</label>
          <input type="number" name="mid_exam" class="form-control" id="mid_exam" min="0" max="100" placeholder="0-100" required>
        </div>
        <div class="mb-3">
          <label for="final_exam" class="form-label">Nilai UAS</label>
          <input type="number" name="final_exam" class="form-control" id="final_exam" min="0" max="100" placeholder="0-100" required>
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
  function confirmDelete(scoreId) {
    Swal.fire({
      title: 'Yakin ingin menghapus nilai ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/scores/${scoreId}`; // Menggunakan rute scores
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
</script>
@endsection
