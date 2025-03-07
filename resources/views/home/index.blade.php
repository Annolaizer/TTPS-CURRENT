<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/home_theme.css') }}">
    <title>TTP - Home</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #009c95;
            padding: 1rem 0;
            margin: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" id="logo">
                <span class="portal-name text-white">Tanzania Teacher Portal</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="#">
                            <i class="fas fa-home d-lg-none me-2"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('news') }}">
                            <i class="fas fa-newspaper d-lg-none me-2"></i>
                            <span>News</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('faqs') }}">
                            <i class="fas fa-question-circle d-lg-none me-2"></i>
                            <span>FAQs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('about') }}">
                            <i class="fas fa-info-circle d-lg-none me-2"></i>
                            <span>About</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt d-lg-none me-2"></i>
                            <span>Login</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main_content">
        <div class="container junk">
            <div class="row">
                <div class="col-md-6">
                    <h1>Tanzania Teacher Portal</h1>
                    <p class="text-justified">Welcome to the National Teaching Council teachers' portal. Get access to accredited training programs and build your portfolio to fulfill your CPD plan and manage your teacher license all in one place. Click on "Register Now" to get started.</p>
                   <a href="{{ route('register') }}"><button class="btn-register w-100">Register Now</button></a> 
                </div>
                <div class="col-md-6">
                    <div class="illustration">
                        <img src="{{ asset('asset/images/d923b7761e014649a904625cac84bd7d.svg') }}" alt="Illustration" width="450px">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Sub section one -->
    <div class="container my-5">
        <div class="card-container">
            <!-- Teacher Guidelines Card -->
            <div class="card">
                <i class="fas fa-chalkboard-teacher icon"></i>
                <h5 class="card-title">Teacher Guidelines</h5>
                <p class="card-text">Download the TPG guidelines for teachers</p>
            </div>
            <!-- Organization Guidelines Card -->
            <div class="card">
                <i class="fas fa-building icon"></i>
                <h5 class="card-title">Organization Guidelines</h5>
                <p class="card-text">Download the TPG guidelines for organizations</p>
            </div>
            <!-- Training Videos Card -->
            <div class="card">
                <i class="fas fa-video icon"></i>
                <h5 class="card-title">Training Videos</h5>
                <p class="card-text">View training videos</p>
            </div>
            <!-- NTC Training Programs Card -->
            <div class="card">
                <i class="fas fa-users icon"></i>
                <h5 class="card-title">NTC Training Programs</h5>
                <p class="card-text">Browse NTC's public training programs</p>
            </div>
        </div>
    </div>

    <hr>

    <!-- second subsection -->
    <div class="container featured-container">
        <h3 class="mb-4">Featured Articles</h3>
        <div class="d-flex flex-wrap justify-content-center">
            <!-- Card 1 -->
            <div class="article-card">
                <h5>GUIDELINES FOR LETTER OF PROFESSIONAL STANDING</h5>
                <p>Wednesday, 3rd July, 2024</p>
                <p>GUIDELINES FOR LETTER OF PROFESSIONAL STANDING</p>
                <a href="#">Read more</a>
            </div>
            <!-- Card 2 -->
            <div class="article-card">
                <h5>SENSITIZATION OF NON-PROFESSIONAL TEACHERS ON ISSUANCE OF TEMPORARY CERTIFICATION</h5>
                <p>Saturday, 9th July, 2022</p>
                <p>SENSITIZATION OF NON-PROFESSIONAL TEACHERS...</p>
                <a href="#">Read more</a>
            </div>
            <!-- Card 3 -->
            <div class="article-card">
                <h5>INVITATION TO TENDER TPG UPDATES</h5>
                <p>Thursday, 14th April, 2022</p>
                <p>INVITATION TO TENDER TPG UPDATES</p>
                <a href="#">Read more</a>
            </div>
        </div>
        <a href="#" class="view-more">View more articles</a>
    </div>

    <!-- accredited service provider --> 
    <section class="service-providers-section">
        <div class="container">
            <h4 class="mb-4 text-center fw-bold">Accredited Service Providers</h4>
            <div class="row row-cols-3 row-cols-md-4 row-cols-lg-6 g-4 service-providers-container" style="width: 90%; margin: auto;">
                <!-- Image 1 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/one.jpg') }}" class="card-img-top" alt="Image 1">
                    </div>
                </div>
                <!-- Image 2 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/two.png') }}" class="card-img-top" alt="Image 2">
                    </div>
                </div>
                <!-- Image 3 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/three.jpg') }}" class="card-img-top" alt="Image 3">
                    </div>
                </div>
                <!-- Image 4 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/four.jpg') }}" class="card-img-top" alt="Image 4">
                    </div>
                </div>
                <!-- Image 5 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/five.jpg') }}" class="card-img-top" alt="Image 5">
                    </div>
                </div>
                <!-- Image 6 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/six.jpg') }}" class="card-img-top" alt="Image 6">
                    </div>
                </div>
                <!-- Image 7 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/seven.jpeg') }}" class="card-img-top" alt="Image 7">
                    </div>
                </div>
                <!-- Image 8 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/eight.png') }}" class="card-img-top" alt="Image 8">
                    </div>
                </div>
                <!-- Image 9 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/nine.jpg') }}" class="card-img-top" alt="Image 9">
                    </div>
                </div>
                <!-- Image 10 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/ten.png') }}" class="card-img-top" alt="Image 10">
                    </div>
                </div>
                <!-- Image 11 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/eleven.jpg') }}" class="card-img-top" alt="Image 11">
                    </div>
                </div>
                <!-- Image 12 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/twelve.png') }}" class="card-img-top" alt="Image 12">
                    </div>
                </div>
                <!-- Image 13 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/thirteen.jpeg') }}" class="card-img-top" alt="Image 13">
                    </div>
                </div>
                <!-- Image 14 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/forteen.jpeg') }}" class="card-img-top" alt="Image 14">
                    </div>
                </div>
                <!-- Image 15 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/fifteen.jpg') }}" class="card-img-top" alt="Image 15">
                    </div>
                </div>
                <!-- Image 16 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/sixteen.jpg') }}" class="card-img-top" alt="Image 16">
                    </div>
                </div>
                <!-- Image 17 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/seventeen.jpg') }}" class="card-img-top" alt="Image 17">
                    </div>
                </div>
                <!-- Image 18 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/eighteen.png') }}" class="card-img-top" alt="Image 18">
                    </div>
                </div>
                <!-- Image 19 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/nineteen.jpg') }}" class="card-img-top" alt="Image 19">
                    </div>
                </div>
                <!-- Image 20 -->
                <div class="col">
                    <div class="service-provider-card">
                        <img src="{{ asset('asset/images/twenty.jpg') }}" class="card-img-top" alt="Image 20">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- sata footer -->
    <!-- Section before Footer -->
