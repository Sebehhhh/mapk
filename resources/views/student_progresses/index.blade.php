@extends('layouts.app')
@section('title', 'Progress Siswa')
@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between">
          <h4 class="card-title">Progress Siswa</h4>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProgressModal">
            <span class="iconify" data-icon="mdi:plus-circle" data-width="22"></span> Tambah Progress
          </button>
        </div>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- FILTER & SEARCH -->
        <form method="GET" class="row align-items-end g-2 mt-4 mb-2">
          <div class="col-md-3">
            <label class="form-label">Kelas</label>
            <select name="class_level" class="form-select">
              <option value="">Semua Kelas</option>
              <option value="X" {{ request('class_level')=='X' ? 'selected' : '' }}>X</option>
              <option value="XI" {{ request('class_level')=='XI' ? 'selected' : '' }}>XI</option>
              <option value="XII" {{ request('class_level')=='XII' ? 'selected' : '' }}>XII</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Semester</label>
            <select name="semester" class="form-select">
              <option value="">Semua Semester</option>
              <option value="ganjil" {{ request('semester')=='ganjil' ? 'selected' : '' }}>Ganjil</option>
              <option value="genap" {{ request('semester')=='genap' ? 'selected' : '' }}>Genap</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Tahun Ajaran</label>
            <input type="text" name="year" class="form-control" placeholder="ex: 2024/2025" value="{{ request('year') }}">
          </div>
          <div class="col-md-2">
            <label class="form-label">Cari Siswa</label>
            <input type="text" name="q" class="form-control" placeholder="Nama siswa..." value="{{ request('q') }}">
          </div>
          <div class="col-md-2 d-grid">
            <button type="submit" class="btn btn-success mt-2 mt-md-0">
              <span class="iconify" data-icon="mdi:magnify" data-width="20"></span> Filter
            </button>
            @if(request('class_level')||request('semester')||request('year')||request('q'))
            <a href="{{ route('student-progresses.index') }}" class="btn btn-outline-success mt-2">Reset</a>
            @endif
          </div>
        </form>
        <!-- END FILTER -->

        <div class="table-responsive mt-4">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Semester</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($progresses as $progress)
              <tr>
                <td>{{ $loop->iteration + ($progresses->firstItem() - 1) }}</td>
                <td>
                  @if($progress->student->user?->photo)
                  <img src="{{ asset('storage/'.$progress->student->user->photo) }}" alt="Foto" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                  @else
                  <img src="https://ui-avatars.com/api/?name={{ urlencode($progress->student->user->name ?? 'Siswa') }}&size=32" class="rounded-circle me-2" width="32" height="32" alt="Default">
                  @endif
                  {{ $progress->student->user->name ?? '-' }}
                </td>
                <td>{{ $progress->class_level }}</td>
                <td class="text-capitalize">{{ $progress->semester }}</td>
                <td>{{ $progress->year }}</td>
                <td>
                  <span class="badge bg-{{ $progress->status == 'aktif' ? 'success' : ($progress->status == 'lulus' ? 'primary' : 'warning') }}">
                    {{ ucfirst($progress->status) }}
                  </span>
                </td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProgressModal{{ $progress->id }}">
                    <span class="iconify" data-icon="mdi:pencil" data-width="18"></span> Edit
                  </button>
                  <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $progress->id }})">
                    <span class="iconify" data-icon="mdi:delete" data-width="18"></span> Hapus
                  </button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted">Tidak ada data</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $progresses->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Progress -->
@foreach($progresses as $progress)
<div class="modal fade" id="editProgressModal{{ $progress->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('student-progresses.update', $progress->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Progress Siswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Siswa</label>
          <input type="text" class="form-control" value="{{ $progress->student->user->name ?? '-' }}" readonly>
        </div>
        <div class="mb-3">
          <label>Kelas</label>
          <select name="class_level" class="form-control" required>
            <option value="X" {{ $progress->class_level == 'X' ? 'selected' : '' }}>X</option>
            <option value="XI" {{ $progress->class_level == 'XI' ? 'selected' : '' }}>XI</option>
            <option value="XII" {{ $progress->class_level == 'XII' ? 'selected' : '' }}>XII</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Semester</label>
          <select name="semester" class="form-control" required>
            <option value="ganjil" {{ $progress->semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="genap" {{ $progress->semester == 'genap' ? 'selected' : '' }}>Genap</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Tahun Ajaran</label>
          <input type="text" name="year" class="form-control" value="{{ $progress->year }}" required>
        </div>
        <div class="mb-3">
          <label>Status</label>
          <select name="status" class="form-control" required>
            <option value="aktif" {{ $progress->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="lulus" {{ $progress->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
            <option value="naik" {{ $progress->status == 'naik' ? 'selected' : '' }}>Naik</option>
            <option value="tinggal" {{ $progress->status == 'tinggal' ? 'selected' : '' }}>Tinggal</option>
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

<!-- Modal Tambah Progress -->
<div class="modal fade" id="createProgressModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" action="{{ route('student-progresses.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Progress Siswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label>Pilih Siswa</label>
          <select name="student_id" class="form-control" required>
            <option value="">-- Pilih Siswa --</option>
            @foreach($students as $s)
            <option value="{{ $s->id }}">{{ $s->user->name ?? 'Tanpa Nama' }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label>Kelas</label>
          <select name="class_level" class="form-control" required>
            <option value="X">X</option>
            <option value="XI">XI</option>
            <option value="XII">XII</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Semester</label>
          <select name="semester" class="form-control" required>
            <option value="ganjil">Ganjil</option>
            <option value="genap">Genap</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Tahun Ajaran</label>
          <input type="text" name="year" class="form-control" required placeholder="Contoh: 2024/2025">
        </div>
        <div class="mb-3">
          <label>Status</label>
          <select name="status" class="form-control" required>
            <option value="aktif">Aktif</option>
            <option value="lulus">Lulus</option>
            <option value="naik">Naik</option>
            <option value="tinggal">Tinggal</option>
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
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<script>
  function confirmDelete(id) {
    Swal.fire({
      title: 'Yakin ingin menghapus progress ini?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/student-progresses/${id}`;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
</script>
@endsection