@extends('layouts.app')
@section('title', 'Kartu Ujian')
@section('content')
<style>
  @media print {

    body,
    html {
      background: #fff !important;
    }

    .no-print,
    header,
    nav,
    aside,
    footer,
    .app-header,
    .app-sidebar,
    .sidebar,
    .main-footer,
    .navbar,
    .breadcrumb,
    .copyright,
    .pagination,
    .btn,
    .alert,
    .d-flex.justify-content-end,
    .card-title,
    .bi-printer {
      display: none !important;
    }

    .kartu-ujian {
      box-shadow: none !important;
      border: 1px solid #aaa !important;
      margin: 0 auto 20px auto !important;
      page-break-after: always;
      max-width: 420px !important;
    }

    @page {
      margin: 0.3in 0.3in 0.3in 0.3in !important;
    }
  }

  .kartu-ujian {
    border: 1px solid #ddd;
    max-width: 420px;
    margin: 24px auto 0 auto;
    padding: 16px 20px 16px 22px;
    background: #fff;
    font-size: 1rem;
  }

  .kartu-ujian .judul {
    font-size: 1.1rem;
    font-weight: 600;
    text-align: center;
  }

  .kartu-ujian .subjudul {
    font-size: 0.99rem;
    margin-bottom: 2px;
    text-align: center;
  }

  .kartu-ujian .data-siswa {
    margin-top: 15px;
    font-size: 1rem;
    line-height: 2;
    font-weight: 500;
    text-align: left;
  }

  @media (max-width: 600px) {
    .kartu-ujian {
      max-width: 99vw;
      padding: 8px;
    }
  }
</style>

@forelse ($examCards as $card)
<div class="kartu-ujian">
  <div class="d-flex align-items-center justify-content-center" style="gap:14px;">
    <img src="{{ asset('assets/images/logos/logo.png') }}" alt="Logo" style="width:62px; height:62px;">
    <div>
      <div class="judul">madrasah Aliyah Pertasi Kencana</div>
      <div class="subjudul">Nu haruyan</div>
    </div>
  </div>
  <hr style="border-top: 2.5px solid #111; margin:12px 0 15px 0;">
  <div class="data-siswa">
    Nomor Peserta : <span style="font-weight:400;">{{ $card->student->id }}</span><br>
    Nama : <span style="font-weight:400;">{{ $card->student->user->name }}</span><br>
    Kelas : <span style="font-weight:400;">{{ $card->student->class }}</span><br>
    Jenis Ujian : <span style="font-weight:400;">{{ $card->exam_type }}</span><br>
    Tahun Ajaran : <span style="font-weight:400;">{{ $card->year }}</span>
  </div>
</div>
@empty
<p class="text-center text-muted py-5">Belum ada kartu ujian yang tersedia.</p>
@endforelse
@endsection