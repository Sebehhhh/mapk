<!-- ======= Footer =======-->
<footer class="footer pt-5 pb-3 bg-light border-0">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-7 mb-3 mb-md-0">
                <h5 class="mb-2 fw-bold">{{ $about->title ?? 'MAPK Haruyan' }}</h5>
                <p class="mb-2">
                    {{ $about->description ? Str::limit(strip_tags($about->description), 70) : 'Madrasah Aliyah Pertasi Kencana' }}<br>
                    {{ $about->address ?? 'Haruyan, Hulu Sungai Tengah, Kalimantan Selatan' }}
                </p>
                <div class="mb-1"><i class="bi bi-telephone"></i> {{ $about->phone ?? '-' }}</div>
                <div class="mb-1"><i class="bi bi-envelope"></i> {{ $about->email ?? '-' }}</div>
            </div>
            <div class="col-md-5 text-md-end">
                <p class="mb-1">© <script>document.write(new Date().getFullYear())</script> {{ $about->title ?? 'MAPK Haruyan' }}. All rights reserved.</p>
                <p class="mb-0 small">
                    Developed by 
                    <a href="#" target="_blank" class="text-decoration-none">ICT TEAM</a>
                </p>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer-->