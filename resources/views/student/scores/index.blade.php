@extends('layouts.app')
@section('title', 'Nilai Saya')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header bg-light d-flex align-items-center justify-content-between">
        <h4 class="fw-bold mb-0"><span class="me-2"
                style="background:#ccc;display:inline-block;width:28px;height:28px;border-radius:6px;vertical-align:middle;"></span>Nilai
        </h4>
        <form method="get" class="mb-3 d-flex gap-2">
            <select class="form-select" name="kelas" style="width:auto;" onchange="this.form.submit()">
                @foreach($availableClasses as $cls)
                <option value="{{ $cls }}" {{ $filterKelas==$cls ? 'selected' : '' }}>Kelas {{ $cls }}</option>
                @endforeach
            </select>
            <select class="form-select" name="semester" style="width:auto;" onchange="this.form.submit()">
                @foreach($availableSemesters as $key => $label)
                <option value="{{ $key }}" {{ $filterSemester==$key ? 'selected' : '' }}>{{ ucfirst($label) }}</option>
                @endforeach
            </select>
        </form>

    </div>
    <div class="card-body bg-light">
        @if($scores->isEmpty())
        <div class="alert alert-info">Belum ada nilai yang tersedia.</div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered" style="background:#fff;">
                <thead>
                    <tr class="text-center align-middle" style="background:#e6e6e6;">
                        <th style="width:40px;">No</th>
                        <th>Mata Pelajaran</th>
                        <th>Kehadiran</th>
                        <th>Tugas</th>
                        <th>UTS</th>
                        <th>UAS</th>
                        <th>Nilai Akhir Mata Pelajaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scores as $i => $score)
                    <tr class="text-center">
                        <td>{{ $i+1 }}</td>
                        <td class="text-start">{{ $score->subject->name ?? '-' }}</td>
                        <td>{{ $score->attendance }}</td>
                        <td>{{ $score->assignment }}</td>
                        <td>{{ $score->mid_exam }}</td>
                        <td>{{ $score->final_exam }}</td>
                        <td>{{ number_format($score->nilai_akhir, 1) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="p-3 bg-white border">
                    <div class="d-flex justify-content-between">
                        <span>Nilai Akhir</span>
                        <span class="fw-bold">{{ $nilai_akhir_rata2 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span>Rangking</span>
                        <span class="fw-bold">{{ $ranking ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection