<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Nova Free Bootstrap Template for Agency &mdash; by FreeBootstrap.net </title>

    <!-- ======= Google Font =======-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet">
    <!-- End Google Font-->

    <!-- ======= Styles =======-->
    <link href="{{ asset('home/assets/vendors/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendors/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendors/glightbox/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('home/assets/vendors/aos/aos.css') }}" rel="stylesheet">
    <!-- End Styles-->

    <!-- ======= Theme Style =======-->
    <link href="{{ asset('home/assets/css/style.css') }}" rel="stylesheet">
    <!-- End Theme Style-->

    <!-- ======= Apply theme =======-->
    <script>
        // Apply the theme as early as possible to avoid flicker
      (function() {
      const storedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-bs-theme', storedTheme);
      })();
    </script>
</head>

<body>


    <!-- ======= Site Wrap =======-->
    <div class="site-wrap">


       @include('welcome.partials.header')

        <!-- ======= Main =======-->
        <main>


            @yield('content')

          @include('welcome.partials.footer')

        </main>
    </div>

    <!-- ======= Back to Top =======-->
    <button id="back-to-top"><i class="bi bi-arrow-up-short"></i></button>
    <!-- End Back to top-->

    <!-- ======= Javascripts =======-->
    <script src="{{ asset('home/assets/vendors/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/glightbox/glightbox.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/aos/aos.js') }}"></script>
    <script src="{{ asset('home/assets/vendors/purecounter/purecounter.js') }}"></script>
    <script src="{{ asset('home/assets/js/custom.js') }}"></script>
    <script src="{{ asset('home/assets/js/send_email.js') }}"></script>
    <!-- End JavaScripts-->
</body>

</html>