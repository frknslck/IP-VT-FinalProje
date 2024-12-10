<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Nightingale</title>
</head>
<body>
    <nav class="py-2 bg-body-tertiary border-bottom">
        <div class="container d-flex flex-wrap">
            <ul class="nav me-auto">
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2 active">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Features</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Pricing</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">FAQs</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">About</a></li>
            </ul>
            <ul class="nav">
                <li class="nav-item"><a href="{{route('wishlist.index')}}" class="nav-link link-body-emphasis px-2"><i class="fas fa-heart"></i> Wishlist</a></li>
                <li class="nav-item"><a href="{{route('cart.index')}}" class="nav-link link-body-emphasis px-2"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                <li class="nav-item"><a href="{{route('user.index')}}" class="nav-link link-body-emphasis px-2"><i class="fa-solid fa-user"></i> {{Auth::user() ? Auth::user()->name : 'Guest'}}</a></li>
            </ul>
        </div>
    </nav>
    <header class="py-3 mb-4 border-bottom">
        <div class="container d-flex flex-wrap justify-content-center">
            <a href="/" class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto link-body-emphasis text-decoration-none">
                <i class="fa-solid fa-dove fa-2x me-2"></i>
                <span class="fs-4">Nightingale Shop</span>
            </a>
            <form class="col-12 col-lg-auto mb-3 mb-lg-0" role="search">
                <input type="search" class="form-control" placeholder="Enter product id..." aria-label="Search">
            </form>
        </div>
    </header>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
