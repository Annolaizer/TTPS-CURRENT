<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/news_theme.css') }}">
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
    <title>TTP - News</title>
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
                        <a class="nav-link text-white active" href="#">
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
        <h2 class="text-white" style="opacity: 1; z-index: 99;">Welcome to the Teacher Portal</h2>
        <a href="{{ route('home') }}"><button type="submit" class="btn btn-prime mt-5" style="width: 200px;">Home</button></a>
    </div>

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

<hr>

    <!-- second subsection stories -->
    <div class="container featured-container">
        <h3 class="mb-4">Latest Stories</h3>
        
        <!-- Search Bar -->
        <div class="news-search">
            <input type="text" id="newsSearch" placeholder="Search news...">
            <i class="fas fa-search"></i>
        </div>

        <!-- News List -->
        <div class="news-list">
            <!-- News Item 1 -->
            <div class="news-item">
                <h4>Teacher Registration Guidelines Updated for 2024</h4>
                <div class="news-meta">
                    <span><i class="far fa-calendar"></i> December 10, 2023</span>
                    <span><i class="far fa-clock"></i> 2:30 PM</span>
                </div>
                <div class="news-content">
                    The Teacher Registration Council has released updated guidelines for teacher registration in 2024. The new guidelines include digital submission requirements and streamlined verification processes.
                </div>
                <a href="#" class="news-link">Read more <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- News Item 2 -->
            <div class="news-item">
                <h4>Professional Development Workshop Series Announced</h4>
                <div class="news-meta">
                    <span><i class="far fa-calendar"></i> December 8, 2023</span>
                    <span><i class="far fa-clock"></i> 10:15 AM</span>
                </div>
                <div class="news-content">
                    A series of professional development workshops will be conducted across various regions starting January 2024. Topics include modern teaching methodologies and digital classroom management.
                </div>
                <a href="#" class="news-link">Read more <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- News Item 3 -->
            <div class="news-item">
                <h4>New Online Portal Features Released</h4>
                <div class="news-meta">
                    <span><i class="far fa-calendar"></i> December 5, 2023</span>
                    <span><i class="far fa-clock"></i> 3:45 PM</span>
                </div>
                <div class="news-content">
                    The teacher portal has been updated with new features including document verification tracking and instant certificate download options for registered teachers.
                </div>
                <a href="#" class="news-link">Read more <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- News Item 4 -->
            <div class="news-item">
                <h4>Annual Teacher Excellence Awards 2023</h4>
                <div class="news-meta">
                    <span><i class="far fa-calendar"></i> December 3, 2023</span>
                    <span><i class="far fa-clock"></i> 11:00 AM</span>
                </div>
                <div class="news-content">
                    Nominations are now open for the Annual Teacher Excellence Awards 2023. Categories include Innovation in Teaching, Community Impact, and Leadership in Education.
                </div>
                <a href="#" class="news-link">Read more <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- News Item 5 -->
            <div class="news-item">
                <h4>Education Policy Forum Highlights</h4>
                <div class="news-meta">
                    <span><i class="far fa-calendar"></i> December 1, 2023</span>
                    <span><i class="far fa-clock"></i> 9:20 AM</span>
                </div>
                <div class="news-content">
                    Key takeaways from the recent Education Policy Forum, including upcoming changes to teacher certification requirements and new professional development opportunities.
                </div>
                <a href="#" class="news-link">Read more <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- Additional Hidden News Items -->
            <div class="news-item hidden-news">
                <h4>Educational Technology Integration Workshop</h4>
                <div class="news-meta">
                    <span><i class="far fa-calendar"></i> November 28, 2023</span>
                    <span><i class="far fa-clock"></i> 1:45 PM</span>
                </div>
                <div class="news-content">
                    Join us for a comprehensive workshop on integrating modern educational technology in classrooms. Learn about the latest tools and methodologies for enhanced learning experiences.
                </div>
                <a href="#" class="news-link">Read more <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="news-item hidden-news">
                <h4>Teacher Certification Deadline Extension</h4>
                <div class="news-meta">
                    <span><i class="far fa-calendar"></i> November 25, 2023</span>
                    <span><i class="far fa-clock"></i> 10:30 AM</span>
                </div>
                <div class="news-content">
                    The deadline for teacher certification renewal has been extended to accommodate the high volume of applications. New deadline details and submission guidelines inside.
                </div>
                <a href="#" class="news-link">Read more <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="news-item hidden-news">
                <h4>School Leadership Conference 2024</h4>
                <div class="news-meta">
                    <span><i class="far fa-calendar"></i> November 22, 2023</span>
                    <span><i class="far fa-clock"></i> 3:15 PM</span>
                </div>
                <div class="news-content">
                    Early bird registration now open for the annual School Leadership Conference. Featured speakers include renowned educators and policy makers from across the country.
                </div>
                <a href="#" class="news-link">Read more <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- More News Button -->
        <button class="more-news-btn" id="moreNewsBtn">
            More News <i class="fas fa-chevron-down"></i>
        </button>
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
        // News Search Functionality
        document.getElementById('newsSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const newsItems = document.querySelectorAll('.news-item');
            
            newsItems.forEach(item => {
                const title = item.querySelector('h4').textContent.toLowerCase();
                const content = item.querySelector('.news-content').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        // More News Button Functionality
        document.getElementById('moreNewsBtn').addEventListener('click', function() {
            const hiddenNews = document.querySelectorAll('.hidden-news');
            const btn = this;
            
            hiddenNews.forEach(item => {
                if (item.style.display === 'none' || !item.style.display) {
                    item.style.display = 'block';
                    btn.innerHTML = 'Show Less <i class="fas fa-chevron-up"></i>';
                } else {
                    item.style.display = 'none';
                    btn.innerHTML = 'More News <i class="fas fa-chevron-down"></i>';
                }
            });
        });

        // Update search to include hidden news items
        document.getElementById('newsSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const newsItems = document.querySelectorAll('.news-item');
            const moreNewsBtn = document.getElementById('moreNewsBtn');
            
            // Hide the "More News" button when searching
            moreNewsBtn.style.display = searchTerm ? 'none' : 'block';
            
            newsItems.forEach(item => {
                const title = item.querySelector('h4').textContent.toLowerCase();
                const content = item.querySelector('.news-content').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
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
