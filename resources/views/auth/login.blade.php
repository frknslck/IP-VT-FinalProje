<!-- login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nightingale Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            height: 100vh;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
        }
        .card-img {
            border-radius: 1rem 0 0 1rem;
            object-fit: cover;
            height: 100%;
        }
        .logo {
            max-height: 50px;
        }
        .btn-login {
            font-size: 0.9rem;
            letter-spacing: 0.05rem;
            padding: 0.75rem 1rem;
        }
        .animate-in {
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-10">
                <div class="card animate-in">
                    <div class="row g-0">
                        <div class="col-md-6 d-none d-md-block">
                            <img src="./nightingale1.jpg" alt="Nightingale" class="img-fluid card-img">
                        </div>
                        <div class="col-md-6">
                            <div class="card-body p-5">
                                <div class="text-center mb-4">
                                    <img src="/icon.png" alt="Nightingale Shop" class="logo mb-2">
                                    <h4 class="text-center">Nightingale Shop</h4>
                                </div>
                                <h5 class="card-title text-center mb-4">Sign In</h5>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                        <label class="form-check-label" for="remember_me">
                                            Remember me
                                        </label>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Sign in</button>
                                    </div>
                                </form>
                                <hr class="my-4">
                                <div class="d-grid mb-2">
                                    <button class="btn btn-google btn-login text-uppercase fw-bold" type="submit">
                                        <i class="fab fa-google me-2"></i> Sign in with Google
                                    </button>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-facebook btn-login text-uppercase fw-bold" type="submit">
                                        <i class="fab fa-facebook-f me-2"></i> Sign in with Facebook
                                    </button>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="#" class="small">Forgot password?</a>
                                </div>
                                <div class="text-center mt-2">
                                    <span class="small">Don't have an account?</span>
                                    <a href="{{ route('register') }}" class="small fw-bold ms-1">Sign up</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>