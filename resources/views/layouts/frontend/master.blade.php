<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Website' }}</title>

    <link rel="stylesheet" href="{{asset('front_assets/css/fontawesome.all.min.css')}}">
    <link rel="stylesheet" href="{{asset('front_assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('front_assets/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset('front_assets/css/style.css')}}">
    @livewireStyles
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand py-0" href="#">
                    <img src="{{asset('front_assets/images/logo.png')}}" alt="logo">
                </a>
            </div>
        </nav>
    </header>

    <main>
        {{ $slot }}
    </main>

   <footer>
        <div class="bg-overlay">
            <img src="{{asset('front_assets/images/footer-bg-overlay.png')}}" alt="">
        </div>
        <div class="container">
            <div class="row main-content">
                <div class="col-lg-4 col-12 footer-col">
                    <div class="logo">
                        <a href="javascript:void(0)">
                            <img src="{{asset('front_assets/images/footer-logo.png')}}" alt="Logo">
                        </a>
                    </div>
                    <div class="desc">
                        <p>Carrnival Trips is a prominent company offering the best packages to Darjeeling and Sikkim, making the clients happy and satisfied with their exemplary services.</p>
                    </div>
                </div>
                <div class="col-lg-2 col-12 footer-col">
                    <label class="menu-heading">Links</label>
                    <ul class="nav">
                        <li class="nav-item"><a href="javascript:void(0)" class="nav-link">T&amp;C</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" class="nav-link">Privacy Policy</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" class="nav-link">Payment Policy</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" class="nav-link">Refund Policy</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" class="nav-link">Reschedule Policy</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" class="nav-link">Cancellation Policy</a></li>
                        <li class="nav-item"><a href="javascript:void(0)" class="nav-link">Disclaimer</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-12 footer-col">
                    <label class="menu-heading">Contact</label>
                    <ul class="contact-menu">
                        <li>
                            <img src="{{asset('front_assets/icons/phone.png')}}" alt="">
                            <a href="tel:88657524332">[88] 657 524 332</a>
                        </li>
                        <li>
                            <img src="{{asset('front_assets/icons/email.png')}}" alt="">
                            <a href="mailto:info@example.com">info@example.com</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 col-12 footer-col">
                    <label class="menu-heading">Social Media</label>
                    <ul class="social-menu">
                        <li><a href="javascript:void(0)"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a href="javascript:void(0)"><i class="fa-brands fa-linkedin-in"></i></a></li>
                        <li><a href="javascript:void(0)"><i class="fa-brands fa-youtube"></i></a></li>
                    </ul>
                    <label class="menu-heading">Subscribe to our Newsletter</label>
                    <form>
                        <input type="email" placeholder="Email *">
                        <button type="submit" class="btn-submit">
                            Submit
                            <img src="{{asset('front_assets/icons/right-angle-circle-white.png')}}" alt="">
                        </button>
                    </form>
                </div>
            </div>
            <div class="row copyright-content">
                <div class="col-12">
                    <p>Copyright &copy; {{date('Y')}} Carrnivaltrips. All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{asset('front_assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('front_assets/js/fontawesome.all.min.js')}}"></script>
    <script src="{{asset('front_assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('front_assets/js/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('front_assets/js/gsap.min.js')}}"></script>
    <script src="{{asset('front_assets/js/custom.js')}}"></script>

    @livewireScripts
    @yield('scripts')   
</body>
</html>
