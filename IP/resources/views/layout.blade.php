<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="/icon.png">
    <title>Nightingale</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/icon.png" alt="nightingale" width="30" height="30" class="d-inline-block align-top me-2">
                Nightingale Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.index') }}"><i class="fas fa-shopping-bag"></i> Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blogs.index') }}"><i class="fa-solid fa-blog"></i> Blogs</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> My Account
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> Wishlist</a></li>
                            <li><a class="dropdown-item" href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fa-solid fa-truck"></i> Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('reviews.index') }}"><i class="fa-solid fa-star-half-stroke"></i> Reviews</a></li>
                            <li><a class="dropdown-item" href="{{ route('coupons.index') }}"><i class="fas fa-gift"></i> Coupons</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex me-2" role="search" action="{{ route('products.search', ['id' => '']) }}" method="GET">
                    <div class="input-group">
                        <input type="search" class="form-control" name="id" placeholder="Enter product id..." aria-label="Search" id="search_id">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <ul class="navbar-nav">
                    @if(Auth::check())
                        <li class="nav-item">
                            <a href="{{ route('notifications.index') }}" class="nav-link">
                                @if($unreadCount > 0)
                                    <i class="fa-solid fa-bell"></i> <span class="badge bg-danger">{{ $unreadCount }}</span>
                                @else
                                    <i class="fa-regular fa-bell"></i>
                                @endif
                            </a>
                        </li>
                        @if(auth()->user()->roles->contains(function ($role) {
                            return in_array($role->name, ['Admin', 'Corp', 'Blogger']);
                        }))
                            <li class="nav-item">
                                <a href="{{ route('action-panel.index') }}" class="nav-link">
                                    <i class="fa-solid fa-cogs"></i>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-user"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('user.index') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link"><i class="fas fa-user-plus"></i> Register</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        @yield('content')
    </div>
    
    <footer class="bg-light border-top py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>Customer Service</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a href="{{ route('requests-complaints.index') }}" class="nav-link p-0 text-muted">Support</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0 text-muted">FAQs</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0 text-muted">Returns</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0 text-muted">Shipping Info</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Company</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a href="#" class="nav-link p-0 text-muted">About Us</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0 text-muted">Careers</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0 text-muted">Privacy Policy</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0 text-muted">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Connect with Us</h5>
                    <ul class="nav">
                        <li class="nav-item"><a href="#" class="nav-link text-muted"><i class="fab fa-facebook-f"></i></a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-muted"><i class="fab fa-twitter"></i></a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-muted"><i class="fab fa-instagram"></i></a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-muted"><i class="fab fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="d-flex justify-content-between pt-3 mt-3 border-top">
                <p class="text-muted mb-0">&copy; 2024 Nightingale Shop. All rights reserved.</p>
                <a href="#" class="text-muted">Support</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('search_id').addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                this.form.submit();
            }
        });
    </script>
</body>
</html>

