@extends('layouts.app')
@section('title', 'Manajemen Nilai Siswa')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Manajemen Nilai Siswa</h4>

          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pickStudentModal">
            Tambah Nilai
          </button>
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

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form method="GET" class="row g-2 mb-3 mt-3">
          <div class="col-md-3">
            <select name="student_id" class="form-select">
              <option value="">Semua Siswa</option>
              @foreach($students as $student)
              <option value="{{ $student->id }}" {{ request('student_id')==$student->id ? 'selected' : '' }}>
                {{ $student->user->name ?? 'N/A' }} ({{ $student->nisn }})
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <select name="semester" class="form-select">
              <option value="">Semua Semester</option>
              <option value="ganjil" {{ request('semester')=='ganjil' ? 'selected' : '' }}>Ganjil</option>
              <option value="genap" {{ request('semester')=='genap' ? 'selected' : '' }}>Genap</option>
            </select>
          </div>
          <div class="col-md-2">
            <select name="class_level" class="form-select">
              <option value="">Semua Kelas</option>
              @foreach(['X','XI','XII'] as $kelas)
              <option value="{{ $kelas }}" {{ request('class_level')==$kelas ? 'selected' : '' }}>{{ $kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="subject_id" class="form-select">
              <option value="">Semua Mapel</option>
              @foreach($subjects as $subject)
              <option value="{{ $subject->id }}" {{ request('subject_id')==$subject->id ? 'selected' : '' }}>
                {{ $subject->name }} ({{ $subject->class_level }})
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-success" name="filter_table" value="1">
              <span class="iconify" data-icon="mdi:magnify" data-width="20"></span> Filter
            </button>
          </div>
        </form>


        {{-- Jika sudah pilih siswa & semester, tampilkan form input nilai --}}
        @if(request('student_id') && request('semester'))
        <div class="card mt-4 mb-4">
          <div class="card-header fw-bold">Input Nilai Siswa</div>
          <div class="card-body">
            <form method="POST" action="{{ route('scores.store-multi') }}">
              @csrf
              <input type="hidden" name="student_id" value="{{ request('student_id') }}">
              <input type="hidden" name="semester" value="{{ request('semester') }}">

              <div class="mb-2">
                <span>
                  <strong>Siswa:</strong>
                  {{ optional($students->where('id', request('student_id'))->first())->user->name ?? '-' }}
                  <span class="mx-2">|</span>
                  <strong>Semester:</strong> {{ ucfirst(request('semester')) }}
                </span>
              </div>



              <div class="table-responsive">
                <table class="table table-bordered align-middle">
                  <thead>
                    <tr>
                      <th>Nama Mata Pelajaran</th>
                      <th>Kelas</th>
                      <th>Kehadiran (%)</th>
                      <th>Tugas</th>
                      <th>UTS</th>
                      <th>UAS</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($mapels as $mapel)
                    <tr>
                      <td>
                        {{ $mapel->name }}
                        <input type="hidden" name="scores[{{ $mapel->id }}][subject_id]" value="{{ $mapel->id }}">
                      </td>
                      <td>{{ $mapel->class_level }}</td>
                      <td>
                        <input type="number" name="scores[{{ $mapel->id }}][attendance]" min="0" max="100"
                          class="form-control" required>
                      </td>
                      <td>
                        <input type="number" name="scores[{{ $mapel->id }}][assignment]" min="0" max="100"
                          class="form-control" required>
                      </td>
                      <td>
                        <input type="number" name="scores[{{ $mapel->id }}][mid_exam]" min="0" max="100"
                          class="form-control" required>
                      </td>
                      <td>
                        <input type="number" name="scores[{{ $mapel->id }}][final_exam]" min="0" max="100"
                          class="form-control" required>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="6" class="text-center">Tidak ada mapel yang diambil siswa pada semester ini.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              @if(count($mapels) > 0)
              <button type="submit" class="btn btn-success">Simpan Semua Nilai</button>
              @endif
              <a href="{{ route('scores.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </form>
          </div>
        </div>
        @endif

        {{-- Table Nilai Siswa --}}
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
                <td>{{ $score->student->user->name ?? 'N/A' }}</td>
                <td>{{ $score->subject->name ?? 'N/A' }}</td>
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

{{-- Modal Pilih Siswa & Semester --}}
<div class="modal fade" id="pickStudentModal" tabindex="-1" aria-labelledby="pickStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="GET" action="{{ route('scores.index') }}">
      <div class="modal-header">
        <h5 class="modal-title" id="pickStudentModalLabel">Pilih Siswa & Semester</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="student_id_pick" class="form-label">Nama Siswa</label>
          <select name="student_id" class="form-select" id="student_id_pick" required>
            <option value="">Pilih Siswa</option>
            @foreach($students as $student)
            <option value="{{ $student->id }}" {{ request('student_id')==$student->id ? 'selected' : '' }}>
              {{ $student->user->name ?? 'N/A' }} ({{ $student->nisn }})
            </option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="semester_pick" class="form-label">Semester</label>
          <select name="semester" class="form-select" id="semester_pick" required>
            <option value="">Pilih Semester</option>
            <option value="ganjil" {{ request('semester')=='ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="genap" {{ request('semester')=='genap' ? 'selected' : '' }}>Genap</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Lanjut</button>
      </div>
    </form>
  </div>
</div>
{{-- Modal Edit Nilai --}}
@foreach($scores as $score)
<div class="modal fade" id="editScoreModal{{ $score->id }}" tabindex="-1"
  aria-labelledby="editScoreModalLabel{{ $score->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('scores.update', $score->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editScoreModalLabel{{ $score->id }}">Edit Nilai</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        {{-- TAMBAHKAN 2 HIDDEN INPUT INI --}}
        <input type="hidden" name="student_id" value="{{ $score->student_id }}">
        <input type="hidden" name="subject_id" value="{{ $score->subject_id }}">

        <div class="mb-3">
          <label class="form-label">Kehadiran (%)</label>
          <input type="number" name="attendance" class="form-control" value="{{ $score->attendance }}" min="0" max="100"
            required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nilai Tugas</label>
          <input type="number" name="assignment" class="form-control" value="{{ $score->assignment }}" min="0" max="100"
            required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nilai UTS</label>
          <input type="number" name="mid_exam" class="form-control" value="{{ $score->mid_exam }}" min="0" max="100"
            required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nilai UAS</label>
          <input type="number" name="final_exam" class="form-control" value="{{ $score->final_exam }}" min="0" max="100"
            required>
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
        form.action = `/scores/${scoreId}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
</script>
@endsection