@extends('layouts.app')
@section('title', 'Mata Pelajaran Saya')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-md-flex align-items-center justify-content-between mb-4">
          <h4 class="card-title">Mata Pelajaran Saya</h4>
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
          <div class="col-md-3">
            <select name="year" class="form-select">
              <option value="">Semua Tahun</option>
              @foreach($availableYears as $year)
                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="semester" class="form-select">
              <option value="">Semua Semester</option>
              @foreach($availableSemesters as $smt)
                <option value="{{ $smt }}" {{ $selectedSemester == $smt ? 'selected' : '' }}>
                  {{ ucfirst($smt) }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="class_level" class="form-select">
              <option value="">Semua Kelas</option>
              @foreach($availableClasses as $class)
                <option value="{{ $class }}" {{ $selectedClass == $class ? 'selected' : '' }}>
                  {{ $class }}
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
                <th>Nama Mata Pelajaran</th>
                <th>Semester</th>
                <th>Kelas</th>
                <th>Tahun Ajaran</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($subjects as $subject)
              <tr>
                <td>{{ $loop->iteration + ($subjects->currentPage() - 1) * $subjects->perPage() }}</td>
                <td>{{ $subject->name }}</td>
                <td>{{ ucfirst($subject->semester) }}</td>
                <td>{{ $subject->class_level }}</td>
                <td>{{ $subject->pivot->year }}</td>
                <td>
                  <span class="badge bg-success">Diambil</span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">Tidak ada data mata pelajaran di tahun/semester/kelas ini.</td>
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
<script src="https://code.iconify.design/3/3.1.1/iconify.min.js"></script>
@endsection