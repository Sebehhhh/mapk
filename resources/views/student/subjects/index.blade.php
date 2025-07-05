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

{{-- Modal Assign Mapel ke Siswa --}}
<div class="modal fade" id="modalAssignSubject" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST" action="{{ route('subject-users.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mapel ke Siswa</h5>
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
                <div class="col-md-4">
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">Pilih Mapel</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->semester }}, {{
                            $subject->class_level }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" min="2000" max="{{ date('Y') + 1 }}" class="form-control"
                        value="{{ old('year', date('Y')) }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(pivotId) {
        Swal.fire({
            title: 'Yakin ingin menghapus mapel dari siswa ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ubah action form ke route destroy resource
                const form = document.getElementById('deleteForm');
                form.action = `/subject-users/${pivotId}`;
                form.submit();
            }
        });
    }
</script>
@endsection