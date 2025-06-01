@extends('layouts.app')
@section('title', 'Kartu Ujian')
@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="card shadow">
      <div class="card-body p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-bold">Kartu Ujian</h3>
          <a href="#" class="btn btn-outline-dark btn-sm" onclick="window.print()">Cetak Kartu Ujian</a>
        </div>

        @forelse ($examCards as $card)
        <div class="border p-4 mb-4">
          <div class="row align-items-center">
            <div class="col-md-2 text-center">
              <img src="{{ asset('assets/images/profile/user-1.jpg') }}" class="rounded-circle" width="100" height="100" alt="Foto Siswa">
            </div>
            <div class="col-md-10">
              <p><strong>No Peserta:</strong> {{ $card->student->id }}</p>
              <p><strong>NISN:</strong> {{ $card->student->nisn }}</p>
              <p><strong>Nama:</strong> {{ $card->student->user->name }}</p>
              <p><strong>TTL:</strong> {{ $card->student->place_of_birth }}, {{ \Carbon\Carbon::parse($card->student->birth_date)->format('d-m-Y') }}</p>
              <p><strong>Jenis Ujian:</strong> {{ $card->exam_type }}</p>
              <p><strong>Semester:</strong> {{ $card->semester }}</p>
              <p><strong>Tahun:</strong> {{ $card->year }}</p>
              <p><strong>Status:</strong> {!! $card->status == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>' !!}</p>
            </div>
          </div>
        </div>
        @empty
          <p class="text-center text-muted">Belum ada kartu ujian yang tersedia.</p>
        @endforelse

      </div>
    </div>
  </div>
</div>

@endsection