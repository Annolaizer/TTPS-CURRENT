<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>TTPS - Training Details</title>
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

        .training-details {
            padding: 2rem 0;
        }

        .details-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .status-verified {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .status-rejected {
            background-color: #ffebee;
            color: #c62828;
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #333;
        }

        .btn-back {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        .btn-back:hover {
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
            <a href="{{ route('organization.trainings') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Trainings
            </a>
        </div>
    </nav>

    <div class="container training-details">
        <div class="details-card">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h2 class="mb-3">{{ $training->title }}</h2>
                    <span class="status-badge status-{{ $training->status }}">
                        {{ ucfirst($training->status) }}
                    </span>
                </div>
                <div class="text-end">
                    <div class="info-label">Training Code</div>
                    <div class="info-value">{{ $training->training_code }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="info-group">
                        <div class="info-label">Description</div>
                        <div class="info-value">{{ $training->description }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Education Level</div>
                                <div class="info-value">{{ $training->education_level }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Training Phase</div>
                                <div class="info-value">Phase {{ $training->training_phase }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Start Date</div>
                                <div class="info-value">{{ $training->start_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">End Date</div>
                                <div class="info-value">{{ $training->end_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Duration (Days)</div>
                                <div class="info-value">{{ $training->duration_days }} days</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-label">Maximum Participants</div>
                                <div class="info-value">{{ $training->max_participants }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="details-card h-100" style="background-color: var(--light-bg)">
                        <h5 class="mb-3">Venue Details</h5>
                        <div class="info-group">
                            <div class="info-label">Venue Name</div>
                            <div class="info-value">{{ $training->venue_name }}</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Region</div>
                            <div class="info-value">{{ $training->region?->region_name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">District</div>
                            <div class="info-value">{{ $training->district?->district_name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-group">
                            <div class="info-label">Ward</div>
                            <div class="info-value">{{ $training->ward?->ward_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($training->status === 'rejected')
                <div class="mt-4">
                    <div class="info-group">
                        <div class="info-label">Rejection Reason</div>
                        <div class="info-value text-danger">{{ $training->rejection_reason }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
