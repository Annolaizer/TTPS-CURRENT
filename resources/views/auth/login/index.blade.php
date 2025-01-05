<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/auth_login_theme.css') }}">
    <title>TTP - Auth Login</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 bg-photo">
                <h3>Tanzania Teacher Portal</h3>
            </div>
            <div class="col-md-8">
                <div class="top-bar">
                    <span class="text-prime">
                        <br>
                        <a href="{{ route('home') }}" class="home-link">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </span>
                </div>
                <div class="card-container">
                    <div class="image-tab mt-4">
                        <img src="{{ asset('asset/images/logo.png') }}" alt="logo" width="75px">
                        <span>{{ $role }} Login</span>
                    </div>
                    <div class="form-container">
                        <form id="loginForm" action="{{ route('login.authenticate') }}" method="POST" autocomplete="off" novalidate>
                            @csrf
                            <input type="hidden" name="role" value="{{ strtolower(str_replace(' ', '_', $role)) }}">
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <div class="form-floating">
                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                    placeholder="Email address" required autocomplete="off" name="email" value="{{ old('email') }}">
                                <label for="email">Email address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-floating password-field">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Password" required autocomplete="new-password" name="password">
                                <label for="password">Password</label>
                                <i class="password-toggle fas fa-eye" onclick="togglePassword(this)"></i>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn-text-prime">Login</button>
                        </form>
                        <div class="forgot-password">
                            <a href="#">I have forgotten my email/password</a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('login') }}" class="back-link">
                    <i class="fas fa-arrow-alt-circle-left text-prime"></i> Back
                </a>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(element) {
            const input = element.parentElement.querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                element.classList.remove('fa-eye');
                element.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                element.classList.remove('fa-eye-slash');
                element.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
