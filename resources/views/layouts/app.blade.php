<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PasarKarya')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --pk-primary: #0f1b38;
            --pk-secondary: #f36b21;
            --pk-light-blue: #a8d0e6;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f9fb;
        }

        .topbar {
            background: var(--pk-primary);
            color: #fff;
            font-size: .8rem;
        }

        .navbar-pk {
            background: #ffffff;
            border-bottom: 1px solid #eee;
            transition: all 0.4s ease;
            padding-top: 15px;
            padding-bottom: 15px;
        }

        .navbar-pk .nav-link {
            font-weight: 600;
            color: #333;
            transition: color 0.3s;
        }

        .navbar-pk .nav-link:hover {
            color: var(--pk-secondary);
        }

        /* TAMBAHAN: Style default untuk teks sapaan */
        .user-greeting {
            color: #6c757d; /* Setara dengan text-muted */
            transition: color 0.4s ease;
        }

        .navbar-brand img {
            height: 55px;
            width: auto;
            object-fit: contain;
            transition: filter 0.4s ease;
        }

        .brand-text {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--pk-primary);
            transition: color 0.4s ease;
            letter-spacing: -0.5px;
        }

        .navbar-pk.scrolled {
            background: linear-gradient(
                90deg,
                var(--pk-primary),
                #1e3a6e
            ) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            padding-top: 10px;
            padding-bottom: 10px;
            border-bottom: none;
        }

        .navbar-pk.scrolled .nav-link {
            color: #ffffff;
        }

        .navbar-pk.scrolled .nav-link:hover {
            color: var(--pk-secondary);
        }

        /* TAMBAHAN: Teks sapaan jadi putih saat di-scroll */
        .navbar-pk.scrolled .user-greeting {
            color: #ffffff !important; 
        }

        .navbar-pk.scrolled .brand-text {
            color: #ffffff;
        }

        .navbar-pk.scrolled .navbar-brand img {
            filter: brightness(0) invert(1);
        }

        .navbar-pk.scrolled .btn-outline-secondary {
            color: #fff;
            border-color: #fff;
        }

        .navbar-pk.scrolled .btn-outline-secondary:hover {
            background: #fff;
            color: var(--pk-primary);
        }

        .navbar-pk.scrolled .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }

        .navbar-pk.scrolled .navbar-toggler-icon {
            filter: brightness(0) invert(1);
        }

        .btn-pk {
            background: var(--pk-primary);
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            border: none;
        }

        .btn-pk:hover {
            background: #081124;
            color: #fff;
        }

        .btn-accent {
            background: var(--pk-secondary);
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            border: none;
        }

        .btn-accent:hover {
            background: #d95a16;
            color: #fff;
        }

        /* TOMBOL KERANJANG BULAT */
        .btn-cart-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--pk-primary);
            color: #fff;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            text-decoration: none;
            transition: 0.3s ease;
            flex-shrink: 0;
        }

        .btn-cart-circle:hover {
            background: #1e3a6e;
            color: #fff;
            transform: scale(1.08);
        }

        .text-accent {
            color: var(--pk-secondary);
        }

        .text-primary-pk {
            color: var(--pk-primary);
        }

        .hero {
            background: #f4f7f9;
            padding: 60px 0;
            border-bottom: 1px solid #eaeaea;
        }

        .hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            color: var(--pk-primary);
        }

        .hero .highlight {
            color: var(--pk-secondary);
        }

        .category-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            border: 2px solid #eee;
            transition: .3s;
        }

        .category-circle:hover {
            border-color: var(--pk-primary);
            transform: scale(1.05);
        }

        .category-circle img {
            width: 65%;
            height: 65%;
            object-fit: contain;
        }

        .product-card {
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            transition: .2s;
        }

        .product-card:hover {
            box-shadow: 0 8px 20px rgba(15, 27, 56, 0.1);
            transform: translateY(-3px);
            border-color: var(--pk-primary);
        }

        .product-card img {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }

        .badge-pk {
            background: var(--pk-secondary);
            font-size: .7rem;
        }

        .promo-banner {
            background: linear-gradient(
                90deg,
                var(--pk-primary),
                #1e3a6e
            );
            color: #fff;
            border-radius: 16px;
        }

        /* PRODUCT DETAIL PAGE */

        .product-detail-row {
            align-items: stretch;
        }

        .product-image-sticky {
            position: sticky;
            top: 100px;
            width: 100%;
        }

        .product-image-frame {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: #fff;
            border: 1px solid #e1e5eb;
            border-radius: 13px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 12px rgba(15, 27, 56, 0.04);
        }

        .product-main-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
            background: #fff;
        }

        .product-info-card {
            height: 100%;
            min-height: 100%;
            background: #fff;
            border: 1px solid #e1e5eb;
            border-radius: 13px;
            padding: 24px 27px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 3px 12px rgba(15, 27, 56, 0.04);
        }

        .product-title {
            font-size: 1.5rem;
            line-height: 1.35;
            color: var(--pk-primary);
            margin-bottom: 13px;
            overflow-wrap: anywhere;
        }

        .product-seller {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 0.85rem;
        }

        .product-seller i {
            font-size: 1.05rem;
        }

        .product-price-section {
            padding-bottom: 15px;
            border-bottom: 1px solid #edf0f3;
        }

        .product-price {
            font-size: 1.55rem;
        }

        .product-description-section {
            margin-bottom: 15px;
        }

        .product-description-section h6 {
            color: var(--pk-primary);
            font-size: 0.9rem;
        }

        .product-description {
            font-size: 0.85rem;
            line-height: 1.7;
            overflow-wrap: anywhere;
            text-align: justify;
        }

        .product-read-more {
            font-size: 0.82rem;
        }

        .product-order-section {
            margin-top: auto;
            padding-top: 17px;
            border-top: 1px solid #edf0f3;
        }

        .product-order-btn {
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 8px;
            min-height: 47px;
            transition: 0.2s ease;
        }

        .product-order-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 27, 56, 0.12);
        }

        /* TABLET */

        @media (max-width: 991.98px) {
            .product-image-sticky {
                position: static;
            }

            .product-image-frame {
                aspect-ratio: 4 / 3;
            }

            .product-info-card {
                padding: 22px;
            }
        }

        /* MOBILE */

        @media (max-width: 575.98px) {
            .product-detail {
                padding-top: 12px !important;
            }

            .product-detail-row {
                --bs-gutter-y: 14px;
            }

            .product-image-frame {
                aspect-ratio: 1 / 1;
                border-radius: 10px;
            }

            .product-info-card {
                padding: 19px 17px;
                border-radius: 10px;
            }

            .product-title {
                font-size: 1.3rem;
            }

            .product-price {
                font-size: 1.4rem;
            }

            .product-description {
                font-size: 0.83rem;
            }
        }

        /* FOOTER */

        .footer-pk {
            background: var(--pk-primary);
            color: #cfd8e3;
        }

        .footer-pk a {
            color: #cfd8e3;
            text-decoration: none;
        }

        .footer-pk a:hover {
            color: var(--pk-secondary);
        }

        /* RESPONSIVE TABLET */

        @media (max-width: 991.98px) {
            .product-image-sticky {
                position: static;
            }

            .product-image-frame {
                aspect-ratio: 4 / 3;
            }

            .product-info-card {
                padding: 25px;
            }

            .navbar-collapse {
                padding-top: 15px;
            }

            .navbar-collapse > .d-flex {
                flex-wrap: wrap;
            }
        }

        /* RESPONSIVE MOBILE */

        @media (max-width: 575.98px) {
            .topbar .container {
                justify-content: center !important;
                gap: 8px;
            }

            .topbar .container span {
                font-size: 0.68rem;
            }

            .topbar .container span:last-child {
                display: none;
            }

            .navbar-brand img {
                height: 42px;
            }

            .brand-text {
                font-size: 1.3rem;
            }

            .product-detail {
                padding-top: 15px !important;
            }

            .product-image-frame {
                aspect-ratio: 1 / 1;
                border-radius: 11px;
            }

            .product-info-card {
                padding: 20px 18px;
                border-radius: 11px;
            }

            .product-title {
                font-size: 1.35rem;
                margin-bottom: 13px;
            }

            .product-seller {
                font-size: 0.8rem;
            }

            .product-price {
                font-size: 1.5rem;
            }

            .product-description {
                font-size: 0.84rem;
            }

            .product-order-btn {
                min-height: 46px;
            }
        }
    </style>
