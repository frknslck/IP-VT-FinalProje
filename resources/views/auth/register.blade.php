<!-- register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nightingale Shop</title>
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
            border-radius: 0 1rem 1rem 0;
            object-fit: cover;
            height: 100%;
        }
        .logo {
            max-height: 50px;
        }
        .btn-register {
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
                        <div class="col-md-6">
                            <div class="card-body p-5">
                                <div class="text-center mb-4">
                                    <img src="/icon.png" alt="Nightingale Shop" class="logo mb-2">
                                    <h4 class="text-center">Nightingale Shop</h4>
                                </div>
                                <h5 class="card-title text-center mb-4">Create an Account</h5>
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="tel" class="form-control" id="tel_no" name="tel_no" placeholder="Phone Number" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-register text-uppercase fw-bold" type="submit">Sign Up</button>
                                    </div>
                                </form>
                                <hr class="my-4">
                                <div class="d-grid mb-2">
                                    <button class="btn btn-google btn-register text-uppercase fw-bold" type="submit">
                                        <i class="fab fa-google me-2"></i> Sign up with Google
                                    </button>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-facebook btn-register text-uppercase fw-bold" type="submit">
                                        <i class="fab fa-facebook-f me-2"></i> Sign up with Facebook
                                    </button>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="small">Already have an account?</span>
                                    <a href="{{ route('login') }}" class="small fw-bold ms-1">Sign in</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-none d-md-block">
                            <img src="./nightingale2.jpg" alt="Nightingale" class="img-fluid card-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>