<div class="container-fluid bg-pulp text-light py-5">
    <div class="container">
      <div class="row">
        <!-- Column 1 -->
        <div class="col-md-3 mb-4">
          <h5 class="text-prime">Teacher Portal Tanzania</h5>
          <p>Education Close Street, Accra</p>
          <p>
            <strong>Phone:</strong> +23350 383 2454<br>
            <strong>Email:</strong> <a href="mailto:info@ntc.gov.gh" class="text-light">info@ntc.gov.gh</a><br>
            <strong>Website:</strong> <a href="http://ntc.gov.gh" class="text-light" target="_blank">ntc.gov.gh</a>
          </p>
        </div>
  
        <!-- Column 2 -->
        <div class="col-md-3 mb-4">
          <h5 class="text-prime">GTLE</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-light">Purchase e-voucher</a></li>
            <li><a href="#" class="text-light">Examination guidelines</a></li>
            <li><a href="#" class="text-light">Sample exam questions</a></li>
            <li><a href="#" class="text-light">Rules and regulations</a></li>
            <li><a href="#" class="text-light">Index Number Verification</a></li>
          </ul>
        </div>
  
        <!-- Column 3 -->
        <div class="col-md-3 mb-4">
          <h5 class="text-prime">Continuous Professional Development</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-light">TCPD guidelines</a></li>
          </ul>
  
          <h5 class="text-prime mt-4">Regulatory Services</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-light">Licensing and registration of teachers</a></li>
            <li><a href="#" class="text-light">Inspection and monitoring compliance</a></li>
            <li><a href="#" class="text-light">Accredited Organization Verification</a></li>
            <li><a href="#" class="text-light">Licensed Teacher Verification</a></li>
            <li><a href="#" class="text-light">Licensed Teacher Register</a></li>
          </ul>
        </div>
  
        <!-- Column 4 -->
        <div class="col-md-3 mb-4">
          <h5 class="text-prime">Partner Links</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-light">Ministry of Education</a></li>
            <li><a href="#" class="text-light">Ghana Education Service</a></li>
            <li><a href="#" class="text-light">Teacher Education Institutions</a></li>
            <li><a href="#" class="text-light">National Service Scheme</a></li>
            <li><a href="#" class="text-light">National Accreditation Board</a></li>
            <li><a href="#" class="text-light">National Council for Tertiary Education</a></li>
            <li><a href="#" class="text-light">T-Tel</a></li>
            <li><a href="#" class="text-light">Conference of Directors of Education</a></li>
            <li><a href="#" class="text-light">Africa Federation of Teachers</a></li>
            <li><a href="#" class="text-light">Regulatory Authority</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  
    <!-- Footer -->
    <div class="footer text-center" style="padding: 20px;">
        &copy; 2024 National Teaching Council. All Rights Reserved.
    </div>

    <div class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateTopBarHeight() {
                const topBar = document.querySelector('.top_bar');
                const navbar = document.querySelector('.navbar');
                if (topBar && navbar) {
                    const height = topBar.offsetHeight;
                    document.documentElement.style.setProperty('--topbar-actual-height', height + 'px');
                    navbar.style.top = height + 'px';
                }
            }
            
            // Update on load and resize
            updateTopBarHeight();
            window.addEventListener('resize', updateTopBarHeight);
            // Update when content might change
            window.addEventListener('load', updateTopBarHeight);
            // Additional check after images and resources are loaded
            window.addEventListener('load', function() {
                setTimeout(updateTopBarHeight, 100);
            });
        });
    </script>
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
    <script>
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    <script src="assets/js/main.js"></script>
</body>
</html>
