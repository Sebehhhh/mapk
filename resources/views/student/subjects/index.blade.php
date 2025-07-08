@extends('layouts.app')
@section('title', 'Manajemen Mapel Siswa')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card w-100">
            <div class="card-body">

                <div class="d-md-flex align-items-center justify-content-between mb-4">
                    <h4 class="card-title">Manajemen Mapel Siswa</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAssignSubject">
                        <span class="iconify" data-icon="mdi:plus" data-width="20"></span> Tambah Mapel ke Siswa
                    </button>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Filter --}}
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <select name="year" class="form-select">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear==$year ? 'selected' : '' }}>{{ $year }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="semester" class="form-select">
                            <option value="">Semua Semester</option>
                            @foreach($availableSemesters as $smt)
                            <option value="{{ $smt }}" {{ $selectedSemester==$smt ? 'selected' : '' }}>{{ ucfirst($smt)
                                }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="class_level" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach($availableClasses as $class)
                            <option value="{{ $class }}" {{ $selectedClass==$class ? 'selected' : '' }}>{{ $class }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-success">
                            <span class="iconify" data-icon="mdi:magnify" data-width="20"></span> Filter
                        </button>
                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Semester</th>
                                <th>Tahun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjectUsers as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>{{ $item->user->student->nisn ?? '-' }}</td>
                                <td>{{ $item->subject->class_level ?? '-' }}</td>
                                <td>{{ $item->subject->name ?? '-' }}</td>
                                <td>{{ ucfirst($item->subject->semester) }}</td>
                                <td>{{ $item->year }}/{{ $item->year + 1 }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete({{ $item->id }})">
                                        <span class="iconify" data-icon="mdi:delete" data-width="18"></span> Hapus
                                    </button>
                                    <form id="deleteForm" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data mapping mapel siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- Modal Assign Mapel ke Siswa (Batch) --}}
<div class="modal fade" id="modalAssignSubject" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="{{ route('subject-users.store-batch') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mapel ke Siswa (Batch)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Siswa</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kelas</label>
                    <select id="classLevelSelect" name="class_level" class="form-select" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($availableClasses as $class)
                            <option value="{{ $class }}">{{ $class }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester</label>
                    <select id="semesterSelect" name="semester" class="form-select" required>
                        <option value="">Pilih Semester</option>
                        @foreach($availableSemesters as $smt)
                            <option value="{{ $smt }}">{{ ucfirst($smt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" min="2000" max="{{ date('Y') + 1 }}" class="form-control"
                        value="{{ old('year', date('Y')) }}" required>
                </div>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Pilih Mapel</label>
                    <div id="mapelCheckboxList">
                        {{-- AJAX mapel akan muncul di sini --}}
                        <small class="text-muted">Pilih kelas dan semester dulu untuk memunculkan mapel.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('classLevelSelect').addEventListener('change', loadSubjects);
    document.getElementById('semesterSelect').addEventListener('change', loadSubjects);

    function loadSubjects() {
        const kelas = document.getElementById('classLevelSelect').value;
        const semester = document.getElementById('semesterSelect').value;
        const el = document.getElementById('mapelCheckboxList');
        if (!kelas || !semester) {
            el.innerHTML = '<small class="text-muted">Pilih kelas dan semester dulu.</small>';
            return;
        }
        fetch(`/get-mapel?class_level=${kelas}&semester=${semester}`)
            .then(response => response.json())
            .then(resp => {
                let data = resp.data ?? resp; // Support both: API response (object) and direct array
                if (!Array.isArray(data) || data.length === 0) {
                    el.innerHTML = '<div class="text-danger">Tidak ada mapel.</div>';
                } else {
                    let html = '';
                    data.forEach(mp => {
                        html += `
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="subject_ids[]" value="${mp.id}" id="mp${mp.id}">
                              <label class="form-check-label" for="mp${mp.id}">${mp.name}</label>
                            </div>
                        `;
                    });
                    el.innerHTML = html;
                }
            })
            .catch(() => {
                el.innerHTML = '<div class="text-danger">Gagal load data mapel.</div>';
            });
    }
</script>
@endsection