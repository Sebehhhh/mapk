<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Kartu Ujian</title>
    <style>
        body {
            background: #fff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .kartu-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 18px 18px;
            justify-content: flex-start;
        }

        .kartu-ujian {
            border: 1.5px solid #ddd;
            border-radius: 8px;
            width: 330px;
            margin: 0;
            padding: 14px 18px 14px 18px;
            background: #fff;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .kartu-header-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 14px;
            min-height: 48px;
        }

        .kartu-logo-col {
            flex: 0 0 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kartu-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .kartu-title-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
            height: 100%;
        }

        .kartu-title {
            font-size: 1rem;
            font-weight: 700;
            color: #17191A;
            margin: 0 0 2px 0;
        }

        .kartu-subtitle {
            font-size: 0.93rem;
            color: #4a4a4a;
            font-weight: 400;
            margin: 0;
            line-height: 1.15;
        }

        .kartu-divider {
            border: none;
            border-top: 2px solid #111;
            margin: 9px 0 12px 0;
        }

        .kartu-data {
            font-size: 0.98rem;
            line-height: 1.6;
            color: #232323;
            font-weight: 500;
        }

        .kartu-data strong {
            font-weight: 700;
            color: #111;
        }

        @media print {

            body,
            html {
                background: #fff !important;
            }

            * {
                box-shadow: none !important;
            }

            .no-print,
            header,
            footer,
            nav,
            aside,
            .sidebar,
            .navbar,
            .pagination {
                display: none !important;
            }

            .kartu-ujian,
            .kartu-grid {
                page-break-inside: avoid !important;
            }

            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>

<body>
    <div class="kartu-grid">
        @foreach($examCards as $peserta)
        <div class="kartu-ujian">
            <div class="kartu-header-row">
                <div class="kartu-logo-col">
                    <div
                        style="width: 42px; height: 42px; background: #e0e0e0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #666;">
                        LOGO
                    </div>
                </div>
                <div class="kartu-title-col">
                    <div class="kartu-title">Madrasah Aliyah Pertasi Kencana</div>
                    <div class="kartu-subtitle">Nu haruyan</div>
                </div>
            </div>
            <hr class="kartu-divider">
            <div class="kartu-data">
                <strong>Nomor Peserta :</strong> {{ $peserta->nomor_peserta }}<br>
                <strong>Nama :</strong> {{ $peserta->nama }}<br>
                <strong>Kelas :</strong> {{ $peserta->kelas }}<br>
                <strong>Jenis Ujian :</strong> {{ $peserta->jenis_ujian }}<br>
                <strong>Tahun Ajaran :</strong> {{ $peserta->tahun_ajaran }}
            </div>
        </div>
        @endforeach
    </div>
</body>

</html>