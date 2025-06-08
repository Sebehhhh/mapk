@extends('layouts.app')
@section('title', 'Kartu Ujian')
@section('content')

<div class="row justify-content-center">
  <div class="col-lg-10">
    <div class="card shadow-lg border-0 mb-5" style="background: #f8fafc;">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
          <h3 class="fw-bold mb-0" style="letter-spacing: 1px;">Kartu Ujian</h3>
          <a href="#" class="btn btn-outline-dark btn-sm" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Kartu
          </a>
        </div>
        @forelse ($examCards as $card)
        <div class="bg-white rounded-4 shadow-sm p-4 mb-4 position-relative border" style="border-left: 6px solid #0d6efd;">
          <div class="row align-items-center g-3">
            <div class="col-md-3 text-center">
              <img src="{{ asset('assets/images/profile/user-1.jpg') }}" 
                   class="rounded-circle shadow"
                   style="width: 110px; height: 110px; object-fit: cover; border: 4px solid #e7eaf3;">
              <div class="mt-2">
                {!! $card->status == 1 
                  ? '<span class="badge bg-success" style="font-size: .92rem;">Aktif</span>' 
                  : '<span class="badge bg-secondary" style="font-size: .92rem;">Tidak Aktif</span>' !!}
              </div>
            </div>
            <div class="col-md-9">
              <div class="row mb-2">
                <div class="col-6 mb-2">
                  <span class="fw-semibold text-muted">No Peserta:</span>
                  <div class="fw-bold fs-6">{{ $card->student->id }}</div>
                </div>
                <div class="col-6 mb-2">
                  <span class="fw-semibold text-muted">NISN:</span>
                  <div class="fw-bold fs-6">{{ $card->student->nisn }}</div>
                </div>
                <div class="col-6 mb-2">
                  <span class="fw-semibold text-muted">Nama:</span>
                  <div class="fw-bold fs-6">{{ $card->student->user->name }}</div>
                </div>
                <div class="col-6 mb-2">
                  <span class="fw-semibold text-muted">TTL:</span>
                  <div class="fw-bold fs-6">
                    {{ $card->student->place_of_birth }}, 
                    {{ \Carbon\Carbon::parse($card->student->birth_date)->format('d-m-Y') }}
                  </div>
                </div>
                <div class="col-6 mb-2">
                  <span class="fw-semibold text-muted">Jenis Ujian:</span>
                  <div class="fw-bold fs-6">{{ $card->exam_type }}</div>
                </div>
                <div class="col-3 mb-2">
                  <span class="fw-semibold text-muted">Semester:</span>
                  <div class="fw-bold fs-6">{{ $card->semester }}</div>
                </div>
                <div class="col-3 mb-2">
                  <span class="fw-semibold text-muted">Tahun:</span>
                  <div class="fw-bold fs-6">{{ $card->year }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @empty
        <p class="text-center text-muted py-5">Belum ada kartu ujian yang tersedia.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

@endsection
