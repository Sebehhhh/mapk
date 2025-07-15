<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Ranking Siswa - {{ $kelas }} {{ ucfirst($semester) }} {{ $year }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 16px; }
        .header img { height: 60px; margin-bottom: 8px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .subtitle { font-size: 14px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: center; }
        th { background: #f0f0f0; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/images/logos/logo.png') }}" alt="Logo" />
        <div class="title">REKAP RANKING SISWA</div>
        <div class="subtitle">Kelas {{ $kelas }} | Semester {{ ucfirst($semester) }} | Tahun {{ $year }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Rata-rata Nilai Akhir</th>
                <th>Ranking</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekap as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-left">{{ $row['siswa']->user->name ?? '-' }}</td>
                <td>{{ $row['avg'] }}</td>
                <td>{{ $row['rank'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 