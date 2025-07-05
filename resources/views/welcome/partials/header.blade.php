<!-- ======= Header =======-->
<header class="fbs__net-navbar navbar navbar-expand-lg dark" aria-label="freebootstrap.net navbar">
    <div class="container d-flex align-items-center justify-content-between">


        <!-- Start Logo-->
        <a class="navbar-brand w-auto" href="index.html">
            <!-- If you use a text logo, uncomment this if it is commented-->
            <!-- Vertex-->

            <!-- If you plan to use an image logo, uncomment this if it is commented-->

            <!-- logo dark--><img class="logo dark img-fluid" src="{{ asset('assets/images/logos/logo.png') }}"
                alt="Logo MAPK NU Haruyan" style="width: 60px; height: 60px;">

            <!-- logo light--><img class="logo light img-fluid" src="{{ asset('assets/images/logos/logo.png') }}"
                alt="Logo MAPK NU Haruyan" style="width: 60px; height: 60px;">

        </a>
        <!-- End Logo-->

        <!-- Start offcanvas-->
        <div class="offcanvas offcanvas-start w-75" id="fbs__net-navbars" tabindex="-1"
            aria-labelledby="fbs__net-navbarsLabel">


            <div class="offcanvas-header">
                <div class="offcanvas-header-logo">
                    <!-- If you use a text logo, uncomment this if it is commented-->

                    <!-- h5#fbs__net-navbarsLabel.offcanvas-title Vertex-->

                    <!-- If you plan to use an image logo, uncomment this if it is commented-->
                    <a class="logo-link" id="fbs__net-navbarsLabel" href="index.html">


                        <!-- logo dark--><img class="logo dark img-fluid"
                            src="{{ asset('home/assets/images/logos/logo.png') }}"
                            alt="FreeBootstrap.net image placeholder">

                        <!-- logo light--><img class="logo light img-fluid"
                            src="{{ asset('home/assets/images/logos/logo.png') }}"
                            alt="FreeBootstrap.net image placeholder">
                    </a>

                </div>
                <button class="btn-close btn-close-black" type="button" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>

            <div class="offcanvas-body align-items-lg-center">


                <ul class="navbar-nav nav me-auto ps-lg-5 mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#ekstrakurikuler">Ekstrakurikuler</a></li>
                    <li class="nav-item"><a class="nav-link" href="#pengumuman">Pengumuman</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang Sekolah</a></li>
                </ul>

            </div>
        </div>
        <!-- End offcanvas-->

        <div class="ms-auto w-auto">


            <div class="header-social d-flex align-items-center gap-1"><a class="btn btn-primary py-2" href="{{ route('login') }}">Login</a>

                <button class="fbs__net-navbar-toggler justify-content-center align-items-center ms-auto"
                    data-bs-toggle="offcanvas" data-bs-target="#fbs__net-navbars" aria-controls="fbs__net-navbars"
                    aria-label="Toggle navigation" aria-expanded="false">
                    <svg class="fbs__net-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="21" x2="3" y1="6" y2="6"></line>
                        <line x1="15" x2="3" y1="12" y2="12"></line>
                        <line x1="17" x2="3" y1="18" y2="18"></line>
                    </svg>
                    <svg class="fbs__net-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>

            </div>

        </div>
    </div>
</header>
<!-- End Header-->