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
    <title>{{ ucfirst(str_replace('_', ' ', $role)) }} - Register</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 bg-photo">
                <h3>Teacher Portal Tanzania</h3>
            </div>
            <div class="col-md-8">
                <div class="top-bar">
                    <span class="text-green">
                        <br>
                        <a href="{{ route('home') }}" class="home-link">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </span>
                </div>
                <div class="card-container">
                    <div class="image-tab mt-4">
                        <img src="{{ asset('asset/images/logo.png') }}" alt="logo" width="75px">
                        <span>{{ ucfirst(str_replace('_', ' ', $role)) }} Signup</span>
                    </div>
                    <div class="form-container">
                        <form action="{{ route('register.store') }}" method="POST" autocomplete="off" novalidate>
                            @csrf
                            <input type="hidden" name="role" value="{{ $role }}">
                            <div class="input-row">
                                <div class="input-col">
                                    <div class="form-floating">
                                        <input type="text" id="firstname" name="firstname" class="form-control @error('firstname') is-invalid @enderror" 
                                            placeholder="First name" required value="{{ old('firstname') }}">
                                        <label for="firstname">First name</label>
                                        @error('firstname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="input-col">
                                    <div class="form-floating">
                                        <input type="text" id="lastname" name="lastname" class="form-control @error('lastname') is-invalid @enderror" 
                                            placeholder="Last name" required value="{{ old('lastname') }}">
                                        <label for="lastname">Last name</label>
                                        @error('lastname')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating">
                                <input type="text" id="othername" name="othername" class="form-control @error('othername') is-invalid @enderror" 
                                    placeholder="Other names" value="{{ old('othername') }}">
                                <label for="othername">Other names</label>
                                @error('othername')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="input-row">
                                <div class="input-col">
                                    <div class="form-floating">
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                            placeholder="Email address" required value="{{ old('email') }}">
                                        <label for="email">Email address</label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="input-col">
                                    <div class="form-floating">
                                        <input type="tel" id="telephone" name="telephone" class="form-control @error('telephone') is-invalid @enderror" 
                                            placeholder="Telephone" required value="{{ old('telephone') }}">
                                        <label for="telephone">Telephone</label>
                                        @error('telephone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="input-row">
                                <div class="input-col">
                                    <div class="form-floating password-field">
                                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                            placeholder="Password" required autocomplete="new-password">
                                        <label for="password">Password</label>
                                        <i class="password-toggle fas fa-eye" onclick="togglePassword(this)"></i>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="input-col">
                                    <div class="form-floating password-field">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" 
                                            placeholder="Confirm password" required autocomplete="new-password">
                                        <label for="password_confirmation">Confirm password</label>
                                        <i class="password-toggle fas fa-eye" onclick="togglePassword(this)"></i>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="mt-3">Register</button>
                        </form>
                    </div>
                </div>
                <a href="{{ route('register') }}" class="back-link">
                    <i class="fas fa-arrow-alt-circle-left text-success"></i> Back
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

        $(document).ready(function() {
            $('form').on('submit', function(e) {
                e.preventDefault();
                
                let form = $(this);
                let submitBtn = form.find('button[type="submit"]');
                submitBtn.prop('disabled', true);
                
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        submitBtn.prop('disabled', false);
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirect;
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false);
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '<ul style="text-align: left; list-style-type: disc;">';
                            for (let field in errors) {
                                errorMessage += `<li>${errors[field][0]}</li>`;
                            }
                            errorMessage += '</ul>';
                            
                            Swal.fire({
                                title: 'Validation Error!',
                                html: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            let errorMessage = 'Registration failed.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                title: 'Error!',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>