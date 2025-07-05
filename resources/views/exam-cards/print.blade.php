<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kartu Ujian</title>
  <style>
    @media print {
      body { background: #fff !important; }
      .no-print { display: none !important; }
      .page-break { page-break-after: always; }
    }
    body { font-family: 'Arial', sans-serif; background: #fff; margin:0; padding:0;}
    .container { max-width: 700px; margin: auto; padding: 32px 0;}
    .card-ujian { border: 1px solid #fff; margin-bottom: 50px; padding: 40px 20px;}
    .judul-header { font-size: 1.4rem; font-weight: 500;}
    .sub-header { font-size: 1.2rem; margin-top: 2px;}
    .logo-img { width: 100px; height: 100px;}
    hr { border-top: 3px solid #111; margin-top: 16px; margin-bottom: 32px;}
    .info-peserta { font-size: 1.35rem; line-height: 2.4;}
  </style>
</head>
<body>
<div class="container">
  @foreach($examCards as $card)
  <div class="card-ujian">
    <div style="display:flex; align-items:start; justify-content:space-between;">
      <div style="min-width:80px;max-width:80px;">
        <img src="{{ public_path('assets/images/logos/logo.png') }}" class="logo-img">
      </div>
      <div style="flex-grow:1; text-align:center; padding-top:12px">
        <div class="judul-header">madrasah Aliyah Pertasi Kencana</div>
        <div class="sub-header">Nu haruyan</div>
      </div>
      <div style="min-width:80px;max-width:80px;"></div>
    </div>
    <hr>
    <div class="info-peserta">
      <div>Nomor Peserta : <span style="font-weight:400;">{{ $card->student->id }}</span></div>
      <div>Nama&nbsp;: <span style="font-weight:400;">{{ $card->student->user->name }}</span></div>
      <div>Kelas&nbsp;: <span style="font-weight:400;">{{ $card->student->class }}</span></div>
      <div>Jenis Ujian&nbsp;: <span style="font-weight:400;">{{ $card->exam_type }}</span></div>
      <div>Tahun Ajaran&nbsp;: <span style="font-weight:400;">{{ $card->year }}</span></div>
    </div>
  </div>
  <div class="page-break"></div>
  @endforeach
</div>
<script>
  window.onload = function() { window.print(); }
</script>
</body>
</html>