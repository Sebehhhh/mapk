@extends('welcome.app')
@section('content')

<style>
    .img-ekskul {
        width: 300pxß;
        height: 200px;
        object-fit: cover;
        border-radius: 16px;
        /* bulat: 50% | kotak: 0 atau sesuai selera */
        background: #eee;
        box-shadow: 0 2px 8px #0001;
        display: block;
    }
</style>
<!-- ======= Hero =======-->
<section class="hero__v6 section" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="hero-subtitle text-uppercase">
                    {{ $hero?->subtitle ?? 'Madrasah Aliyah Pertasi Kencana' }}
                </span>
                <h1 class="hero-title mb-3">{{ $hero?->title ?? 'MAPK Haruyan' }}</h1>
                <p class="hero-description mb-4 mb-lg-5">
                    {!! nl2br(e($hero?->description ?? 'Selamat datang di website resmi MAPK Haruyan.<br>Mewujudkan
                    generasi Islami, berprestasi, dan berakhlak mulia.')) !!}
                </p>
            </div>
            <div class="col-lg-6">
                <img class="img-fluid rounded-4 shadow"
                    src="{{ $hero && $hero->photo ? asset('storage/' . $hero->photo) : 'https://ui-avatars.com/api/?name=MAPK+Haruyan&size=400' }}"
                    alt="Foto Sekolah" style="width: 100%; height: 400px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>
<!-- End Hero-->

<!-- ======= About =======-->
{{-- <section class="about__v4 section" id="about">
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-2">
                <h2>Tentang MAPK Haruyan</h2>
                <p>Madrasah Aliyah Pertasi Kencana (MAPK) Haruyan adalah lembaga pendidikan Islam yang berkomitmen pada
                    pengembangan ilmu pengetahuan dan karakter. Dengan visi “Mencetak Generasi Qurani dan Unggul”, MAPK
                    Haruyan terus berinovasi dalam pendidikan.</p>
                <ul>
                    <li><strong>Akreditasi:</strong> A</li>
                    <li><strong>Alamat:</strong> Haruyan, Hulu Sungai Tengah, Kalimantan Selatan</li>
                    <li><strong>Visi:</strong> Mencetak Generasi Qurani dan Unggul</li>
                </ul>
            </div>
            <div class="col-md-6">
                <img class="img-fluid rounded-4"
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMkWXpFYEAfATyroiHmwMUJ5EV_bF3l48BtiH5zvZ-Z50Z3GMWMMYx5G6OazTVCMOWIv0&usqp=CAU"
                    alt="Tentang Sekolah" style="width: 100%; height: 400px; object-fit: cover;">
            </div>
        </div>
    </div>
</section> --}}
<!-- End About-->
<section class="section" id="ekstrakurikuler">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Ekstrakurikuler</h2>
            <p>Pilihan ekskul untuk mengembangkan bakat & minat siswa MAPK Haruyan</p>
        </div>
        <div class="row g-4 justify-content-center">
            @php
                $listEkstra = $extracurriculars->take(8);
                $colClass = $listEkstra->count() < 4 ? 'col-md-4' : 'col-md-3';
            @endphp
            @forelse($listEkstra as $ekstra)
                <div class="{{ $colClass }}">
                    <div class="card shadow-sm border-0 h-100 text-center">
                        <img class="img-ekskul"
                            src="{{ $ekstra->photo ? asset('storage/'.$ekstra->photo) : 'https://ui-avatars.com/api/?name='.urlencode($ekstra->name).'&size=128' }}"
                            class="card-img-top" alt="{{ $ekstra->name }}"
                            style="width:100%;height:160px;object-fit:cover;">
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $ekstra->name }}</h5>
                            @if($ekstra->description)
                            <p class="small text-muted mb-0">{{ Str::limit(strip_tags($ekstra->description), 50) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    Belum ada data ekstrakurikuler.
                </div>
            @endforelse
        </div>
    </div>
</section>


<section class="section" id="pengumuman">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Pengumuman</h2>
            <p>Informasi & pengumuman terbaru dari MAPK Haruyan</p>
        </div>
        <div class="row g-3">
            @foreach($pengumuman ?? [] as $peng)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $peng->judul }}</h5>
                        <p class="mb-1">{{ $peng->tanggal->format('d M Y') }}</p>
                        <p class="mb-0">{{ $peng->isi }}</p>
                    </div>
                </div>
            </div>
            @endforeach
            {{-- Dummy jika belum ada --}}
            @if(empty($pengumuman))
            <div class="col-12">
                <div class="alert alert-info text-center">Belum ada pengumuman.</div>
            </div>
            @endif
        </div>
    </div>
</section>
<!-- ======= About =======-->
<section class="about__v4 section" id="about">
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-2">
                <h2>Tentang {{ $about->title ?? 'MAPK Haruyan' }}</h2>
                <p>
                    {{ $about->description ?? 'Madrasah Aliyah Pertasi Kencana (MAPK) Haruyan adalah lembaga pendidikan
                    Islam yang berkomitmen pada pengembangan ilmu pengetahuan dan karakter.' }}
                </p>
                <ul>
                    <li><strong>Akreditasi:</strong> {{ $about->akreditasi ?? '-' }}</li>
                    <li><strong>Alamat:</strong> {{ $about->address ?? '-' }}</li>
                    <li><strong>Email:</strong> {{ $about->email ?? '-' }}</li>
                    @if($about && $about->instagram)
                        <li><strong>Instagram:</strong> <a href="https://instagram.com/{{ ltrim($about->instagram, '@') }}" target="_blank">{{ $about->instagram }}</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-md-6">
                <img class="img-fluid rounded-4"
                    src="{{ $about && $about->photo ? asset('storage/'.$about->photo) : 'https://ui-avatars.com/api/?name='.urlencode($about->title ?? 'Sekolah').'+MAPK+Haruyan&size=400' }}"
                    alt="Tentang Sekolah" style="width: 100%; height: 400px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>
<!-- End About-->

<!-- ======= Kontak Sekolah =======-->
<section class="section" id="tentang">
    <div class="container">
        <div class="text-center mb-5">
            <h2>Kontak Sekolah</h2>
            <p>Hubungi {{ $about->title ?? 'MAPK Haruyan' }} untuk informasi lebih lanjut</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4 d-flex flex-column justify-content-center">
                <div class="mb-2"><i class="bi bi-geo-alt"></i> {{ $about->address ?? '-' }}</div>
                <div class="mb-2"><i class="bi bi-envelope"></i> {{ $about->email ?? '-' }}</div>
                @if($about && $about->instagram)
                <div class="mb-2"><i class="bi bi-instagram"></i> <a href="https://instagram.com/{{ ltrim($about->instagram, '@') }}" target="_blank">{{ $about->instagram }}</a></div>
                @endif
            </div>
            <div class="col-md-6 mb-4">
                <div class="ratio ratio-16x9 shadow-sm" style="min-height:250px;">
                    <iframe
                        src="https://www.google.com/maps?q={{ $about->latitude ?? '-2.6921566438792364' }},{{ $about->longitude ?? '115.34939246393857' }}&hl=id&z=16&output=embed"
                        width="100%" height="100%" style="border:0;min-height:250px;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Kontak Sekolah-->

@endsection