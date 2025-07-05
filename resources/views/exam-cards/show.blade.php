@extends('layouts.app')
@section('title', 'Kartu Ujian')
@section('content')

<style>
  @media print {
    body, html {
      background: #fff !important;
    }
    .no-print, header, nav, aside, footer, .app-header, .app-sidebar, .sidebar, .main-footer, .navbar, .breadcrumb,
    .copyright, .pagination, .btn, .alert, .d-flex.justify-content-end, .card-title, .bi-printer {
      display: none !important;
    }
    .container, .row, .col-lg-8, .card, .card-body, .mb-5, .px-2, .py-4 {
      background: #fff !important;
      box-shadow: none !important;
      border: none !important;
      margin: 0 !important;
      padding: 0 !important;
      width: 100% !important;
      max-width: 100% !important;
    }
    /* Hilangkan print footer/page number yang muncul di browser */
    @page { margin: 0.5in 0.5in 0.3in 0.5in !important; }
  }
  </style>

<div class="container py-4" style="background: #fff;">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      @forelse ($examCards as $card)
      <div class="mb-5 px-2 py-4" style="border: 1px solid #fff;">
        {{-- HEADER: Logo + Judul + Garis --}}
        <div class="d-flex align-items-start justify-content-between">
          <div style="min-width:80;max-width:80;">
            <img src="{{ asset('assets/images/logos/logo.png') }}" alt="Logo" style="width:100px;height:100px;">
          </div>
          <div class="flex-grow-1 text-center ps-3" style="padding-top:12px">
            <div style="font-size: 1.4rem;font-weight: 500;">madrasah Aliyah Pertasi Kencana</div>
            <div style="font-size: 1.2rem;margin-top:2px;">Nu haruyan</div>
          </div>
          <div style="min-width:80;max-width:80;"></div> {{-- Empty for balancing --}}
        </div>
        <hr style="border-top: 3px solid #111; margin-top:16px; margin-bottom:32px;">

        {{-- Data Peserta, rata kiri, font besar, space tebal --}}
        <div style="font-size:1.35rem;line-height:2.4;">
          <div>Nomor Peserta : <span style="font-weight:400;">{{ $card->student->id }}</span></div>
          <div>Nama&nbsp;: <span style="font-weight:400;">{{ $card->student->user->name }}</span></div>
          <div>Kelas&nbsp;: <span style="font-weight:400;">{{ $card->student->class }}</span></div>
          <div>Jenis Ujian&nbsp;: <span style="font-weight:400;">{{ $card->exam_type }}</span></div>
          <div>Tahun Ajaran&nbsp;: <span style="font-weight:400;">{{ $card->year }}</span></div>
        </div>
      </div>
      @empty
      <p class="text-center text-muted py-5">Belum ada kartu ujian yang tersedia.</p>
      @endforelse
     
    </div>
  </div>
</div>
@endsection