@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

@if(auth()->user()->role === 'admin')

<div class="row">
  <!-- Info Cards Row -->
  <div class="col-lg-12">
    <div class="row">
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-primary mb-1">
              <i class="ti ti-users"></i>
            </div>
            <h5 class="card-title mb-0">{{ $students_count }}</h5>
            <small class="text-muted">Total Siswa</small>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-success mb-1">
              <i class="ti ti-user"></i>
            </div>
            <h5 class="card-title mb-0">{{ $users_count }}</h5>
            <small class="text-muted">Total User</small>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-info mb-1">
              <i class="ti ti-book"></i>
            </div>
            <h5 class="card-title mb-0">{{ $subjects_count }}</h5>
            <small class="text-muted">Mata Pelajaran</small>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-warning mb-1">
              <i class="ti ti-id-badge"></i>
            </div>
            <h5 class="card-title mb-0">{{ $exam_cards_count }}</h5>
            <small class="text-muted">Kartu Ujian</small>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-secondary mb-1">
              <i class="ti ti-users"></i>
            </div>
            <h5 class="card-title mb-0">{{ $student_parents_count }}</h5>
            <small class="text-muted">Orang Tua Terdaftar</small>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-danger mb-1">
              <i class="ti ti-chart-bar"></i>
            </div>
            <h5 class="card-title mb-0">{{ $scores_count }}</h5>
            <small class="text-muted">Total Nilai Tercatat</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Statistik Chart -->
  <div class="col-lg-6 mt-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-2">Statistik Siswa per Angkatan</h5>
        <canvas id="angkatanChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-6 mt-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-2">Statistik Siswa Berdasarkan Gender</h5>
        <canvas id="genderChart"></canvas>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Data dikirim dari Controller via JSON
    var angkatanLabels = @json($angkatanLabels);
var angkatanData   = @json($angkatanData);
var genderLabels   = @json($genderLabels);
var genderData     = @json($genderData);

// Chart Angkatan
new Chart(document.getElementById('angkatanChart'), {
  type: 'bar',
  data: {
    labels: angkatanLabels,
    datasets: [{
      label: 'Jumlah Siswa',
      data: angkatanData,
      backgroundColor: 'rgba(24,78,189,0.6)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true, stepSize: 1 } }
  }
});

// Chart Gender
new Chart(document.getElementById('genderChart'), {
  type: 'doughnut',
  data: {
    labels: genderLabels,
    datasets: [{
      data: genderData,
      backgroundColor: [
        'rgba(24,78,189,0.6)',
        'rgba(242,162,24,0.6)'
      ],
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { position: 'bottom' } }
  }
});

  });
</script>

@else
<div class="row">
  <div class="col-lg-12">
    <div class="card shadow-sm mb-4">
      <div class="card-body text-center">
        <h4 class="card-title mb-3">Selamat Datang, {{ auth()->user()->name }}</h4>
        <p class="text-muted">NISN: {{ $student->nisn ?? '-' }}</p>
        <div class="d-flex justify-content-center gap-3 mt-2">
          <span class="badge bg-primary">Kelas {{ $kelasTerbaru }}</span>
          <span class="badge bg-secondary text-white text-capitalize">Semester {{ $semesterTerbaru }}</span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-primary mb-1"><i class="ti ti-book"></i></div>
            <h5 class="card-title mb-0">{{ $subjects_count_siswa }}</h5>
            <small class="text-muted">Mata Pelajaran</small>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-success mb-1"><i class="ti ti-chart-bar"></i></div>
            <h5 class="card-title mb-0">{{ $nilai_akhir_rata2 }}</h5>
            <small class="text-muted">Rata-rata Nilai Akhir</small>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <div class="fs-1 text-warning mb-1"><i class="ti ti-award"></i></div>
            <h5 class="card-title mb-0">{{ $ranking }}</h5>
            <small class="text-muted">Ranking di Kelas Ini</small>
          </div>
        </div>
      </div>
    </div>
    <div class="card shadow-sm mt-4">
      <div class="card-body">
        <h5 class="card-title mb-2">Grafik Rata-rata Nilai Akhir per Semester</h5>
        <canvas id="nilaiChart"></canvas>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      var nilaiData = @json($nilaiData);
      new Chart(document.getElementById('nilaiChart'), {
        type: 'line',
        data: {
          labels: nilaiData.labels,
          datasets: [{
            label: 'Nilai Rata-rata',
            data: nilaiData.data,
            borderColor: 'rgba(24,78,189,1)',
            backgroundColor: 'rgba(24,78,189,0.1)',
            fill: true,
            tension: 0.4,
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true, stepSize: 1 } }
        }
      });
    });
</script>
@endif


@endsection