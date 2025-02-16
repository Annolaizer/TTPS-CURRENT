<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/login_theme.css')}}">
    <title>TTP - Login</title>
</head>
<body>
<div class="container-fluid">
<div class="row">
    <div class="col-md-4 bg-photo">
        <h3 class="text-light">Tanzania Teacher Portal</h3>
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
            <div class="image-tab">
                <img src="{{ asset('asset/images/logo.png') }}" alt="logo" width="75px">
                <span>Tanzania Teacher Portal</span>
            </div>
            <div class="link-tabs">
                <ol>
                    <a href="{{ route('login.role', ['role' => 'teacher']) }}">
                        <li>
                            <i class="fas fa-chalkboard-teacher"></i> Teacher Login
                        </li>
                    </a>
                    <a href="{{ route('login.role', ['role' => 'organization']) }}">
                        <li>
                            <i class="fas fa-building"></i> Organization Login
                        </li>
                    </a>
                    <a href="{{ route('login.role', ['role' => 'cpd_facilitator']) }}">
                        <li>
                            <i class="fas fa-user"></i> CPD Facilitator Login
                        </li>
                    </a>
                </ol>
            </div>
            <div class="signup-prompt">
                <span>Don't have an account? </span>
                <a href="{{route('register')}}">Create one now!</a>
            </div>
        </div>
    </div>
</div>
</div>

    <div class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script>
        // Scroll to top functionality
        const scrollToTopBtn = document.querySelector('.scroll-to-top');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>