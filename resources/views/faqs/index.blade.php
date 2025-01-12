<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/faq_theme.css') }}">
    <title>TTP - FAQs</title>
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
                <img src="asset/images/logo.png" alt="Logo" id="logo">
                <span class="portal-name text-white">Tanzania Teacher Portal</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('home') }}">
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
                        <a class="nav-link text-white active" href="#">
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
        <div class="img-frame mt-5">
            <img src="{{ asset('asset/images/faqs.svg') }}" alt="thumb-nail" width="400px">
        </div>
        <h2 class="text-dark" style="opacity: 1; z-index: 99;">How can we help you?</h2>
        <input type="search" name="search" id="search" class="form-control text-center" id="search" placeholder="Type keyword">
    </div>

    <!-- second subsection -->
    <div class="container featured-container">
        <div class="card-container" style="width: 100%; max-width: 900px; margin: auto;">
            <ul class="faq-list" style="list-style: none; padding: 0;">
                <li class="faq-item">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">I have completed my registration, when will I get my license?</h5>
                            <small class="text-muted">Sunday, 28th February, 2021</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
                <li class="faq-item">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">What are Professional Standards and Ethics?</h5>
                            <small class="text-muted">Thursday, 9th April, 2020</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
                <li class="faq-item">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">What goes into CPD?</h5>
                            <small class="text-muted">Thursday, 9th April, 2020</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
                <li class="faq-item">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">What is CPD?</h5>
                            <small class="text-muted">Thursday, 9th April, 2020</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
                <li class="faq-item">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">After licensing what next?</h5>
                            <small class="text-muted">Thursday, 9th April, 2020</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
                <li class="faq-item hidden">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">Will applicants pay for licensing and registration?</h5>
                            <small class="text-muted">Thursday, 9th April, 2020</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
                <li class="faq-item hidden">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">Teachers with foreign qualifications:</h5>
                            <small class="text-muted">Thursday, 9th April, 2020</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
                <li class="faq-item hidden">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">How do I register as an in-service teacher?</h5>
                            <small class="text-muted">Thursday, 9th April, 2020</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
                <li class="faq-item hidden">
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h5 class="mb-1">Who is an in-service teacher?</h5>
                            <small class="text-muted">Thursday, 9th April, 2020</small>
                        </div>
                        <button class="btn btn-primary btn-sm">View</button>
                    </div>
                </li>
            </ul>
            <button class="show-more-btn">
                More FAQs <i class="fas fa-chevron-down"></i>
            </button>
        </div>
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

    <!-- Scroll to Top Button -->
    <button onclick="scrollToTop()" id="scrollToTopBtn" class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

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
        document.getElementById('showMoreBtn').addEventListener('click', function() {
            const hiddenItems = document.querySelectorAll('.faq-item.hidden');
            hiddenItems.forEach(item => {
                item.classList.remove('hidden');
            });
            this.style.display = 'none';
        });
    </script>
    <script>
        // Scroll to Top functionality
        const scrollToTopBtn = document.getElementById("scrollToTopBtn");

        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollToTopBtn.classList.add("visible");
            } else {
                scrollToTopBtn.classList.remove("visible");
            }
        };

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
    </script>
    <script>
        // Show More FAQs functionality
        document.addEventListener('DOMContentLoaded', function() {
            const showMoreBtn = document.querySelector('.show-more-btn');
            const hiddenFaqs = document.querySelectorAll('.faq-item.hidden');
            
            showMoreBtn.addEventListener('click', function() {
                hiddenFaqs.forEach(faq => {
                    if (faq.style.display === 'block') {
                        faq.style.display = 'none';
                        showMoreBtn.innerHTML = 'More FAQs <i class="fas fa-chevron-down"></i>';
                        showMoreBtn.classList.remove('show-less');
                    } else {
                        faq.style.display = 'block';
                        showMoreBtn.innerHTML = 'Show Less <i class="fas fa-chevron-down"></i>';
                        showMoreBtn.classList.add('show-less');
                    }
                });
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
