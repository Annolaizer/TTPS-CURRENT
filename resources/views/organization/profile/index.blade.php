<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>TTPS - Organisation Profile</title>
    <style>
        :root {
            --primary-color: #009c95;
            --secondary-color: #cc3344;
            --hover-color: #007c77;
            --hover-dark: #006c68;
            --light-bg: #f8f9fa;
            --navbar-height: 70px;
            --container-max-width: 1200px;
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

        .profile-section {
            padding: 2rem 0;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--hover-color));
            color: white;
            padding: 2rem;
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 4px solid rgba(255, 255, 255, 0.2);
        }

        .profile-avatar i {
            font-size: 3rem;
            color: var(--primary-color);
        }

        .profile-name {
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .profile-role {
            text-align: center;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .profile-body {
            padding: 2rem;
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .info-value {
            color: #333;
            padding: 0.75rem;
            background: var(--light-bg);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .edit-button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .edit-button:hover {
            background: var(--hover-color);
            color: white;
        }

        .contact-info {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .contact-info h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .contact-item i {
            color: var(--primary-color);
            margin-right: 1rem;
            width: 20px;
        }

        @media (max-width: 768px) {
            .profile-header {
                padding: 1.5rem;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
            }

            .profile-body {
                padding: 1.5rem;
            }
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
            <a href="{{ route('organization.dashboard') }}" class="edit-button">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </nav>

    <div class="container profile-section">
        <div class="row">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-building"></i>
                        </div>
                        <h2 class="profile-name">{{ $organization->name }}</h2>
                        <div class="profile-role">Organization Account</div>
                    </div>
                    <div class="profile-body">
                        <div class="info-group">
                            <div class="info-label">Organization Name</div>
                            <div class="info-value">{{ $organization->name }}</div>

                            <div class="info-label">Registration Number</div>
                            <div class="info-value">{{ $organization->registration_number }}</div>

                            <div class="info-label">Type of Organization</div>
                            <div class="info-value">{{ $organization->type }}</div>

                            <div class="info-label">Establishment Date</div>
                            <div class="info-value">{{ $organization->established_date }}</div>
                        </div>

                        <a href="{{ route('organization.profile.setup') }}" class="edit-button">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="contact-info">
                    <h3><i class="fas fa-address-card"></i> Contact Information</h3>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>{{ $organization->phone ?? 'Not provided' }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $organization->address ?? 'Not provided' }}</span>
                    </div>
                </div>

                <div class="contact-info">
                    <h3><i class="fas fa-shield-alt"></i> Account Security</h3>
                    <div class="contact-item">
                        <i class="fas fa-user-shield"></i>
                        <span>Last login: {{ $user->last_login_at ?? 'Never' }}</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Account created: {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
