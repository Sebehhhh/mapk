@extends('layouts.app')
@section('title', 'Rekap Ranking Siswa')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <h4 class="fw-bold mb-0"><i class="ti ti-award me-2"></i>Rekap Ranking Siswa</h4>
        <form method="get" class="d-flex gap-2 mb-0">
            <select class="form-select" name="kelas" style="width:auto;" onchange="this.form.submit()">
                @foreach($availableClasses as $cls)
                    <option value="{{ $cls }}" {{ $kelas==$cls ? 'selected' : '' }}>Kelas {{ $cls }}</option>
                @endforeach
            </select>
            <select class="form-select" name="semester" style="width:auto;" onchange="this.form.submit()">
                @foreach($availableSemesters as $key => $label)
                    <option value="{{ $key }}" {{ $semester==$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="card-body bg-light">
        @if(empty($rekap))
        <div class="alert alert-info text-center">Belum ada data nilai untuk kelas & semester ini.</div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle" style="background:#fff;">
                <thead class="table-secondary text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Semester</th>
                        <th>Rata-rata Nilai Akhir</th>
                        <th>Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekap as $i => $row)
                    <tr class="text-center">
                        <td>{{ $i + 1 }}</td>
                        <td class="text-start">{{ $row['siswa']->user->name ?? '-' }}</td>
                        <td>{{ $row['kelas'] }}</td>
                        <td>{{ ucfirst($row['semester']) }}</td>
                        <td>{{ $row['avg'] }}</td>
                        <td><span class="badge bg-success fs-6">{{ $row['rank'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