</head>

<body>

    {{-- TOPBAR --}}
    <div class="topbar py-1">
        <div class="container d-flex justify-content-between">
            <span>
                <i class="bi bi-truck me-1"></i>
                Gratis ongkir min. Rp150.000
            </span>

            <span>
                <i class="bi bi-shield-check me-1"></i>
                Transaksi aman & terpercaya
            </span>

            <span>
                <i class="bi bi-headset me-1"></i>
                Dukungan 24/7
            </span>
        </div>
    </div>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-pk sticky-top" id="mainNavbar">

        <div class="container">

            {{-- Logo --}}
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-icon.png') }}" alt="PasarKarya Icon">
                <span class="brand-text mt-1">
                    PasarKarya
                </span>
            </a>

            {{-- Mobile toggler --}}
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navMenu"
                aria-controls="navMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navbar menu --}}
            <div class="collapse navbar-collapse" id="navMenu">

                <ul class="navbar-nav mx-auto gap-3">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('catalog') }}">
                            Katalog
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tentang') }}">
                            Tentang
                        </a>
                    </li>

                </ul>

                {{-- Authentication --}}
                <div class="d-flex align-items-center gap-2">

                    @auth

                        {{-- PERUBAHAN: Mengganti text-muted menjadi user-greeting --}}
                        <span class="user-greeting small d-none d-lg-block me-2">
                            Halo, {{ auth()->user()->nama }}
                        </span>

                        {{-- Tombol Profil --}}
                        <a
                            href="{{ route('profile.edit') }}"
                            class="btn btn-outline-secondary btn-sm fw-bold"
                        >
                            <i class="bi bi-person-circle me-1"></i>
                            Profil
                        </a>

                        {{-- Tombol Dashboard --}}
                        <a
                            href="{{ route(auth()->user()->role . '.dashboard') }}"
                            class="btn btn-outline-secondary btn-sm fw-bold"
                        >
                            Dashboard
                        </a>

                        {{-- Tombol Keranjang khusus Pembeli --}}
                        @if(auth()->user()->role === 'pembeli')
                            <a
                                href="{{ route('pembeli.keranjang.index') }}"
                                class="btn-cart-circle"
                                title="Keranjang Belanja"
                                aria-label="Keranjang Belanja"
                            >
                                <i class="bi bi-cart3"></i>
                            </a>
                        @endif

                        {{-- Tombol Logout --}}
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button class="btn btn-accent btn-sm">
                                Logout
                            </button>
                        </form>

                    @else

                        <a
                            href="{{ route('login') }}"
                            class="btn btn-outline-secondary btn-sm fw-bold px-3"
                        >
                            Login
                        </a>

                        <a
                            href="{{ route('register') }}"
                            class="btn btn-accent btn-sm px-3"
                        >
                            Daftar
                        </a>

                    @endauth

                </div>

            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main>

        <div class="container mt-3">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

        </div>

        @yield('content')

    </main>

    {{-- FOOTER --}}
    <footer class="footer-pk pt-5 pb-3 mt-5">

        <div class="container">

            <div class="row g-4">

                {{-- Brand --}}
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-3">

                        <img
                            src="{{ asset('images/logo-icon.png') }}"
                            alt="Logo PK"
                            style="height: 40px; filter: brightness(0) invert(1);"
                        >

                        <h5 class="fw-bold text-white mb-0">
                            PasarKarya
                        </h5>
                    </div>

                    <p class="small">
                        Wadah hasil karya mahasiswa yang masih layak digunakan.
                        Dibuat untuk mempermudah jual-beli produk kreatif.
                    </p>
                </div>

                {{-- Menu --}}
                <div class="col-md-2">
                    <h6 class="text-white fw-bold">
                        Menu
                    </h6>

                    <ul class="list-unstyled small">
                        <li>
                            <a href="{{ route('home') }}">Beranda</a>
                        </li>

                        <li>
                            <a href="{{ route('home') }}#daftar-produk">Katalog</a>
                        </li>

                        <li>
                            <a href="{{ route('tentang') }}">Tentang</a>
                        </li>
                    </ul>
                </div>

                {{-- Bantuan --}}
                <div class="col-md-3">
                    <h6 class="text-white fw-bold">
                        Bantuan
                    </h6>

                    <ul class="list-unstyled small">
                        <li>
                            <a href="#">Cara Pesan</a>
                        </li>

                        <li>
                            <a href="#">Pengambilan Produk</a>
                        </li>

                        <li>
                            <a href="#">FAQ</a>
                        </li>
                    </ul>
                </div>

                {{-- Kontak --}}
                <div class="col-md-3">
                    <h6 class="text-white fw-bold">
                        Kontak
                    </h6>

                    <p class="small mb-1">
                        <i class="bi bi-envelope me-1"></i>
                        info@pasarkarya.test
                    </p>

                    <p class="small">
                        <i class="bi bi-telephone me-1"></i>
                        0812-0000-0000
                    </p>
                </div>

            </div>

            <hr class="border-secondary">

            <p class="text-center small mb-0">
                &copy; {{ date('Y') }} PasarKarya. Project Ujikom BNSP.
            </p>

        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Navbar scroll effect --}}
    <script>
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('mainNavbar');

            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>

    @stack('scripts')

</body>
</html>