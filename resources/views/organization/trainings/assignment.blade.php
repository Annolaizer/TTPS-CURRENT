<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <title>TTPS - Trainings</title>
    <style>
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f9fa;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            padding-top: var(--navbar-height);
            color: #333;
        }

        .navbar {
            background-color: var(--primary-color);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1020;
            min-height: var(--navbar-height);
            padding: 0.625rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 156, 149, 0.25);
        }

        .pagination {
            margin-top: 1.5rem;
            justify-content: center;
        }

        .page-link {
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-filter:hover {
            background-color: var(--hover-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" id="logo" style="height: 40px;">
                <span class="portal-name" style="color: white; margin-left: 10px;">Tanzania Teacher Portal</span>
            </a>
            <a href="{{ route('organization.trainings') }}" class="btn btn-filter text-white">
                <i class="fas fa-arrow-left"></i> Back to Trainings
            </a>
        </div>
    </nav>

    <div class="container training-section">
         <!-- Training Info -->
         <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Training Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Training Code:</strong> {{ $training->training_code }}</p>
                                <p><strong>Title:</strong> {{ $training->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Start Date:</strong> {{ $training->start_date->format('d M Y') }}</p>
                                <p><strong>End Date:</strong> {{ $training->end_date->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Training  Phase Modal -->


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- adding Training phase script -->


    <!-- assigning training participants script -->
</body>
</html>
