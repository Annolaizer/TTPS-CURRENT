<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/auth_register_theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Admin Login - TTPS</title>
    <style>
        .bg-photo {
            background: linear-gradient(rgba(0, 100, 0, 0.7), rgba(0, 100, 0, 0.7)), url("{{ asset('asset/images/bg.jpg') }}");
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bg-photo h3 {
            color: white;
            text-align: center;
            font-size: 2rem;
            padding: 20px;
        }
        .card-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        .image-tab {
            text-align: center;
            margin-bottom: 30px;
        }
        .image-tab img {
            margin-bottom: 15px;
        }
        .image-tab span {
            display: block;
            font-size: 1.5rem;
            color: #198754;
            font-weight: 500;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .password-field {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        .btn-success {
            padding: 12px;
            font-size: 1.1rem;
        }
        .form-select {
            height: 58px !important;
        }
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 bg-photo">
                <h3>Tanzania Teacher Portal<br>Administration</h3>
            </div>
            <div class="col-md-8">
                <div class="card-container">
                    <div class="image-tab mt-4">
                        <img src="{{ asset('asset/images/logo.png') }}" alt="logo" width="75px">
                        <span>Administrator Login</span>
                    </div>
                    <div class="form-container">
                        <form id="loginForm" action="{{ route('login.authenticate') }}" method="POST" autocomplete="off" novalidate>
                            @csrf
                            <input type="hidden" name="role" value="admin">

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="form-floating mb-4">
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    placeholder="Email address" required value="{{ old('email') }}">
                                <label for="email" class="text-muted">Email address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4 password-field">
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                    placeholder="Password" required>
                                <label for="password" class="text-muted">Password</label>
                                <i class="fas fa-eye-slash password-toggle" onclick="togglePassword()"></i>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.querySelector('.password-toggle');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                
                // Disable the submit button to prevent double submission
                submitBtn.prop('disabled', true);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                window.location.href = '{{ route("admin.dashboard") }}';
                            }
                        } else {
                            submitBtn.prop('disabled', false);
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: 'Incorrect email or password.',
                                confirmButtonColor: '#198754'
                            });
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: 'An error occurred while logging in.',
                            confirmButtonColor: '#198754'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
